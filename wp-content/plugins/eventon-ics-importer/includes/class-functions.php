<?php
/** 
 * ICS functions
 * @version 1.0
 */

class evoics_fnc{

	function __construct(){
		$this->options = get_option('evcal_options_evoics_1');

		
	}

// ICS event functions
	function get_events_from_ics($file){
		require_once('lib/class.iCalReader.php');
		$ICS = new ical($file);

		return $ICS->events();
	}

	
// IMPORTING EVENT
	function import_event($event){
		if(empty($event['status']) || $event['status']=='ns' ) return false;	
		
		// alredy imported events
		if(!empty($event['imported_event_id'])){

			// if sync already imported set to yes
			if(evo_settings_check_yn($this->options, 'evoics_sync_fetched') ){

				// update description
				$description = !empty($event['event_description'])? $event['event_description']:'';
	            $my_post = array(
	                'ID'           => $event['imported_event_id'],
	                'post_content' => $description,
	            );
	            wp_update_post( $my_post );

				$this->save_event_post_data($event['imported_event_id'], $event);
				return $event['imported_event_id'];
			}

			return false;

		}else{
			if($post_id = $this->create_post($event) ){
				$this->save_event_post_data($post_id, $event);				
				return $post_id;		
			}

			return false;
		}
		
	}

// save custom meta fields
	function save_event_post_data($post_id,$post_data){
		global $evoics;
	 	
	 	// for all fields
	 	foreach($this->get_all_fields() as $fieldvar=>$field){

	 		// for empty values
	 		if(empty($post_data[$field])) continue;

	 		// adjust array field value
	 		$fieldvar = (is_numeric($fieldvar))? $field: $fieldvar;
	 		//$value = addslashes(htmlspecialchars_decode($post_data[$field]) );	
	 		$value = addslashes(html_entity_decode($post_data[$field]) );	

	 		$fieldSaved = false;		 		

	 		// skip fields
	 		if(in_array($field, apply_filters('evoics_skipped_save_fields',array(
	 			'event_description',
	 			'event_name',
	 			'event_start_date',
	 			'event_start_time',
	 			'event_end_date',
	 			'event_end_time', 
	 			'evcal_location_name',
	 			'UID'
	 		) 
	 		))) continue;

	 		// yes no fields
	 		if(in_array($field, array('all_day'))){
	 			$value = strtolower($value);
	 			$this->create_custom_fields($post_id, $fieldvar, $value);	
				$fieldSaved = true;
	 		}

	 		// save non saved fields as post type meta
	 		if(!$fieldSaved){
	 			$this->create_custom_fields($post_id, $fieldvar, $value);
	 		}

	 		// pluggable hook
	 		do_action('evoics_save_event_custom_data', $post_id, $post_data, $field);

	 	} // endforeach
	 	
	 	// save event date and time information
	 		if(isset($post_data['event_start_date'])&& isset($post_data['event_end_date']) ){
				$start_time = !empty($post_data['event_start_time'])?
					explode(":",$post_data['event_start_time']): false;
				$end_time = !empty($post_data['event_end_time'])?
					explode(":",$post_data['event_end_time']):false;
				
				$date_array = array(
					'evcal_start_date'=>$post_data['event_start_date'],
					'evcal_start_time_hour'=>( $start_time? $start_time[0]: ''),
					'evcal_start_time_min'=>( $start_time? $start_time[1]: ''),
					'evcal_st_ampm'=> ( $start_time? $start_time[2]: ''),
					'evcal_end_date'=>$post_data['event_end_date'], 										
					'evcal_end_time_hour'=>( $end_time? $end_time[0]:''),
					'evcal_end_time_min'=>( $end_time? $end_time[1]:''),
					'evcal_et_ampm'=>( $end_time? $end_time[2]:''),

					'evcal_allday'=>( !empty($post_data['all_day'])? $post_data['all_day']:'no'),
				);
				
				$proper_time = eventon_get_unix_time($date_array, 'm/d/Y');
				
				// save required start time variables
				$this->create_custom_fields($post_id, 'evcal_srow', $proper_time['unix_start']);
				$this->create_custom_fields($post_id, 'evcal_erow', $proper_time['unix_end']);		
			}
	
	 	// event location fields
	 		if( !empty($post_data['evcal_location_name']) ){

	 			$termName = esc_attr(stripslashes($post_data['evcal_location_name']));

	 			$term = term_exists( $termName, 'event_location');
	 			if($term !== 0 && $term !== null){
	 				// assign location term to the event		 			
	 				wp_set_object_terms( $post_id, $termName, 'event_location');		
	 			}else{
	 				$term_slug = str_replace(" ", "-", $termName);

					// create wp term
					$newterm = wp_insert_term( $termName, 'event_location', array('slug'=>$term_slug) );

					if(!is_wp_error($newterm)){
						$term_meta = array();
						$termID = (int)$newterm['term_id'];

						// generate latLon
						if(isset($post_data['evcal_location_name']))
							$latlon = eventon_get_latlon_from_address($post_data['evcal_location_name']);

						// latitude and longitude
						$term_meta['location_lon'] = (!empty($_POST['evcal_lon']))? $_POST['evcal_lon']:
							(!empty($latlon['lng'])? floatval($latlon['lng']): null);
						$term_meta['location_lat'] = (!empty($_POST['evcal_lat']))? $_POST['evcal_lat']:
							(!empty($latlon['lat'])? floatval($latlon['lat']): null);
						
						$term_meta['location_address'] = $termName;

						//update_option("taxonomy_".$termID, $term_meta);
						evo_save_term_metas('event_location', $termID, $term_meta);
												
						wp_set_object_terms( $post_id,  $termID , 'event_location', false);					
					}
	 			}

	 			// set location generation to yes
	 			$this->create_custom_fields( $post_id, 'evcal_gmap_gen', 'yes');
	 		}
		
		// UID field if passed
			if(!empty($post_data['UID']) ){
				$this->create_custom_fields($post_id, '_evoics_uid', $value);	
			}

	 	// Pluggable filter
	 		do_action('evoics_save_additional_data', $post_id, $post_data);
	}


/** Create the event post */
	function create_post($data) {
		$evoHelper = new evo_helper();

		// content for the event
		$content = (!empty($data['event_description'])?$data['event_description']:null );
		$content = str_replace('\,', ",", stripslashes($content) );

		$ICSopt2 = get_option('evcal_options_evoics_1');

		$publishStatus = evo_settings_check_yn($ICSopt2,'EVOICS_status_publish')? 'publish': 'draft';

		return $evoHelper->create_posts(array(
			'post_status'=>$publishStatus,
			'post_type'=>'ajde_events',
			'post_title'=>convert_chars(stripslashes($data['event_name'])),
			'post_content'=>$content
		));
    }
	function create_custom_fields($post_id, $field, $value) {       
        add_post_meta($post_id, $field, $value);
    }
	function get_author_id() {
		$current_user = wp_get_current_user();
        return (($current_user instanceof WP_User)) ? $current_user->ID : 0;
    }
    // upload and return event featured image
	    function upload_image($url, $event_name){
	    	return $this->upload_file($url, $event_name);
	    }

// process fetched event data
	function process_fetched_events($events_array){
		if(sizeof($events_array)==0) return false;

		$imported_events = $this->get_imported_event_ids();

		$data = array();
		foreach($events_array as $index=>$event){

			$data[$index] = $this->process_fetched_data($event);

			// status
			if(is_array($imported_events) && in_array($event['UID'], $imported_events)){
				$site_event_id = array_search($event['UID'], $imported_events);
				$data[$index]['status'] = 'as';
				$data[$index]['imported_event_id'] = $site_event_id;
			}else{
				$data[$index]['status'] = 'ss';
			}

			// validate required fields
			if( empty($data[$index]['event_start_date'])){
				$data[$index]['log'] = 'Start date missing';
				$data[$index]['status'] = 'err';
			}
		}

		return $data;
	}
	function process_fetched_data($ics_data){
		// defaults
			$ics_data['evcal_allday'] ='no';
			$timezoneADJ = evo_settings_check_yn($this->options,'EVOICS_auto_timezone');
			$alldayADJ = evo_settings_check_yn($this->options,'EVOICS_auto_allday_dis');
			$WPtimezone = get_option( 'timezone_string');
				$WPtimezone = (empty($WPtimezone)? false:$WPtimezone );

		// event date validation
			if(!empty($ics_data['DTSTART'])){
				$dt = new DateTime($ics_data['DTSTART']);

				// set wp default timezone 
				if($timezoneADJ && $WPtimezone){
					$dt->setTimeZone( new DateTimezone($WPtimezone) );				
				}else{
					$dt->setTimeZone( new DateTimezone( 'UTC') );
				}

				
				$event_start_date_val= $dt->format('m/d/Y');
				$event_start_time_val= $dt->format('g:i:a');
			}else{ 
				$event_start_date_val =null;	
				$event_start_time_val =null;
			}
		
		// End time
			if(!empty($ics_data['DTEND'])){
				$dt = new DateTime($ics_data['DTEND']);
				
				if($timezoneADJ && $WPtimezone){
					$dt->setTimeZone( new DateTimezone($WPtimezone) );
				}else{
					$dt->setTimeZone( new DateTimezone( 'UTC') );
				}

				$event_end_date_val = $dt->format('m/d/Y');
				$event_end_time_val = $dt->format('g:i:a');
			}else{ 
				$event_end_time_val =$event_start_time_val;	
				$event_end_date_val = $event_start_date_val;
			}								
		
		// description
			$event_description = (!empty($ics_data['DESCRIPTION']))? 
				html_entity_decode(convert_chars(addslashes($ics_data['DESCRIPTION'] ))): null;

		// Auto detect all day event
			if($event_start_time_val == '12:00:am' && $event_end_time_val =='12:00:am' && $alldayADJ)
				$ics_data['evcal_allday'] = 'yes';

		$ics_data['event_start_date'] = $event_start_date_val;
		$ics_data['event_start_time'] = $event_start_time_val;
		$ics_data['event_end_date'] = $event_end_date_val;
		$ics_data['event_end_time'] = $event_end_time_val;
		$ics_data['event_description'] = $event_description;

		// event name
			$eventName = (!empty($ics_data['SUMMARY']))?
				html_entity_decode($ics_data['SUMMARY']):	$event_start_date_val;
			$ics_data['event_name'] = $eventName;

		// Location 
			if(!empty($ics_data['LOCATION']))
				$ics_data['evcal_location_name'] = $ics_data['LOCATION'];

		// pluggable
			$ics_data = apply_filters('evoics_additional_data_validation', $ics_data);


		return $ics_data;
	}

// upload a file
	function upload_file($url, $event_name='', $file_type='image'){
    	if($file_type =='image'){
    		$preg = '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i';
    		$desc="Featured image for '$event_name'";
    	}else{
    		$preg = '/[^\?]+\.(ics)\b/i';
    		$desc="ICS File for importing events on ". date('Y-m-d', time());
    	}

		if(empty($url))	return false;

    	// Download file to temp location
	      $tmp = download_url( $url );

	      // Set variables for storage
	      $preg = preg_match( $preg, $url, $matches );

	      if(!$preg) return false;

	      $file_array['name'] = basename($matches[0]);
	      $file_array['tmp_name'] = $tmp;

	      // If error storing temporarily, unlink
	      if ( is_wp_error( $tmp ) ) {
	         @unlink($file_array['tmp_name']);
	         $file_array['tmp_name'] = '';
	      }

	      // do the validation and storage stuff
	      $post_id=0;
	      
	      $id = media_handle_sideload( $file_array, $post_id, $desc );
	      
	      // If error storing permanently, unlink
	      if ( is_wp_error($id) ) {
	         @unlink($file_array['tmp_name']);
	         return false;
	      }

	      $src = wp_get_attachment_url( $id );
	      return array(0=>$id,1=>$src);
    	
    }

// get list of already imported events
    function get_imported_event_ids(){

        $events = new WP_Query(array(
            'post_type'=>'ajde_events',
            'posts_per_page'=>-1,
            'meta_key'=>'_evoics_uid',
            'post_status' => array(
                'publish', 
                'pending', 
                'draft', 
                'auto-draft'
            ) 
        ));

        $imported = array();
        if(!$events->have_posts())  return false;

        while($events->have_posts()): $events->the_post();              
            $UID = get_post_meta($events->post->ID,'_evoics_uid',true);
            if(!empty( $UID))  $imported[$events->post->ID] = $UID;
        endwhile;
        wp_reset_postdata();

        return $imported;           
    }

// Supported variable names for event post meta values 
	function get_all_fields(){
		$fields =  array(
			'event_name',
			'evcal_location_name',
			'evcal_allday',
			'event_start_date',
			'event_start_time',
			'event_end_date',
			'event_end_time',
			'event_description',
			'UID',
			'imported_event_id'
		);
		
		// pluggable hook for additional fields
			$fields = apply_filters('evoics_additional_ics_fields', $fields);

		return $fields;
	}

// guidelines for ICS file
	function print_guidelines(){
		global $eventon, $evoics;
		
		ob_start();
		
		require_once($evoics->plugin_path.'/guide.php');
		
		$content = ob_get_clean();
		
		echo $eventon->output_eventon_pop_window( 
			array('content'=>$content, 'title'=>'How to use ICS Importer', 'type'=>'padded')
		);
		?>					
			<h3><?php _e('**ICS file guidelines','eventon')?></h3>
			<p><?php _e('Please read the below guide for proper .ICS file that is acceptable with this addon. Along with this addon, in the main addon file folder you should find a <b>sample.ics</b> file that can be used to help guide for creation of ics file.','eventon');?></p>
			<a type='submit' name='' id='eventon_ics_guide_trig' class=' ajde_popup_trig btn_secondary evo_admin_btn'><?php _e('Guide for ICS File','eventon');?></a>

		<?php
	}
}