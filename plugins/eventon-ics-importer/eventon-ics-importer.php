<?php
/*
 Plugin Name: EventON - ICS importer
 Plugin URI: http://plugins.ashanjay.com/event-calendar/
 Description: Import events from a .ICS file into eventON system
 Author: Ashan Jay
 Version: 1.0.2
 Author URI: http://www.ashanjay.com/
 */

class EVOICS_import{	
	
	public $version='1.0.2';
	public $eventon_version = '2.5.5';
	public $name = 'ICS Importer';
	
	public $addon_data = array();
	public $slug, $plugin_slug , $plugin_url , $plugin_path ;
	
	// Construct
	public function __construct(){
		$this->super_init();
		add_action('plugins_loaded', array($this, 'plugin_init'));
	}
	
	function plugin_init(){
		// check if eventon exists with addon class
		if( !isset($GLOBALS['eventon']) || !class_exists('evo_addons') ){
			add_action('admin_notices', array($this, 'notice'));
			return false;			
		}			
		
		$this->addon = new evo_addons($this->addon_data);

		if($this->addon->evo_version_check()){
			add_action( 'init', array( $this, 'init' ), 0 );
		}
	}
	function notice(){
		?><div class="message error"><p><?php printf(__('EventON %s is NOT active! - '), $this->name); 
	        	echo "You do not have EventON main plugin, which is REQUIRED.";?></p></div><?php
	}
	
	// SUPER init
		function super_init(){
			// PLUGIN SLUGS			
			$this->addon_data['plugin_url'] = path_join(WP_PLUGIN_URL, basename(dirname(__FILE__)));
			$this->addon_data['plugin_slug'] = plugin_basename(__FILE__);
			list ($t1, $t2) = explode('/', $this->addon_data['plugin_slug'] );
	        $this->addon_data['slug'] = $t1;
	        $this->addon_data['plugin_path'] = dirname( __FILE__ );
	        $this->addon_data['evo_version'] = '2.2.12';
	        $this->addon_data['version'] = $this->version;
	        $this->addon_data['name'] = 'ActionUser';

	        $this->plugin_url = $this->addon_data['plugin_url'];
	        $this->plugin_slug = $this->addon_data['plugin_slug'];
	        $this->slug = $this->addon_data['slug'];
	        $this->plugin_path = $this->addon_data['plugin_path'];
	        $this->assets_path = str_replace(array('http:','https:'), '',$this->addon_data['plugin_url']).'/assets/';
		}

	// INITIATE action user
		function init(){

	      	// Deactivation
			register_deactivation_hook( __FILE__, array($this,'deactivate'));

			include_once('includes/class-functions.php');
			
			if ( is_admin() ){
				include_once('includes/class-admin-init.php');
				$this->admin = new EVOICS_admin();		
			}

			if( defined('DOING_AJAX') ){
				include_once('includes/class-ajax.php');
			}

			include_once($this->plugin_path.'/includes/class-cron.php');
			$this->cron = new evoics_cron();
			// Cron jobs
			$this->cron->schedule_jobs();
		}

	// Deactivate addon
		function deactivate(){	$this->addon->remove_addon();	}		
}

// Initiate this addon within the plugin
$GLOBALS['evoics'] = new EVOICS_import();
?>