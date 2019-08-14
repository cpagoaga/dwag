<?php
/*
Plugin Name: VC Hexagon Grid
Plugin URI: http://wp.themeofwp.com/elegantmegaaddons/hexagon-grid/
Description: Haxagon Grid Addon for Visual Composer WordPress plugin.
Author: ThemeofWP.com 
Author URI: http://www.themeofwp.com
Version: 1.0.3
Requires at least: 3.0
Tested up to: 4.6
License: You should have purchased a license from http://codecanyon.net/user/themeofwp/portfolio/
Support Forum : http://themeofwp.com/support/
*/


// don't load directly
if (!defined('ABSPATH')) die('-1');

require_once( dirname(__FILE__).'/lib/vc-template-manager.php' );

class VCHexagonStandaloneAddonClass {

	const slug = 'hexagong_grid';
	const base = 'hexagong_grid';
	
    function __construct() {
        // We safely integrate with VC with this hook
        add_action( 'init', array( $this, 'integrateWithVC' ) );
		
		// Load the plugin text domain.
        add_action( 'init', array( $this, 'load_text_domain' ) );
		
        // Use this when creating a shortcode addon
        add_action( 'vc_before_init', array( $this, 'hexagong_grid_shortcode_vc' ) );
		add_action( 'vc_before_init', array( $this, 'hexagong_grid_content_shortcode_vc' ) );
		
		add_shortcode( 'hexagong_grid', array( $this, 'vc_hexagong_grid_shortcode' ));
		add_shortcode( 'hexagong_grid_content', array( $this, 'hexagong_grid_content_shortcode' ));
		
        // Register CSS and JS
        add_action( 'wp_enqueue_scripts', array( $this, 'loadCssAndJs' ) );
    }
	
	// Load languages
	public function load_text_domain() {
		load_plugin_textdomain("vc_themeofwp_addon", false, '/' . basename(dirname(__FILE__)) . '/languages/'); // load plugin
	}
	
	
	// Parent Element
	public function hexagong_grid_shortcode_vc() {
		vc_map( 
			array(
				"icon" 					  => 'icon-vc-hexagongrid-page',
				'name'                    => __( 'Hexagon Grid' , 'vc_themeofwp_addon' ),
				'base'                    => 'hexagong_grid',
				'description'             => __( 'Add hexagong grid gallery to your page.', 'vc_themeofwp_addon' ),
				"icon"                    => plugins_url('assets/vc_icon.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
				'as_parent'               => array('only' => 'hexagong_grid_content'), // Use only|except attributes to limit child shortcodes (separate multiple values with comma)
				'content_element'         => true,
				'show_settings_on_create' => true,
				"js_view" 				  => 'VcColumnView',
				"category" 				  => __('Content', 'vc_themeofwp_addon'),
				'params'          		  => array(					
					array(
						"type" => "textfield",
						"heading" => __( "Extra Class Name", "vc_themeofwp_addon" ),
						"param_name" => "el_class",
						"description" => __( "Extra class to be customized via CSS", "vc_themeofwp_addon" )
					),
					
					array(
						'type' => 'css_editor',
						'heading' => __( 'Custom Design Options', 'vc_themeofwp_addon' ),
						'param_name' => 'css',
						'group' => __( 'Design options', 'vc_themeofwp_addon' ),
					),
				),
			) 
		);
	}
	

	// Nested Element
	public function hexagong_grid_content_shortcode_vc() {
		vc_map( 
			array(
				"icon"            => 'icon-vc-hexagongrid-page',
				'name'            => __('Grid Item', 'vc_themeofwp_addon'),
				'base'            => 'hexagong_grid_content',
				'description'     => __( 'Add hexagong grid items to your gallery.', 'vc_themeofwp_addon' ),
				"icon"            => plugins_url('assets/vc_icon.png', __FILE__), // or css class name which you can reffer in your css file later. Example: "vc_extend_my_class"
				"category"        => __('Content', 'vc_themeofwp_addon'),
				'content_element' => true,
				'as_child'        => array('only' => 'hexagong_grid'), // Use only|except attributes to limit parent (separate multiple values with comma)
				'params'          => array(
				
					array( 
							"type" => "colorpicker",
							"heading" => __( "Default Background Color", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_grid_bg",
							'admin_label' => true,
							"description" => __( "Please choose the grid background color.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "colorpicker",
							"heading" => __( "Hover Background Color", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_grid_hover_bg",
							'admin_label' => true,
							"description" => __( "Please choose the grid background color.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "attach_image",
							"heading" => __( "Thumbnail Image", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_img",
							'admin_label' => true,
							"description" => __( "Please choose your image.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield",
							"heading" => __( "Default Title", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_default_title",
							'admin_label' => true,
							'group' => __( "Title", "vc_themeofwp_addon" ),
							"description" => __( "Please enter the default title.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield",
							"heading" => __( "Hover Title", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_title",
							'admin_label' => true,
							'group' => __( "Title", "vc_themeofwp_addon" ),
							"description" => __( "Please enter the hover title.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield",
							"heading" => __( "Title Link URL", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_url",
							'admin_label' => true,
							'group' => __( "Title", "vc_themeofwp_addon" ),
							"description" => __( "Please enter the link URL.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "textarea_html",
							"heading" => __( "Content", "vc_themeofwp_addon" ),
							"param_name" => "content",
							'admin_label' => true,
							'group' => __( "Content", "vc_themeofwp_addon" ),
							"description" => __( "Please enter the content.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "colorpicker",
							"heading" => __( "Content Color", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_content_color",
							'admin_label' => true,
							'group' => __( "Content", "vc_themeofwp_addon" ),
							"description" => __( "Please choose the title color.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "textfield",
							"heading" => __( "Content Font Size", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_content_fontsize",
							'admin_label' => true,
							'group' => __( "Content", "vc_themeofwp_addon" ),
							"description" => __( "Please enter the title font size.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "Facebook", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_facebook",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use Facebook share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "Twitter", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_twitter",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use Twitter share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array(
							"type" => "dropdown",
							"heading" => __( "Instagram", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_showinstagram",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							"value" => array(
											__( "No", "vc_themeofwp_addon" ) => "no", 
											__( "Yes", "vc_themeofwp_addon" ) => "instagram", 
										),
							"description" => __( "Do you want to use view on Instagram social icon?", "vc_themeofwp_addon" ),
							"admin_label" => false
					),
					
					array( 
							"type" => "textfield", 
							"heading" => __( "Instagram Link", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_instagram",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => false,
							"dependency" => Array( 
											'element' => "hexagongrid_showinstagram",
											'value' => array( 'instagram' ),
											),
							"description" => __( "Please enter the content url on Instagram. Ex:https://www.instagram.com/p/BEPQ4Cptvfm/?taken-by=themeofwp", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "LinkedIn", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_linkedin",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use LinkedIn share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "Pinterest", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_pinterest",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use Pinterest share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "Google+", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_google",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use Google+ share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "Digg", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_digg",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use Digg share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "Reddit", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_reddit",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use Reddit share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "Stumbleupon", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_stumbleupon",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use Stumbleupon share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "Tumblr", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_tumblr",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use Tumblr share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array( 
							"type" => "checkbox",
							"heading" => __( "E-mail", "vc_themeofwp_addon" ),
							"param_name" => "hexagongrid_email",
							'group' => __( "Social", "vc_themeofwp_addon" ),
							'admin_label' => true,
							"description" => __( "Do you want to use E-mail share?.", "vc_themeofwp_addon" ),
							"value" => ""
					),
					
					array(
					'type' => 'checkbox',
					'heading' => __( 'Use theme default font family?', 'vc_themeofwp_addon' ),
					'param_name' => 'use_theme_fonts',
					'value' => array( __( 'Yes', 'js_composer' ) => 'yes' ),
					'group' => __( 'Custom fonts', 'vc_themeofwp_addon' ),
					'description' => __( 'Use font family from the theme.', 'vc_themeofwp_addon' ),
					
					),
				
					array(
					'type' => 'google_fonts',
					'param_name' => 'google_fonts',
					'value' => '',
					'group' => __( 'Custom fonts', 'vc_themeofwp_addon' ),
					
							"dependency" => Array( 
									'element' => "use_theme_fonts",
									'value_not_equal_to' => array( 'yes' ),
								),
						
							'settings' => array(
								'fields' => array(
									'font_family_description' => __( 'Select font family.', 'vc_themeofwp_addon' ),
									'font_style_description' => __( 'Select font styling.', 'vc_themeofwp_addon' ),
								),
								
								
						),
					),
					
					array(
						'type' => 'checkbox',
						'heading' => __( 'Custom font for the content?', 'vc_themeofwp_addon' ),
						'param_name' => 'use_custom_for_content',
						'value' => "",
						'group' => __( 'Custom fonts', 'js_composer' ),
						"dependency" => Array( 
									'element' => "use_theme_fonts",
									'value_not_equal_to' => array( 'yes' ),
								),
						'description' => __( 'Use this custom font family for the content.', 'vc_themeofwp_addon' ),
					),
						
					array(
					'type' => 'font_container',
					'param_name' => 'font_container',
					'value' => 'tag:h1',
					'group' => __( 'Typography', 'vc_themeofwp_addon' ),
						
						'settings' => array(
							
							'fields' => array(
								'tag' => 'h2', // default value h2
								'text_align',
								'font_size',
								'line_height',
								'color',
								'font_style',
								'tag_description' => __( 'Select element tag.', 'vc_themeofwp_addon' ),
								'text_align_description' => __( 'Select text alignment.', 'vc_themeofwp_addon' ),
								'font_size_description' => __( 'Enter font size.', 'vc_themeofwp_addon' ),
								'line_height_description' => __( 'Enter line height.', 'vc_themeofwp_addon' ),
								'color_description' => __( 'Select heading color.', 'vc_themeofwp_addon' ),
								'font_style_description' => __('Select letter style.', 'vc_themeofwp_addon'),
							),
						),
					),
					
					array(
						"type" => "textfield",
						"heading" => __( "Extra Class Name", "vc_themeofwp_addon" ),
						"param_name" => "el_class",
						"description" => __( "Extra class to be customized via CSS", "vc_themeofwp_addon" )
					),
				),
			) 
		);
	}
	
	
	
	
	/**
	 * Grid Gallery Shortcode
	 */
	public function vc_hexagong_grid_shortcode( $atts, $content = null ) {
		$output = $el_class = $css = '';
		
		extract(shortcode_atts( array(
			'el_class' => '',
			'css' => '',
		), $atts ) );
		
		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, '' ), self::slug, $atts );
		
		$output = '<ul id="vcmp_hexagon_grid" class="vcmp_clr '.esc_attr( $css_class ).' '.esc_attr( $el_class ).'">'. do_shortcode($content).'</ul>';
		return $output;
	}
	
	
	public function nl2p($str) {
		$arr=explode("\n",$str);
		$out='';

		for($i=0;$i<count($arr);$i++) {
			if(strlen(trim($arr[$i]))>0)
				$out.=''.trim($arr[$i]).'';
		}
		return $out;
	}
	

	/**
	 * Grid Gallery Content Items Shortcode
	 */
	public function hexagong_grid_content_shortcode( $atts, $content = null ) {
	
		$output = $el_class = $use_theme_fonts = $google_fonts = $use_custom_for_content = $font_container = $hexagongrid_img = $hexagongrid_title = $hexagongrid_default_title = $hexagongrid_url = $hexagongrid_content_fontsize = $hexagongrid_content_color = $hexagongrid_grid_bg = $hexagongrid_grid_hover_bg = $hexagongrid_facebook = $hexagongrid_twitter = $hexagongrid_instagram = $hexagongrid_linkedin = $hexagongrid_pinterest = $hexagongrid_google = $hexagongrid_digg = $hexagongrid_reddit = $hexagongrid_stumbleupon = $hexagongrid_tumblr = $hexagongrid_tumblr = $hexagongrid_email = '';

		extract( 
			shortcode_atts( 
				array(
					'el_class' => '',
					'use_theme_fonts' => '',
					'google_fonts' => '',
					'use_custom_for_content' => '',
					'font_container' => '',
					
					'hexagongrid_img' => '',
					'hexagongrid_title' => '',
					'hexagongrid_default_title' => '',
					'hexagongrid_url' => '',
					'hexagongrid_content_fontsize' => '',
					'hexagongrid_content_color' => '',
					'hexagongrid_grid_bg' => '',
					'hexagongrid_grid_hover_bg' => '',
					
					'hexagongrid_facebook' => '',
					'hexagongrid_twitter' => '',
					'hexagongrid_instagram' => '',
					'hexagongrid_linkedin' => '',
					'hexagongrid_pinterest' => '',
					'hexagongrid_google' => '',
					'hexagongrid_digg' => '',
					'hexagongrid_reddit' => '',
					'hexagongrid_stumbleupon' => '',
					'hexagongrid_tumblr' => '',
					'hexagongrid_email' => '',
				), $atts 
			) 
		);
		
		$content = $this->nl2p($content);
		
		static $i = 0;
		static $it = 0;

		
		$hexagongrid_img = wp_get_attachment_image_src(intval($hexagongrid_img), 'full');
		$hexagongrid_img = $hexagongrid_img[0];
		
		$post_title=urlencode(get_the_title());
		$post_link=get_permalink();
		$post_excerpt=strip_tags(get_the_excerpt());
		$post_thumb=wp_get_attachment_url(get_post_thumbnail_id());
		
		$font_container_obj = new Vc_Font_Container();
		$font_container_data = $font_container_obj->_vc_font_container_parse_attributes( 
			array(
		        'tag',
		        'text_align',
		        'font_size',
		        'line_height',
		        'color',
		        'font_style',
		        'font_style_italic',
		        'font_style_bold'
			), 
			$font_container 
		);
		
		if ( ! empty( $font_container_data ) && isset( $font_container_data['values'] ) ) {
			foreach ( $font_container_data['values'] as $key => $value ) {
				if ( $key != 'tag' && strlen( $value ) > 0 ) {
					if ( preg_match( '/description/', $key ) ) {
						continue;
					}
					if ( $key == 'font_size' || $key == 'line_height' ) {
						$value = preg_replace( '/\s+/', '', $value );
					}
					if ( $key == 'font_size' ) {
						$pattern = '/^(\d*(?:\.\d+)?)\s*(px|\%|in|cm|mm|em|rem|ex|pt|pc|vw|vh|vmin|vmax)?$/';
						// allowed metrics: http://www.w3schools.com/cssref/css_units.asp
						$regexr = preg_match( $pattern, $value, $matches );
						$value = isset( $matches[1] ) ? (float) $matches[1] : (float) $value;
						$unit = isset( $matches[2] ) ? $matches[2] : 'px';
						$value = $value . $unit;
					}
					if ( strlen( $value ) > 0 ) {
						if(array_key_exists($key, $font_container_data['fields'])){
							switch ($key) {
								case 'font_style_italic':
										if($value == 1){
											$styles[$key] = 'font-style: italic';
										}
									break;

								case 'font_style_bold':
										if($value == 1){
											$styles[$key] = 'font-weight: bold';
										}
									break;
								
								default:
										$styles[$key] = str_replace( '_', '-', $key ) . ': ' . $value;
									break;
							}
						}
					}
				}
			}
		}
		
		$google_fonts_obj = new Vc_Google_Fonts();
		$google_fonts_data = $google_fonts_obj->_vc_google_fonts_parse_attributes( 
			array(
		        'font_family',
		        'font_style'
			), 
			$google_fonts
		);

		$settings = get_option( 'wpb_js_google_fonts_subsets' );
		$subsets = '';
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			$subsets = '&subset=' . implode( ',', $settings );
		}
		
		$google_fonts_family = explode( ':', $google_fonts_data['values']['font_family'] );
		$styles[] = 'font-family:' . $google_fonts_family[0];
		$google_fonts_styles = explode( ':', $google_fonts_data['values']['font_style'] );
		@$styles[] = 'font-weight:' . $google_fonts_styles[1];
		@$styles[] = 'font-style:' . $google_fonts_styles[2];
		$styles[] = 'background-color:' . $hexagongrid_grid_hover_bg;
		
		$style = '';
		if ( ! empty( $styles ) ) {
			$style = 'style="' . esc_attr( implode( ';', $styles ) ) . '"';
		}
		
		if ( ! empty( $google_fonts ) ) {
			wp_enqueue_style( 'vc_google_fonts_' . vc_build_safe_css_class( $google_fonts_data['values']['font_family'] ), '//fonts.googleapis.com/css?family=' . $google_fonts_data['values']['font_family'] . $subsets );
		}
		
		
		$output .= ' <li class=" '.esc_attr( $el_class ).'">
						<div style="background: '.$hexagongrid_grid_bg.' url('.$hexagongrid_img.') no-repeat scroll 0 50% / cover;">
							<span class="defaulthexcontent">'.$hexagongrid_default_title.'</span>
							
							<' . $font_container_data['values']['tag'] . ' class="hexagon-title" ' . $style . '>
								<a href="'.$hexagongrid_url.'" style="color:' . $font_container_data['values']['color'] . '; font-size:' . $font_container_data['values']['font_size'] . '">
									'.$hexagongrid_title.'
								</a>
							</' . $font_container_data['values']['tag'] . '>
							
							<p style="background-color:'.$hexagongrid_grid_hover_bg.'; color:'.$hexagongrid_content_color.'; font-size:'.$hexagongrid_content_fontsize.'; ';
							
							if ( $use_custom_for_content== 'true' ) {
										$output .= ' font-family:'.$google_fonts_family[0].'; font-weight:'.$google_fonts_styles[1].'; font-style:'.$google_fonts_styles[2].'';
							}
							
		$output .= ' ">';
		
		$output .= '<span class="hoverhexcontent">'. do_shortcode($content) .'</span>
							
							<span class="griddershare">';
								
		if ( !$hexagongrid_facebook == '' ) {			
		$output .= '<a title="'.__( "Share on Facebook", "vc_themeofwp_addon" ).'" href="https://www.facebook.com/sharer.php?display=popup&amp;u='. $hexagongrid_img .'&amp;t='.$hexagongrid_title.'" class="share-btn fa fa-facebook" onclick="javascript:window.open(this.href,
											  \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';								  
		};
		
		if ( !$hexagongrid_twitter == '' ) {									  
		$output .= '<a title="'.__( "Share on Twitter", "vc_themeofwp_addon" ).'" href="https://twitter.com/share?url='.$post_link.'&amp;text='.$hexagongrid_title.'+'. $hexagongrid_img .'" class="share-btn fa fa-twitter" onclick="javascript:window.open(this.href,
											  \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';
		};
		
		if ( !$hexagongrid_instagram == '' ) {									  
		$output .= '<a title="'.__( "View on Instagram", "vc_themeofwp_addon" ).'" href="'.$hexagongrid_instagram.'" class="share-btn fa fa-instagram" onclick="javascript:window.open(this.href,
											  \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=800\');return false;"></a>';
		};
		
		if ( !$hexagongrid_linkedin == '' ) { 
		$output .= '<a title="'.__( "Share on LinkedIn", "vc_themeofwp_addon" ).'" href="http://www.linkedin.com/shareArticle?mini=true&amp;url='.$post_link.'" class="share-btn fa fa-linkedin" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';
		};
		
		if ( !$hexagongrid_pinterest == '' ) { 
		$output .= '<a title="'.__( "Share on Pinterest", "vc_themeofwp_addon" ).'" href="https://pinterest.com/pin/create/button/?url='.$post_link.'&amp;media='.$hexagongrid_img.'&amp;description='.$hexagongrid_title.'" class="share-btn fa fa-pinterest" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';
		};
		
		if ( !$hexagongrid_google == '' ) {						
		$output .= '<a title="'.__( "Share on Google", "vc_themeofwp_addon" ).'" href="https://plus.google.com/share?url='.$post_link.'" class="share-btn fa fa-google-plus" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';
		};
					
		if ( !$hexagongrid_digg == '' ) {
		$output .= '<a title="'.__( "Share on Digg", "vc_themeofwp_addon" ).'" href="http://www.digg.com/submit?url='.$post_link.'" class="share-btn fa fa-digg" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';
		};
						
		if ( !$hexagongrid_reddit == '' ) {
		$output .= '<a title="'.__( "Share on Reddit", "vc_themeofwp_addon" ).'" href="http://reddit.com/submit?url='.$post_link.'" class="share-btn fa fa-reddit" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';
		};
		
		if ( !$hexagongrid_stumbleupon == '' ) { 
		$output .= '<a title="'.__( "Share on Stumbleupon", "vc_themeofwp_addon" ).'" href="http://www.stumbleupon.com/submit?url='.$post_link.'&amp;title='.$hexagongrid_title.'" class="share-btn fa fa-stumbleupon" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';
		};
		
		if ( !$hexagongrid_tumblr == '' ) { 
		$output .= '<a title="'.__( "Share on Tumblr", "vc_themeofwp_addon" ).'" href="http://www.tumblr.com/share/link?url='.$post_link.'&amp;name='.$hexagongrid_title.'" class="share-btn fa fa-tumblr" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';
		};
		
		if ( !$hexagongrid_email == '' ) { 
		$output .= '<a title="'.__( "Sent by E-mail", "vc_themeofwp_addon" ).'" href="mailto:?subject='.$hexagongrid_title.'&amp;body='.$content.' '. $hexagongrid_custom_video .''.$hexagongrid_img.''. $hexagongrid_youtube_video .''. $hexagongrid_vimeo_video .''. $hexagongrid_text_only .' '.$post_link.'" class="share-btn fa fa-envelope" onclick="javascript:window.open(this.href,\'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600\');return false;"></a>';
		};
		
		$output .= '		</span>
						</p>
					</div>
				</li>
					
					';
		
		return $output;
		
	}
	
 
    public function integrateWithVC() {
        // Check if Visual Composer is installed
        if ( ! defined( 'WPB_VC_VERSION' ) ) {
            // Display notice that Visual Compser is required
            add_action('admin_notices', array( $this, 'showVcVersionNotice' ));
            return;
        }
    }
    
	
    /*
    Load plugin css and javascript files which you may need on front end of your site
    */
    public function loadCssAndJs() {
      wp_register_style( 'vc_hexagon_style', plugins_url('assets/vc_hexagongrid.css', __FILE__) );
      wp_register_style( 'vc_hexagon_fonts', plugins_url('assets/vc_fonts.css', __FILE__) );
      wp_enqueue_style( 'vc_hexagon_style' );
      wp_enqueue_style( 'vc_hexagon_fonts' );

      // If you need any javascript files on front end, here is how you can load them.
      //wp_enqueue_script( 'vc_hexagon_js', plugins_url('assets/vc_hexagon.js', __FILE__), array('jquery') );
    }

    /*
    Show notice if your plugin is activated but Visual Composer is not
    */
    public function showVcVersionNotice() {
        $plugin_data = get_plugin_data(__FILE__);
        echo '
        <div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/1UNVpys" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'vc_themeofwp_addon'), $plugin_data['Name']).'</p>
        </div>';
    }
}

	// A must for container functionality, replace Wbc_Item with your base name from mapping for parent container
	if(class_exists('WPBakeryShortCodesContainer')){
		class WPBakeryShortCode_hexagong_grid extends WPBakeryShortCodesContainer {

		}
	}

	// Replace Wbc_Inner_Item with your base name from mapping for nested element
	if(class_exists('WPBakeryShortCode')){
		class WPBakeryShortCode_hexagong_grid_content extends WPBakeryShortCode {

		}
	}
// Finally initialize code
new VCHexagonStandaloneAddonClass();