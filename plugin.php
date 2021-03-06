<?php

/**
 *
 *
 * @version 1.1.7
 * @package Main
 * @author e-plugin.com
 */

/*
  Plugin Name: Fitness Trainer
  Plugin URI: http://e-plugin.com/
  Description: Build Paid fitness site using Wordpress.No programming knowledge required.
  Author: e-plugin
  Author URI: http://e-plugin.com/
  Version: 1.1.7
  Text Domain: epfitness
  License: GPLv3

*/

// Exit if accessed directly
  if (!defined('ABSPATH')) {
  	exit;
  }

  if (!class_exists('wp_ep_fitness')) {  	
		
			final class wp_ep_fitness {
			
			private static $instance;
			
				/**
				 * The Plug-in version.
				 *
				 * @var string
				 */
				public $version = "1.1.7";
				
				/**
				 * The minimal required version of WordPress for this plug-in to function correctly.
				 *
				 * @var string
				 */
				public $wp_version = "3.5";
				
				public static function instance() {
					if (!isset(self::$instance) && !(self::$instance instanceof wp_ep_fitness)) {
						self::$instance = new wp_ep_fitness;
					}
					return self::$instance;
				}
				
				/**
				 * Construct and start the other plug-in functionality
				 */
				public function __construct() {
					
						//
						// 1. Plug-in requirements
						//
					if (!$this->check_requirements()) {
						return;
					}
					
						//
						// 2. Declare constants and load dependencies
						//
					$this->define_constants();
					$this->load_dependencies();
					
						//
						// 3. Activation Hooks
						//
					register_activation_hook(__FILE__, array(&$this, 'activate'));
					register_deactivation_hook(__FILE__, array(&$this, 'deactivate'));
					register_uninstall_hook(__FILE__, 'wp_ep_fitness::uninstall');
					
						//
						// 4. Load Widget
						//
					add_action('widgets_init', array(&$this, 'register_widget'));
					
						//
						// 5. i18n
						//
					add_action('init', array(&$this, 'i18n'));
						//
						// 6. Actions
						//
					
					add_action('wp_ajax_ep_fitness_registration_submit', array(&$this, 'ep_fitness_registration_submit'));
					add_action('wp_ajax_nopriv_ep_fitness_registration_submit', array(&$this, 'ep_fitness_registration_submit'));
					add_action('wp_ajax_ep_fitness_user_exist_check', array(&$this, 'ep_fitness_user_exist_check'));
					add_action('wp_ajax_nopriv_ep_fitness_user_exist_check', array(&$this, 'ep_fitness_user_exist_check'));
					add_action('wp_ajax_ep_fitness_check_coupon', array(&$this, 'ep_fitness_check_coupon'));
					add_action('wp_ajax_nopriv_ep_fitness_check_coupon', array(&$this, 'ep_fitness_check_coupon'));					
					add_action('wp_ajax_ep_fitness_check_package_amount', array(&$this, 'ep_fitness_check_package_amount'));
					add_action('wp_ajax_nopriv_ep_fitness_check_package_amount', array(&$this, 'ep_fitness_check_package_amount'));
					add_action('wp_ajax_ep_fitness_update_profile_pic', array(&$this, 'ep_fitness_update_profile_pic'));
					add_action('wp_ajax_nopriv_ep_fitness_update_profile_pic', array(&$this, 'ep_fitness_update_profile_pic'));
					add_action('wp_ajax_ep_fitness_update_profile_setting', array(&$this, 'ep_fitness_update_profile_setting'));
					add_action('wp_ajax_nopriv_ep_fitness_update_profile_setting', array(&$this, 'ep_fitness_update_profile_setting'));
					//add_action('wp_ajax_ep_fitness_update_wp_post', array(&$this, 'ep_fitness_update_wp_post'));
					//add_action('wp_ajax_nopriv_ep_fitness_update_wp_post', array(&$this, 'ep_fitness_update_wp_post'));
					add_action('wp_ajax_ep_fitness_save_wp_post', array(&$this, 'ep_fitness_save_wp_post'));
					add_action('wp_ajax_nopriv_ep_fitness_save_wp_post', array(&$this, 'ep_fitness_save_wp_post'));
					add_action('wp_ajax_ep_fitness_save_listing', array(&$this, 'ep_fitness_save_listing'));
					add_action('wp_ajax_nopriv_ep_fitness_save_listing', array(&$this, 'ep_fitness_save_listing'));	
					add_action('wp_ajax_ep_fitness_update_setting_fb', array(&$this, 'ep_fitness_update_setting_fb'));
					add_action('wp_ajax_nopriv_ep_fitness_update_setting_fb', array(&$this, 'ep_fitness_update_setting_fb'));
					add_action('wp_ajax_ep_fitness_update_setting_hide', array(&$this, 'ep_fitness_update_setting_hide'));
					add_action('wp_ajax_nopriv_ep_fitness_update_setting_hide', array(&$this, 'ep_fitness_update_setting_hide'));
					add_action('wp_ajax_ep_fitness_update_setting_password', array(&$this, 'ep_fitness_update_setting_password'));
					add_action('wp_ajax_nopriv_ep_fitness_update_setting_password', array(&$this, 'ep_fitness_update_setting_password'));					
					add_action('wp_ajax_ep_fitness_check_login', array(&$this, 'ep_fitness_check_login'));
					add_action('wp_ajax_nopriv_ep_fitness_check_login', array(&$this, 'ep_fitness_check_login'));
					add_action('wp_ajax_ep_fitness_forget_password', array(&$this, 'ep_fitness_forget_password'));
					add_action('wp_ajax_nopriv_ep_fitness_forget_password', array(&$this, 'ep_fitness_forget_password'));
					add_action('wp_ajax_ep_fitness_cancel_stripe', array(&$this, 'ep_fitness_cancel_stripe'));
					add_action('wp_ajax_nopriv_ep_fitness_cancel_stripe', array(&$this, 'ep_fitness_cancel_stripe'));
					add_action('wp_ajax_ep_fitness_cancel_paypal', array(&$this, 'ep_fitness_cancel_paypal'));
					add_action('wp_ajax_nopriv_ep_fitness_cancel_paypal', array(&$this, 'ep_fitness_cancel_paypal'));
					add_action('wp_ajax_ep_fitness_profile_stripe_upgrade', array(&$this, 'ep_fitness_profile_stripe_upgrade'));
					add_action('wp_ajax_nopriv_ep_fitness_profile_stripe_upgrade', array(&$this, 'ep_fitness_profile_stripe_upgrade'));						
					add_action('wp_ajax_ep_fitness_profile_stripe_add_balance', array(&$this, 'ep_fitness_profile_stripe_add_balance'));
					add_action('wp_ajax_nopriv_ep_fitness_profile_stripe_add_balance', array(&$this, 'ep_fitness_profile_stripe_add_balance'));	
					
					
					add_action('wp_ajax_ep_fitness_paypal_notify_url', array(&$this, 'ep_fitness_paypal_notify_url'));
					add_action('wp_ajax_nopriv_ep_fitness_paypal_notify_url', array(&$this, 'ep_fitness_paypal_notify_url'));					
					
					add_action('wp_ajax_ep_fitness_cron_job', array(&$this, 'ep_fitness_cron_job'));
					add_action('wp_ajax_nopriv_ep_fitness_cron_job', array(&$this, 'ep_fitness_cron_job'));	
							
					add_action('wp_ajax_ep_fitness_cpt_change', array(&$this, 'ep_fitness_cpt_change'));
					add_action('wp_ajax_nopriv_ep_fitness_cpt_change', array(&$this, 'ep_fitness_cpt_change'));	
				
					add_action('wp_ajax_ep_fitness_save_record', array(&$this, 'ep_fitness_save_record'));
					add_action('wp_ajax_nopriv_ep_fitness_save_record', array(&$this, 'ep_fitness_save_record'));
					
					add_action('wp_ajax_ep_fitness_update_record', array(&$this, 'ep_fitness_update_record'));
					add_action('wp_ajax_nopriv_ep_fitness_update_record', array(&$this, 'ep_fitness_update_record'));	
					
					add_action('wp_ajax_iv_training_done', array(&$this, 'iv_training_done'));
					add_action('wp_ajax_nopriv_iv_training_done', array(&$this, 'iv_training_done'));	
						
									
					
					add_action('plugins_loaded', array(&$this, 'start'));
					add_action('add_meta_boxes', array(&$this, 'prfx_custom_meta_iv_listing'));
					
					//add_action('save_post', array(&$this, 'iv_listing_meta_save'));
					add_action('pre_post_update', array(&$this, 'iv_listing_meta_save'));
					
									
					add_action( 'init', array(&$this, 'ep_fitness_paypal_form_submit') );
					add_action( 'init', array(&$this, 'ep_fitness_stripe_form_submit') );					
					add_action('wp_login', array(&$this, 'check_expiry_date'));					
					add_action('pre_get_posts',array(&$this, 'iv_restrict_media_library') );
					
					add_action('init', array(&$this, 'remove_admin_bar') );	
					
						// For Visual Composer 
					add_action('vc_before_init',array(&$this, 'dir_vc_pricing_table') );
					add_action('vc_before_init',array(&$this, 'dir_vc_signup') );
					add_action('vc_before_init',array(&$this, 'dir_vc_user_login') );
					add_action('vc_before_init',array(&$this, 'dir_vc_my_account') );
					add_action('vc_before_init',array(&$this, 'dir_vc_public_profile') );
					add_action('vc_before_init',array(&$this, 'dir_vc_user_directory') );
					
					
										
						
						// 7. Shortcode						
					add_shortcode('ep_fitness_display', array(&$this, 'ep_fitness_display_func'));	
					add_shortcode('iv_archive_directories', array(&$this, 'iv_archive_directories_func'));
					add_shortcode('ep_fitness_price_table', array(&$this, 'ep_fitness_price_table_func'));
					add_shortcode('ep_fitness_registration_form', array(&$this, 'ep_fitness_registration_form_func'));
					add_shortcode('ep_fitness_payment_form', array(&$this, 'ep_fitness_payment_form_func'));
					add_shortcode('ep_fitness_form_wizard', array(&$this, 'ep_fitness_form_wizard_func'));
					add_shortcode('ep_fitness_profile_template', array(&$this, 'ep_fitness_profile_template_func'));
					add_shortcode('ep_fitness_profile_public', array(&$this, 'ep_fitness_profile_public_func'));
					//add_shortcode('ep_fitness_stripe_form', array(&$this, 'ep_fitness_stripe_form_func'));
					add_shortcode('ep_fitness_login', array(&$this, 'ep_fitness_login_func'));
					add_shortcode('ep_fitness_user_directory', array(&$this, 'ep_fitness_user_directory_func'));
					
					
					
									
					add_shortcode('ep_fitness_reminder_email_cron', array(&$this, 'ep_fitness_reminder_email_cron_func'));
					
						// 8. Filter
					
				
					
					add_filter('user_contactmethods', array(&$this, 'modify_contact_methods') );					
					add_action( 'wp_loaded', array(&$this, 'ep_fitness_woocommerce_form_submit') );
					
								
					//---- COMMENT FILTERS ----//	
				
													
					//add_filter('request', array(&$this, 'post_type_tags_fix'));
					
					add_action( 'init', array(&$this, 'ep_post_type') );
					add_action( 'init', array(&$this, 'ep_create_my_taxonomy_category'));
					add_action( 'init', array(&$this, 'ep_create_my_taxonomy_tags'));	
					add_action( 'init', array(&$this, 'iv_physicalrecord_post_type') ); 
					
					add_filter( 'template_include', array(&$this, 'include_template_function'), 9, 2  );
					
					
				}
				
				
				/**
				 * Define constants needed across the plug-in.
				 */
				private function define_constants() {
					if (!defined('wp_ep_fitness_BASENAME')) define('wp_ep_fitness_BASENAME', plugin_basename(__FILE__));
					//define('wp_ep_fitness_BASENAME', plugin_basename(__FILE__));
					if (!defined('wp_ep_fitness_DIR')) define('wp_ep_fitness_DIR', dirname(__FILE__));
					if (!defined('wp_ep_fitness_FOLDER'))define('wp_ep_fitness_FOLDER', plugin_basename(dirname(__FILE__)));
					if (!defined('wp_ep_fitness_ABSPATH'))define('wp_ep_fitness_ABSPATH', trailingslashit(str_replace("\\", "/", WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)))));
					if (!defined('wp_ep_fitness_URLPATH'))define('wp_ep_fitness_URLPATH', trailingslashit(WP_PLUGIN_URL . '/' . plugin_basename(dirname(__FILE__))));
					if (!defined('wp_ep_fitness_ADMINPATH'))define('wp_ep_fitness_ADMINPATH', get_admin_url());
					
					
					$filename = get_template_directory()."/fitnesstrainer/";
					if (!file_exists($filename)) {					
						if (!defined('wp_ep_fitness_template'))define( 'wp_ep_fitness_template', wp_ep_fitness_ABSPATH.'template/' );
						
					}else{
						if (!defined('wp_ep_fitness_template'))define( 'wp_ep_fitness_template', $filename);
					}	
					
				}				
				/**
				 * Loads PHP files that required by the plug-in
				 */			

				public function remove_admin_bar() {
					 $iv_hide = get_option( '_ep_fitness_hide_admin_bar');
					if (!current_user_can('administrator') && !is_admin()) {
						if($iv_hide=='yes'){							
						  show_admin_bar(false);
						  
						}
					}	
				}
					
				public function include_template_function( $template_path ) { 
					$default_fields = array();
					$field_set=get_option('_ep_fitness_url_postype' );		
					
					if($field_set!=""){ 
							$default_fields=get_option('_ep_fitness_url_postype' );
					}else{															
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';																
					}
					if(sizeof($field_set)<1){																
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';	
					 }	
					foreach ( $default_fields as $field_key => $field_value ) {												
							if ( get_post_type() == $field_key ) { 
								if(  is_category() || is_archive() || is_single()){
									header("HTTP/1.1 301 Moved Permanently");
									header("Location: ".get_bloginfo('url'));
									exit();
								}	
							}
					  }
					 if ( get_post_type() == 'physical-record' ) { 
							if(  is_category() || is_archive() || is_single()){
								header("HTTP/1.1 301 Moved Permanently");
								header("Location: ".get_bloginfo('url'));
								exit();
							}	
					} 
					
					return $template_path;
				}
				public function ep_post_type(){
					
					$default_fields = array();
					$field_set=get_option('_ep_fitness_url_postype' );		
					
					if($field_set!=""){ 
							$default_fields=get_option('_ep_fitness_url_postype' );
					}else{															
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';																	
					}
					if(sizeof($field_set)<1){																
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';		
					 }	
					foreach ( $default_fields as $field_key => $field_value ) {	
					
						if($field_key!="" ){
								$labels = array(
								'name'                => _x( $field_value, 'Post Type General Name', 'text_directories' ),
								'singular_name'       => _x( $field_value, 'Post Type Singular Name', 'text_directories' ),
								'menu_name'           => __( $field_value, 'text_directories' ),
								'name_admin_bar'      => __( $field_value, 'text_directories' ),
								'parent_item_colon'   => __( 'Parent Item:', 'text_directories' ),
								'all_items'           => __( 'All Items', 'text_directories' ),
								'add_new_item'        => __( 'Add New Item', 'text_directories' ),
								'add_new'             => __( 'Add New', 'text_directories' ),
								'new_item'            => __( 'New Item', 'text_directories' ),
								'edit_item'           => __( 'Edit Item', 'text_directories' ),
								'update_item'         => __( 'Update Item', 'text_directories' ),
								'view_item'           => __( 'View Item', 'text_directories' ),
								'search_items'        => __( 'Search Item', 'text_directories' ),
								'not_found'           => __( 'Not found', 'text_directories' ),
								'not_found_in_trash'  => __( 'Not found in Trash', 'text_directories' ),
							);
							$args = array(
								'label'               => __( $field_value, 'text_directories' ),
								'description'         => __( $field_value, 'text_directories' ),
								'labels'              => $labels,
								'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'post-formats','custom-fields' ),
								//'taxonomies'          => array(  'post_tag' ),
								'hierarchical'        => false,
								'public'              => true,
								'show_ui'             => true,
								'show_in_menu'        => true,
								'menu_position'       => 5,
								'show_in_admin_bar'   => true,
								'show_in_nav_menus'   => true,
								'can_export'          => true,
								'has_archive'         => true,
								'exclude_from_search' => false,
								'publicly_queryable'  => true,
								'capability_type'     => 'post',
								
							);
							register_post_type( $field_key, $args );
							
							
						}
					}			
					
				
				}
				public function ep_create_my_taxonomy_category(){
					$default_fields = array();
					$field_set=get_option('_ep_fitness_url_postype' );		
					
					if($field_set!=""){ 
							$default_fields=get_option('_ep_fitness_url_postype' );
					}else{															
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';																
					}
					if(sizeof($field_set)<1){																
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';		
					 }	
					foreach ( $default_fields as $field_key => $field_value ) {	
					
						if($field_key!="" ){
							register_taxonomy(
							$field_key.'-category',
							$field_key,
								array(
									'label' => __( 'Categories', 'text_directories'),
									'rewrite' => array( 'slug' => $field_key.'-category' ),
									'hierarchical' => true,
									'query_var' => true,
								)
							);
						}
					
					}	
				
				}
				public function ep_create_my_taxonomy_tags(){
					$default_fields = array();
					$field_set=get_option('_ep_fitness_url_postype' );		
					
					if($field_set!=""){ 
							$default_fields=get_option('_ep_fitness_url_postype' );
					}else{															
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';																
					}
					if(sizeof($field_set)<1){																
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';			
					 }	
					foreach ( $default_fields as $field_key => $field_value ) {	
					
						if($field_key!="" ){
							register_taxonomy(
							$field_key.'_tag',
							$field_key,
								array(
									'label' => __( 'Tags', 'text_directories'),
									'rewrite' => array( 'slug' => $field_key.'_tag' ),
									'hierarchical' => true,
									'query_var' => true,
								)
							);
						}
					
					}	
				
				}										
				public function post_type_tags_fix($request) {
					
					if ( isset($request['tag']) && !isset($request['post_type']) ){
						$request['post_type'] = 'directories';
						 
					}
					
					return $request;
				} 
				public function iv_physicalrecord_post_type(){
					$directory_url_2='physical-record';
					
					$labels = array(
						'name'                => _x( $directory_url_2, 'Post Type General Name', 'epfitness' ),
						'singular_name'       => _x( $directory_url_2, 'Post Type Singular Name', 'epfitness' ),
						'menu_name'           => __( "Physical Record", 'epfitness' ),
						'name_admin_bar'      => __( $directory_url_2, 'epfitness' ),
						'parent_item_colon'   => __( 'Parent Item:', 'epfitness' ),
						'all_items'           => __( 'All Items', 'epfitness' ),
						'add_new_item'        => __( 'Add New Item', 'epfitness' ),
						'add_new'             => __( 'Add New', 'epfitness' ),
						'new_item'            => __( 'New Item', 'epfitness' ),
						'edit_item'           => __( 'Edit Item', 'text_directories' ),
						'update_item'         => __( 'Update Item', 'text_directories' ),
						'view_item'           => __( 'View Item', 'text_directories' ),
						'search_items'        => __( 'Search Item', 'text_directories' ),
						'not_found'           => __( 'Not found', 'text_directories' ),
						'not_found_in_trash'  => __( 'Not found in Trash', 'epfitness' ),
					);
					$args = array(
						'label'               => __( $directory_url_2, 'epfitness' ),
						'description'         => __( $directory_url_2, 'epfitness' ),
						'labels'              => $labels,
						'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'comments', 'post-formats','custom-fields' ),
						//'taxonomies'          => array(  'post_tag' ),
						'rewrite' 				=> array('slug' => _x( $directory_url_2, 'URL slug', 'epfitness' )),
						'hierarchical'        => false,
						'public'              => true,
						'show_ui'             => true,
						'show_in_menu'        => true,
						'menu_position'       => 5,
						'show_in_admin_bar'   => true,
						'show_in_nav_menus'   => true,
						'can_export'          => true,
						'has_archive'         => true,
						'exclude_from_search' => false,
						'publicly_queryable'  => true,
						'capability_type'     => 'post',
						
					);
					register_post_type( 'physical-record', $args );
				}
				
				
				public function dir_vc_pricing_table() {
						vc_map( array(
						  "name" => __( "Pricing Table", "epfitness" ),
						  "base" => "ep_fitness_price_table",
						  'icon' =>  wp_ep_fitness_URLPATH.'/assets/images/vc-icon.png',      
						  "class" => "",
						  "category" => __( "Content", "epfitness"),
						  //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
						  //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
						  "params" => array(
							 array(
								"type" => "textfield",
								"holder" => "div",
								"class" => "",
								"heading" => __( "Style Name", "epfitness" ),
								"param_name" => "no",
								"value" => __( "Default", "epfitness" ),
								"description" => __( "You can select the style from wp-admin e.g : style-1.", "epfitness" )
							 )
						  )
					   ) );
				}
				public function dir_vc_signup() {
						vc_map( array(
						  "name" => __( "Signup ", "epfitness" ),
						  "base" => "ep_fitness_form_wizard",
						  'icon' =>  wp_ep_fitness_URLPATH.'/assets/images/vc-icon.png',
						  "class" => "",
						  "category" => __( "Content", "epfitness"),
						  //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
						  //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
						  "params" => array(
							 array(
								"type" => "textfield",
								"holder" => "div",
								"class" => "",
								"heading" => __( "Style Name", "epfitness" ),
								"param_name" => "Default",
								"value" => __( "Default", "epfitness" ),
								"description" => __( ".", "epfitness" )
							 )
						  )
					   ) );
				}
				public function dir_vc_my_account() {
						vc_map( array(
						  "name" => __( "My Acount ", "epfitness" ),
						  "base" => "ep_fitness_profile_template",
						  'icon' =>  wp_ep_fitness_URLPATH.'/assets/images/vc-icon.png',
						  "class" => "",
						  "category" => __( "Content", "epfitness"),
						  //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
						  //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
						  "params" => array(
							 array(
								"type" => "textfield",
								"holder" => "div",
								"class" => "",
								"heading" => __( "Style Name", "epfitness" ),
								"param_name" => "Default",
								"value" => __( "Default", "epfitness" ),
								"description" => __( ".", "epfitness" )
							 )
						  )
					   ) );
				}
				public function dir_vc_public_profile() {
						vc_map( array(
						  "name" => __( "Public Profile ", "epfitness" ),
						  "base" => "ep_fitness_profile_public",
						  'icon' =>  wp_ep_fitness_URLPATH.'/assets/images/vc-icon.png',
						  "class" => "",
						  "category" => __( "Content", "epfitness"),
						  //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
						  //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
						  "params" => array(
							 array(
								"type" => "textfield",
								"holder" => "div",
								"class" => "",
								"heading" => __( "Style Name", "epfitness" ),
								"param_name" => "Default",
								"value" => __( "Default", "epfitness" ),
								"description" => __( "You can select the style from wp-admin e.g : style-1 , style-2 ", "epfitness" )
							 )
						  )
					   ) );
				}
				public function dir_vc_user_directory() {
						vc_map( array(
						  "name" => __( "User Directory ", "epfitness" ),
						  "base" => "ep_fitness_user_directory",
						  'icon' =>  wp_ep_fitness_URLPATH.'/assets/images/vc-icon.png',
						  "class" => "",
						  "category" => __( "Content", "epfitness"),
						  //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
						  //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
						  "params" => array(
							 array(
								"type" => "textfield",
								"holder" => "div",
								"class" => "",
								"heading" => __( "Show  number of user / Page", "epfitness" ),
								"param_name" => "per_page",
								"value" => __( "12", "epfitness" ),
								"description" => __( "You can set the number : 10,20 ", "epfitness" )
							 )
						  )
					   ) );
				}
				public function dir_vc_user_login() {
						vc_map( array(
						  "name" => __( "Login", "epfitness" ),
						  "base" => "ep_fitness_login",
						  'icon' =>  wp_ep_fitness_URLPATH.'/assets/images/vc-icon.png',
						  "class" => "",
						  "category" => __( "Content", "epfitness"),
						  //'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
						  //'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
						  "params" => array(
							 array(
								"type" => "textfield",
								"holder" => "div",
								"class" => "",
								"heading" => __( " Login", "epfitness" ),
								"param_name" => "style",
								"value" => __( "Default", "epfitness" ),
								"description" => __( "Default ", "epfitness" )
							 )
						  )
					   ) );
				}
				
			 public function ep_fitness_woocommerce_form_submit(  ) {
					require_once(wp_ep_fitness_ABSPATH . '/admin/pages/payment-inc/woo-submit.php');
				}
				
				public function author_public_profile() {
					
					$author = get_the_author();	
					$iv_redirect = get_option( '_ep_fitness_profile_public_page');
					if($iv_redirect!='defult'){ 
						$reg_page= get_permalink( $iv_redirect) ; 
						return    $reg_page.'?&id='.$author; //$reg_page ;
						//wp_redirect( $reg_page.'/author/111'  );
						exit;
					}
				}
				
				public function iv_registration_redirect(){
					$iv_redirect = get_option( 'ep_fitness_signup_redirect');
					if($iv_redirect!='defult'){
						$reg_page= get_permalink( $iv_redirect); 
						wp_redirect( $reg_page );
						exit;
					}	
						
				}
				
					
				
				public function ep_fitness_login_func(){
						require_once(wp_ep_fitness_template. 'private-profile/profile-login.php');
				}
				public function ep_fitness_forget_password(){
					
					parse_str($_POST['form_data'], $data_a);
						if( ! email_exists($data_a['forget_email']) ) {
							echo json_encode(array("code" => "not-success","msg"=>"There is no user registered with that email address."));
								exit(0);
						} else {
								require_once( wp_ep_fitness_ABSPATH. 'inc/forget-mail.php');
								echo json_encode(array("code" => "success","msg"=>"Updated Successfully"));
								exit(0);
						}
				}
				public function hd_search_box_func($atts = ''){
					ob_start();	
					include( wp_ep_fitness_template. 'listing/slider-search.php');
					$content = ob_get_clean();
					return $content;
					
			   }
				public function ep_fitness_check_login(){
					
					parse_str($_POST['form_data'], $form_data);
					global $user;
					$creds = array();
					$creds['user_login'] =$form_data['username'];
					$creds['user_password'] =  $form_data['password'];
					$creds['remember'] =  (isset($form_data['remember']) ?'true' : 'false');
					$secure_cookie = is_ssl() ? true : false;
					$user = wp_signon( $creds, $secure_cookie);
					if ( is_wp_error($user) ) {
						//echo $user->get_error_message();
						echo json_encode(array("code" => "not-success","msg"=>$user->get_error_message()));
						exit(0);
					}
					if ( !is_wp_error($user) ) {
						$iv_redirect = get_option( '_ep_fitness_profile_page');
						if($iv_redirect!='defult'){
							if ( function_exists('icl_object_id') ) {
								$iv_redirect = icl_object_id($iv_redirect, 'page', true);
							}	
							
							$reg_page= get_permalink( $iv_redirect); 
							echo json_encode(array("code" => "success","msg"=>$reg_page));
							exit(0);
							//wp_redirect( $reg_page );
							
						}
					}		
				
				}
				
			
				public function ep_fitness_user_directory_func($atts = ''){
					global $current_user;						 
					
					if(isset($atts['style']) and $atts['style']!="" ){
						$tempale=$atts['style']; 
					}else{
						$tempale=get_option('ep_fitness_user_directory'); 
					}
					if($tempale==''){
						$tempale='style-2';
					}	
												
					
					ob_start();						  //include the specified file
					if($tempale=='style-1'){
							include( wp_ep_fitness_template. 'user-directory/directory-template-1.php');
					}
					if($tempale=='style-2'){
							include( wp_ep_fitness_template. 'user-directory/directory-template-2.php');
					}
					
					$content = ob_get_clean();
					return $content;						
							
						
				}
				public function ep_fitness_profile_public_func($atts = '') {						
						
						ob_start();						  //include the specified file
						
						include( wp_ep_fitness_template. 'profile-public/profile-template-2.php');						
						
						$content = ob_get_clean();	
					
					return $content;
				}
				
				
				public function ep_fitness_cancel_paypal(){
						global $wpdb;
						global $current_user;
						parse_str($_POST['form_data'], $form_data);
						
						if( ! class_exists('Paypal' ) ) {
							require_once(wp_ep_fitness_DIR . '/inc/class-paypal.php');
							
						}

						$post_name='ep_fitness_paypal_setting';						
						$row = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_name = '".$post_name."' ");
						$paypal_id='0';
						if(sizeof($row )>0){
							$paypal_id= $row->ID;
						}
						$paypal_api_currency=get_post_meta($paypal_id, 'ep_fitness_paypal_api_currency', true);
						
						$paypal_username=get_post_meta($paypal_id, 'ep_fitness_paypal_username',true);
						$paypal_api_password=get_post_meta($paypal_id, 'ep_fitness_paypal_api_password', true);
						$paypal_api_signature=get_post_meta($paypal_id, 'ep_fitness_paypal_api_signature', true);
						
						$credentials = array();
						$credentials['USER'] = (isset($paypal_username)) ? $paypal_username : '';
						$credentials['PWD'] = (isset($paypal_api_password)) ? $paypal_api_password : '';
						$credentials['SIGNATURE'] = (isset($paypal_api_signature)) ? $paypal_api_signature : '';
						
						$paypal_mode=get_post_meta($paypal_id, 'ep_fitness_paypal_mode', true);
					
						$currencyCode = $paypal_api_currency;
						$sandbox = ($paypal_mode == 'live') ? '' : 'sandbox.';
						$sandboxBool = (!empty($sandbox)) ? true : false;
						
						$paypal = new Paypal($credentials,$sandboxBool);
						
						$oldProfile = get_user_meta($current_user->ID,'iv_paypal_recurring_profile_id',true);
						if (!empty($oldProfile)) {
							$cancelParams = array(
								'PROFILEID' => $oldProfile,
								'ACTION' => 'Cancel'
							);
							$paypal -> request('ManageRecurringPaymentsProfileStatus',$cancelParams);
							
							update_user_meta($current_user->ID,'iv_paypal_recurring_profile_id','');
							update_user_meta($current_user->ID,'iv_cancel_reason', $form_data['cancel_text']); 
							update_user_meta($current_user->ID,'ep_fitness_payment_status', 'cancel'); 
							
							echo json_encode(array("code" => "success","msg"=>"Cancel Successfully"));
							exit(0);							
						}else{
						
							echo json_encode(array("code" => "not","msg"=>"Unable to Cancel "));
							exit(0);	
						}
						
				
				}
				
				
				public function  ep_fitness_profile_stripe_upgrade(){
						require_once(wp_ep_fitness_DIR . '/admin/files/lib/Stripe.php');
						global $wpdb;
						global $current_user;
						parse_str($_POST['form_data'], $form_data);	
						
						$newpost_id='';
						$post_name='ep_fitness_stripe_setting';
						$row = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_name = '".$post_name."' ");
									if(sizeof($row )>0){
									  $newpost_id= $row->ID;
									}
						$stripe_mode=get_post_meta( $newpost_id,'ep_fitness_stripe_mode',true);	
						if($stripe_mode=='test'){
							$stripe_api =get_post_meta($newpost_id, 'ep_fitness_stripe_secret_test',true);	
						}else{
							$stripe_api =get_post_meta($newpost_id, 'ep_fitness_stripe_live_secret_key',true);	
						}
						
						Stripe::setApiKey($stripe_api);						
						// For  cancel ----
						$arb_status =	get_user_meta($current_user->ID, 'ep_fitness_payment_status', true);
						$cust_id = get_user_meta($current_user->ID,'ep_fitness_stripe_cust_id',true);
						$sub_id = get_user_meta($current_user->ID,'ep_fitness_stripe_subscrip_id',true);
						if($sub_id!=''){	
							try{
									$iv_cancel_stripe = Stripe_Customer::retrieve($form_data['cust_id']);
									$iv_cancel_stripe->subscriptions->retrieve($form_data['sub_id'])->cancel();
									
							} catch (Exception $e) {
								//print_r($e);	
								
								//$error_msg=$e;
							}
							update_user_meta($current_user->ID,'ep_fitness_payment_status', 'cancel'); 
							update_user_meta($current_user->ID,'ep_fitness_stripe_subscrip_id','');
						}
						
						// Start  New 
						$response='';
						parse_str($_POST['form_data'], $form_data);
						require_once(wp_ep_fitness_DIR . '/admin/pages/payment-inc/stripe-upgrade.php');
						
						echo json_encode(array("code" => "success","msg"=>$response));
						exit(0);

				}
				public function  ep_fitness_profile_stripe_add_balance(){
						require_once(wp_ep_fitness_DIR . '/admin/files/lib/Stripe.php');
						global $wpdb;
						global $current_user;
						parse_str($_POST['form_data'], $form_data);	
						
						$newpost_id='';
						$post_name='ep_fitness_stripe_setting';
						$row = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_name = '".$post_name."' ");
									if(sizeof($row )>0){
									  $newpost_id= $row->ID;
									}
						$stripe_mode=get_post_meta( $newpost_id,'ep_fitness_stripe_mode',true);	
						if($stripe_mode=='test'){
							$stripe_api =get_post_meta($newpost_id, 'ep_fitness_stripe_secret_test',true);	
						}else{
							$stripe_api =get_post_meta($newpost_id, 'ep_fitness_stripe_live_secret_key',true);	
						}
																		
						// Start  New 
						$response='';
						parse_str($_POST['form_data'], $form_data);
						require_once(wp_ep_fitness_DIR . '/admin/pages/payment-inc/stripe-add-balance.php');
						
						echo json_encode(array("code" => "success","msg"=>$response));
						exit(0);

				}
				public function ep_fitness_cancel_stripe(){
						
						require_once(wp_ep_fitness_DIR . '/admin/files/lib/Stripe.php');
						global $wpdb;
						global $current_user;
						parse_str($_POST['form_data'], $form_data);	
						
						$newpost_id='';
						$post_name='ep_fitness_stripe_setting';
						$row = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_name = '".$post_name."' ");
									if(sizeof($row )>0){
									  $newpost_id= $row->ID;
									}
						$stripe_mode=get_post_meta( $newpost_id,'ep_fitness_stripe_mode',true);	
						if($stripe_mode=='test'){
							$stripe_api =get_post_meta($newpost_id, 'ep_fitness_stripe_secret_test',true);	
						}else{
							$stripe_api =get_post_meta($newpost_id, 'ep_fitness_stripe_live_secret_key',true);	
						}
						parse_str($_POST['form_data'], $form_data);
						
						
						Stripe::setApiKey($stripe_api);
						
						
						try{
								$iv_cancel_stripe = Stripe_Customer::retrieve($form_data['cust_id']);
								$iv_cancel_stripe->subscriptions->retrieve($form_data['sub_id'])->cancel();
						
						} catch (Exception $e) {
							//print_r($e);	
						}
						
						
						update_user_meta($current_user->ID,'iv_cancel_reason', $form_data['cancel_text']); 
						update_user_meta($current_user->ID,'ep_fitness_payment_status', 'cancel'); 
						update_user_meta($current_user->ID,'ep_fitness_stripe_subscrip_id','');
						
						echo json_encode(array("code" => "success","msg"=>"Cancel Successfully"));
						exit(0);
				
				}
				public function  ep_fitness_stripe_form_func(){
					//echo wp_ep_fitness_URLPATH.'files/short_code_file/iv_stripe_form_display';
					require_once(wp_ep_fitness_ABSPATH.'files/short_code_file/iv_stripe_form_display.php');
				}
				
				public function ep_fitness_update_setting_hide(){
					global $current_user;
					parse_str($_POST['form_data'], $form_data);	
					$mobile_hide=(isset($form_data['mobile_hide'])? $form_data['mobile_hide']:'');	
					$email_hide=(isset($form_data['email_hide'])? $form_data['email_hide']:'');	
					$phone_hide=(isset($form_data['phone_hide'])? $form_data['phone_hide']:'');	
					
					update_user_meta($current_user->ID,'hide_email', $email_hide); 
					update_user_meta($current_user->ID,'hide_phone', $phone_hide);					
					update_user_meta($current_user->ID,'hide_mobile',$mobile_hide); 
					
					 
					
					echo json_encode(array("code" => "success","msg"=>"Updated Successfully"));
					exit(0);
				}
				public function iv_training_done(){
					global $current_user;
							
							$training_done_string='_post_done_'.$_POST['post_id'];
							$training_done_string2='_post_done_day_'.$_POST['post_id'].'_'.$_POST['day_num'];
							
							update_user_meta($current_user->ID,$training_done_string, 	'done'); 
							
							update_user_meta($current_user->ID,$training_done_string2, 	'done'); 
							
							echo json_encode(array("code" => "success","msg"=>"Updated Successfully"));
							exit(0);

				}
				public function ep_fitness_update_setting_fb(){
					global $current_user;
					parse_str($_POST['form_data'], $form_data);			
					update_user_meta($current_user->ID,'twitter', $form_data['twitter']); 
					update_user_meta($current_user->ID,'facebook', $form_data['facebook']); 
					update_user_meta($current_user->ID,'gplus', $form_data['gplus']); 
					update_user_meta($current_user->ID,'linkedin', $form_data['linkedin']); 
					
					update_user_meta($current_user->ID,'pinterest', $form_data['pinterest']); 
					update_user_meta($current_user->ID,'instagram', $form_data['instagram']); 
					update_user_meta($current_user->ID,'vimeo', $form_data['vimeo']); 
					update_user_meta($current_user->ID,'youtube', $form_data['youtube']); 
					
					 
					
					echo json_encode(array("code" => "success","msg"=>"Updated Successfully"));
					exit(0);
				}
				public function ep_fitness_update_setting_password(){
					global $current_user;
					parse_str($_POST['form_data'], $form_data);						
					if ( wp_check_password( $form_data['c_pass'], $current_user->user_pass, $current_user->ID) ){
					  
							if($form_data['r_pass']!=$form_data['n_pass']){
								echo json_encode(array("code" => "not", "msg"=>"New Password & Re Password are not same. "));
								exit(0);
							}else{
								wp_set_password( $form_data['n_pass'], $current_user->ID);
								echo json_encode(array("code" => "success","msg"=>"Updated Successfully"));
								exit(0);
							}
					}else{
						
					   echo json_encode(array("code" => "not", "msg"=>"Current password is wrong. "));
						exit(0);
					}
					
					
					
				}
				
				public function ep_fitness_update_profile_setting(){
					global $current_user;
					parse_str($_POST['form_data'], $form_data);							
						
					foreach ( $form_data as $field_key => $field_value ) { 
						
						update_user_meta($current_user->ID,$field_key, $field_value); 
					}
						
					update_user_meta($current_user->ID, 'image_gallery_ids', $form_data['gallery_image_ids']); 
					
					echo json_encode(array("code" => "success","msg"=>"Updated Successfully"));
					exit(0);
				
				}
				public function modify_contact_methods($profile_fields) {

					// Add new fields
				
					$profile_fields['twitter'] = 'Twitter Username';
					$profile_fields['facebook'] = 'Facebook URL';
					$profile_fields['gplus'] = 'Google+ URL';
					$profile_fields['linkedin'] = 'Linkedin';
					
					
							$default_fields = array();
							$field_set=get_option('ep_fitness_profile_fields' );

						   if($field_set!=""){
							$default_fields=get_option('ep_fitness_profile_fields' );
						  }else{
						   $default_fields['first_name']='First Name';
						   $default_fields['last_name']='Last Name';
						   $default_fields['phone']='Phone Number';
						   $default_fields['mobile']='Mobile Number';
						   $default_fields['address']='Address';
						   $default_fields['occupation']='Occupation';
						   $default_fields['description']='About';
						   $default_fields['web_site']='Website Url';

						 }
						 if(sizeof($field_set)<1){
						   $default_fields['first_name']='First Name';
						   $default_fields['last_name']='Last Name';
						   $default_fields['phone']='Phone Number';
						   $default_fields['mobile']='Mobile Number';
						   $default_fields['address']='Address';
						   $default_fields['occupation']='Occupation';
						   $default_fields['description']='About';
						   $default_fields['web_site']='Website Url';
						 }

						 $i=1;
						 foreach ( $default_fields as $field_key => $field_value ) {
							 
							 	$profile_fields[$field_key] = $field_value;
							
					  }
					
					
					return $profile_fields;
				}

				public function iv_restrict_media_library( $wp_query ) {
					global $current_user, $pagenow;
					
						//global $current_user;
						if( is_admin() && !current_user_can('edit_others_posts') ) {
							$wp_query->set( 'author', $current_user->ID );
							add_filter('views_edit-post', 'fix_post_counts');
							add_filter('views_upload', 'fix_media_counts');
						}
					
				}
				public function check_expiry_date($user) {
					
					require_once(wp_ep_fitness_DIR . '/inc/check_expire_date.php');
				}
				
				public function ep_fitness_update_profile_pic(){
					global $current_user;
					if(isset($_REQUEST['profile_pic_url_1'])){
						$iv_profile_pic_url=$_REQUEST['profile_pic_url_1'];
						$attachment_thum=$_REQUEST['attachment_thum'];
					}else{
						$iv_profile_pic_url='';
						$attachment_thum='';
					
					}
					update_user_meta($current_user->ID, 'iv_profile_pic_thum', $attachment_thum);					
					update_user_meta($current_user->ID, 'iv_profile_pic_url', $iv_profile_pic_url);
					echo json_encode('success');
					exit(0);
				}
				
				public function ep_fitness_paypal_form_submit(  ) {
					require_once(wp_ep_fitness_DIR . '/admin/pages/payment-inc/paypal-submit.php');
				}	
				public function ep_fitness_stripe_form_submit(  ) {
					require_once(wp_ep_fitness_DIR . '/admin/pages/payment-inc/stripe-submit.php');
				}
				
				public function plugin_mce_css_ep_fitness( $mce_css ) {
					if ( ! empty( $mce_css ) )
						$mce_css .= ',';

					$mce_css .= plugins_url( 'admin/files/css/iv-bootstrap.css', __FILE__ );

					return $mce_css;
				}
												
				/***********************************
				 * Adds a meta box to the post editing screen
				*/
				public function prfx_custom_meta_iv_listing() {
					$default_fields = array();
					$field_set=get_option('_ep_fitness_url_postype' );		
					
					if($field_set!=""){ 
							$default_fields=get_option('_ep_fitness_url_postype' );
					}else{															
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';																	
					}
					if(sizeof($field_set)<1){																
							$default_fields['training-plans']='Training Plans';
							$default_fields['detox-plans']='Detox Plans';
							$default_fields['diet-plans']='Diet Plans';
							$default_fields['diet-guide']='Diet Guide';
							$default_fields['recipes']='Recipes';		
					 }	
					foreach ( $default_fields as $field_key => $field_value ) {
						add_meta_box('prfx_meta', __(' Assign the Post', 'epfitness'), array(&$this, 'iv_listing_meta_callback'),$field_key,'advanced');
					
					}	
					
					
				
				
				}
					public function ep_fitness_save_record(){
					global $current_user; global $wpdb;	
					$directory_url_1='physical-record';					
					$post_type='physical-record';	
					
					parse_str($_POST['form_data'], $form_data);				
					$my_post = array();
					//$my_post['ID'] = $form_data['user_post_id'];
					
					$my_post['post_title'] = 'User Name = '.$current_user->user_login.' | Date ='.date('Y-m-d H:i:s');
										
					
					$form_data['post_status']='publish';
					$my_post['post_status'] = $form_data['post_status'];					
					$newpost_id= wp_insert_post( $my_post );						
					$post_type = $directory_url_1;
					if($post_type!=''){
							$query = "UPDATE {$wpdb->prefix}posts SET post_type='" . $post_type . "' WHERE id='" . $newpost_id . "' LIMIT 1";
							$wpdb->query($query);										
					}
					// WPML Start******
					if ( function_exists('icl_object_id') ) {
					include_once( WP_PLUGIN_DIR . '/sitepress-multilingual-cms/inc/wpml-api.php' );
					$_POST['icl_post_language'] = $language_code = ICL_LANGUAGE_CODE;
					$query = "UPDATE {$wpdb->prefix}icl_translations SET element_type='post_".$directory_url_1."' WHERE element_id='" . $newpost_id . "' LIMIT 1";
					$wpdb->query($query);					
					//wpml_update_translatable_content( 'post_directories', $newpost_id , $language_code );	
					}
					// End WPML**********
					
											
					if(isset($form_data['feature_image_id'] )){
							$attach_id =$form_data['feature_image_id'];
							set_post_thumbnail( $newpost_id, $attach_id );					
					}
				
					
					update_post_meta($newpost_id, 'week', $form_data['week']); 
					update_post_meta($newpost_id, 'date', $form_data['date']);
					$default_fields = array();
					$field_set=get_option('ep_fitness_fields' );
							
							
						if($field_set!=""){ 
								$default_fields=get_option('ep_fitness_fields' );
						}else{															
								$default_fields['height']='Height';
								$default_fields['weight']='Weight';
								$default_fields['chest']='Chest';
								$default_fields['l-arm']='Left Arm';
								$default_fields['r-arm']='Right Arm';
								$default_fields['waist']='Waist';
								$default_fields['abdomen']='Abdomen';
								$default_fields['hips']='Hips';
								$default_fields['l-thigh']='Left Thigh';
								$default_fields['r-thigh']='Right Thigh';
								$default_fields['l-calf']='Left Calf';
								$default_fields['r-calf']='Right Calf';
						}
						if(sizeof($field_set)<1){																
								$default_fields['height']='Height';
								$default_fields['weight']='Weight';
								$default_fields['chest']='Chest';
								$default_fields['l-arm']='Left Arm';
								$default_fields['r-arm']='Right Arm';
								$default_fields['waist']='Waist';
								$default_fields['abdomen']='Abdomen';
								$default_fields['hips']='Hips';
								$default_fields['l-thigh']='Left Thigh';
								$default_fields['r-thigh']='Right Thigh';
								$default_fields['l-calf']='Left Calf';
								$default_fields['r-calf']='Right Calf';
						 }
					
					if(sizeof($default_fields )){			
						foreach( $default_fields as $field_key => $field_value ) { 
							if(isset($form_data[$field_key])){		
								$form_data[$field_key];
								update_post_meta($newpost_id, $field_key, $form_data[$field_key] );
							}
						}					
					}
					
					
					echo json_encode(array("code" => "success","msg"=>"Updated Successfully"));
					exit(0);
										
				
				}
				public function ep_fitness_update_record(){
					global $current_user;global $wpdb;	
					parse_str($_POST['form_data'], $form_data);		
					$my_post = array();
					$directory_url_1='physical-record';	
					$directory_url_2='physical-record';				
				
					
					$my_post['ID'] = $newpost_id= $form_data['user_post_id'];
					
					$form_data['post_status']='publish';
					$my_post['post_status'] = $form_data['post_status'];
					
							
					if(isset($form_data['feature_image_id'] )){
							$attach_id =$form_data['feature_image_id'];
							set_post_thumbnail( $newpost_id, $attach_id );					
					}	
					
					update_post_meta($newpost_id, 'week', $form_data['week']); 
					update_post_meta($newpost_id, 'date', $form_data['date']);
					
					$default_fields = array();
					$field_set=get_option('ep_fitness_fields' );
							
							
						if($field_set!=""){ 
								$default_fields=get_option('ep_fitness_fields' );
						}else{															
								$default_fields['height']='Height';
								$default_fields['weight']='Weight';
								$default_fields['chest']='Chest';
								$default_fields['l-arm']='Left Arm';
								$default_fields['r-arm']='Right Arm';
								$default_fields['waist']='Waist';
								$default_fields['abdomen']='Abdomen';
								$default_fields['hips']='Hips';
								$default_fields['l-thigh']='Left Thigh';
								$default_fields['r-thigh']='Right Thigh';
								$default_fields['l-calf']='Left Calf';
								$default_fields['r-calf']='Right Calf';
						}
						if(sizeof($field_set)<1){																
								$default_fields['height']='Height';
								$default_fields['weight']='Weight';
								$default_fields['chest']='Chest';
								$default_fields['l-arm']='Left Arm';
								$default_fields['r-arm']='Right Arm';
								$default_fields['waist']='Waist';
								$default_fields['abdomen']='Abdomen';
								$default_fields['hips']='Hips';
								$default_fields['l-thigh']='Left Thigh';
								$default_fields['r-thigh']='Right Thigh';
								$default_fields['l-calf']='Left Calf';
								$default_fields['r-calf']='Right Calf';
						 }
					
					if(sizeof($default_fields )){			
						foreach( $default_fields as $field_key => $field_value ) { 
							if(isset($form_data[$field_key])){		
								$form_data[$field_key];
								update_post_meta($newpost_id, $field_key, $form_data[$field_key] );
							}
						}					
					}	
					
					echo json_encode(array("code" => "success","msg"=>"Updated Successfully"));
					exit(0);				
					
				
				}
				
				
				public function ep_fitness_check_coupon(){
				
					global $wpdb;
					$coupon_code=$_REQUEST['coupon_code'];
					$package_id=$_REQUEST['package_id'];
					//$package_amount =$_REQUEST['package_amount'];
					$package_amount=get_post_meta($package_id, 'ep_fitness_package_cost',true);
					$api_currency =$_REQUEST['api_currency'];
					
						$post_cont = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_title = '" . $coupon_code . "' and  post_type='iv_coupon'");	
						
						if(sizeof($post_cont)>0 && $package_amount>0){
							$coupon_name = $post_cont->post_title;
							 
							 $current_date=$today = date("m/d/Y");
							 
							 
							 $start_date=get_post_meta($post_cont->ID, 'iv_coupon_start_date', true);
							 $end_date=get_post_meta($post_cont->ID, 'iv_coupon_end_date', true);
							 $coupon_used=get_post_meta($post_cont->ID, 'iv_coupon_used', true);
							 $coupon_limit=get_post_meta($post_cont->ID, 'iv_coupon_limit', true);
							 $dis_amount=get_post_meta($post_cont->ID, 'iv_coupon_amount', true);							 
							 $package_ids =get_post_meta($post_cont->ID, 'iv_coupon_pac_id', true);
							 
							 $all_pac_arr= explode(",",$package_ids);
							 
							 $today_time = strtotime($current_date);
							 $start_time = strtotime($start_date);
							 $expire_time = strtotime($end_date);
							 
												
							 if(in_array('0', $all_pac_arr)){
								$pac_found=1;
								
							 }else{
								if(in_array($package_id, $all_pac_arr)){
									$pac_found=1;
								}else{
									$pac_found=0;
								}
								
							 }
							 $recurring = get_post_meta( $package_id,'ep_fitness_package_recurring',true); 
							
							
							 if($today_time >= $start_time && $today_time<=$expire_time && $coupon_used<=$coupon_limit && $pac_found == '1' && $recurring!='on' ){
						
								$total = $package_amount -$dis_amount;
								$coupon_type= get_post_meta($post_cont->ID, 'iv_coupon_type', true);
								if($coupon_type=='percentage'){
										$dis_amount= $dis_amount * $package_amount/100;
										$total = $package_amount -$dis_amount ;
								}
								
								echo json_encode(array('code' => 'success',
														'dis_amount' => $dis_amount.' '.$api_currency,
														'gtotal' => $total.' '.$api_currency,
														'p_amount' => $package_amount.' '.$api_currency,
													));
								
								exit(0);
							}else{
								$dis_amount='';
								$total=$package_amount;
								echo json_encode(array('code' => 'not-success-2',
														'dis_amount' => '',
														'gtotal' => $total.' '.$api_currency,
														'p_amount' => $package_amount.' '.$api_currency,
														
													));
								exit(0);
							
							}
							
						
						}else{
								if($package_amount=="" or $package_amount=="0"){$package_amount='0';}
								$dis_amount='';
								$total=$package_amount;
								echo json_encode(array('code' => 'not-success-1',
														'dis_amount' => '',
														'gtotal' => $total.' '.$api_currency,
														'p_amount' => $package_amount.' '.$api_currency,
													));
								exit(0);
						
						}
						
					
					
				}
				public function ep_fitness_check_package_amount(){
				
					global $wpdb;
					$coupon_code=isset($_REQUEST['coupon_code']);
					$package_id=$_REQUEST['package_id'];
										
					if( get_post_meta( $package_id,'ep_fitness_package_recurring',true) =='on'  ){
						$package_amount=get_post_meta($package_id, 'ep_fitness_package_recurring_cost_initial', true);			
					}else{					
						$package_amount=get_post_meta($package_id, 'ep_fitness_package_cost',true);
					}
					
					$api_currency =$_REQUEST['api_currency'];
						
						$post_cont = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_title = '" . $coupon_code . "' and  post_type='iv_coupon'");	
						
						if(sizeof($post_cont)>0){
							$coupon_name = $post_cont->post_title;
							 
							 $current_date=$today = date("m/d/Y");
							 
							 
							 $start_date=get_post_meta($post_cont->ID, 'iv_coupon_start_date', true);
							 $end_date=get_post_meta($post_cont->ID, 'iv_coupon_end_date', true);
							 $coupon_used=get_post_meta($post_cont->ID, 'iv_coupon_used', true);
							 $coupon_limit=get_post_meta($post_cont->ID, 'iv_coupon_limit', true);
							 $dis_amount=get_post_meta($post_cont->ID, 'iv_coupon_amount', true);							 
							 $package_ids =get_post_meta($post_cont->ID, 'iv_coupon_pac_id', true);
							 
							 $all_pac_arr= explode(",",$package_ids);
							 
							 $today_time = strtotime($current_date);
							 $start_time = strtotime($start_date);
							 $expire_time = strtotime($end_date);
							 
							 $pac_found= in_array($package_id, $all_pac_arr);							
							 
							 if($today_time >= $start_time && $today_time<=$expire_time && $coupon_used<=$coupon_limit && $pac_found=="1"){
							// if( $today_time<=$expire_time && $coupon_used<=$coupon_limit){	
								$total = $package_amount -$dis_amount;
								echo json_encode(array('code' => 'success',
														'dis_amount' => $dis_amount.' '.$api_currency,
														'gtotal' =>$total.' '.$api_currency,
														'p_amount' => $package_amount.' '.$api_currency,
													));
								
								exit(0);
							}else{
								$dis_amount='--';
								$total=$package_amount;
								echo json_encode(array('code' => 'not-success',
														'dis_amount' => $dis_amount.' '.$api_currency,
														'gtotal' => $total.' '.$api_currency,
														'p_amount' =>$package_amount.' '.$api_currency,
													));
								exit(0);
							
							}
							
						
						}else{
								
								$dis_amount='--';
								$total=$package_amount;
								echo json_encode(array('code' => 'not-success',
														'dis_amount' => $dis_amount.' '.$api_currency,
														'gtotal' => $total.' '.$api_currency,
														'p_amount' => $package_amount.' '.$api_currency,
													));
								exit(0);
						
						}
						
					
					
				}
				/**
				 * Outputs the content of the meta box
				 */
				public function iv_listing_meta_callback($post) {
					wp_nonce_field(basename(__FILE__), 'prfx_nonce');
					require_once ('admin/pages/metabox.php');
				}
				
				public function iv_listing_meta_save($post_id) {					
					
					//$is_autosave = wp_is_post_autosave($post_id);	
					$post_for=(isset($_REQUEST['post_for_radio'])?$_REQUEST['post_for_radio']: get_post_meta( $post_id,'_ep_post_for', true ));
					if($post_for!=''){
						update_post_meta( $post_id,'_ep_post_for', $post_for );
						$specific_package=(isset($_REQUEST['specific_package'])?$_REQUEST['specific_package']:'');
						update_post_meta( $post_id,'_ep_postfor_package', $specific_package);
						$specific_users=(isset($_REQUEST['specific_users'])?$_REQUEST['specific_users']:'');
						update_post_meta( $post_id,'_ep_postfor_user', $specific_users);

						$specific_products=(isset($_REQUEST['Woocommerce_products'])?$_REQUEST['Woocommerce_products']:'');
						update_post_meta( $post_id,'_ep_postfor_woocommerce', $specific_products);

						
						
						$day_for=(isset($_REQUEST['day_for'])?$_REQUEST['day_for']:'');
						update_post_meta( $post_id,'_ep_day_option', $day_for );
						$day_number=(isset($_REQUEST['day_number'])?$_REQUEST['day_number']:'');
						update_post_meta( $post_id,'_ep_user_day_number', $day_number );
						$day_date=(isset($_REQUEST['day_date'])?$_REQUEST['day_date']:'');
						update_post_meta( $post_id,'_ep_user_date', $day_date ); 
					}
					//var_dump($_REQUEST);
					//print_r($_POST);
					//var_dump($_REQUEST['post_for_radio']);
					//echo' ....Post Meta...'. get_post_meta( $post_id,'_ep_post_for', true ).'..Post ID...'.$post_id;
					//die('--5---');	
				}
				public function iv_doctor_meta_save($post_id) {
					
					global $wpdb;
					$is_autosave = wp_is_post_autosave($post_id);
					
					if (isset($_REQUEST['iv_doctor_approve'])) {
						if($_REQUEST['iv_doctor_approve']=='yes'){ 
								update_post_meta($post_id, 'iv_doctor_approve', $_REQUEST['iv_doctor_approve']);
							// Set new user for post
							$sql="UPDATE  $wpdb->posts SET post_author=".$_REQUEST['ep_fitness_author_id']."  WHERE ID=".$post_id;		
							$wpdb->query($sql); 	
						}
					} 
				}
				public function iv_content_protected_pages($content) {					
					$current_user = wp_get_current_user();
					global $post ;	
					ob_start();	
					
					$active_module=get_option('_ep_fitness_active_visibility_page'); 					
					if(!isset($post->post_type)){
							return $content;
					}	
					
					
					
					if($active_module=='yes' AND $post->post_type=='page'){					
						if(isset($current_user->ID) AND $current_user->ID!=''){
							$user_role= $current_user->roles[0];
							if(isset($current_user->roles[0]) and $current_user->roles[0]=='administrator'){
								return $content;
							}
							$message=get_option('_iv_visibility_login_message');							
						}else{							
							$user_role= 'visitor';
							
							$message=get_option('_iv_visibility_visitor_message');
								
						}	
						$store_array=get_option('_iv_visibility_serialize_page_role');
						//echo'<pre>';
						//print_r($store_array);

						 if(isset($store_array[$user_role]))
						{	
							if(in_array($post->post_name, $store_array[$user_role])){
								return $content;
							}else{
								$content = ob_get_clean();
								$content =  $message; 
								return $content;
							}
							
						}else{ 
								$content = ob_get_clean();
								$content =  $message; 
								return $content;
						
						}
						
						
					}
					return $content;
				}
				
				public function ep_fitness_cpt_change(){
					
					$custom_post_type = $_POST['select_data'];
					
							$args2 = array(
							'type'                     => $custom_post_type,
							//'parent'                   => $term_parent->term_id,
							'orderby'                  => 'name',
							'order'                    => 'ASC',
							'hide_empty'        	   => false,
							'hierarchical'             => 0,
							'exclude'                  => '',
							'include'                  => '',
							'number'                   => '',
							'taxonomy'                 => $custom_post_type.'-category',
							'pad_counts'               => false

							);
							$categories = get_categories( $args2 );
							
							if ( $categories && !is_wp_error( $categories ) ) :

								$val_cat2='<select name="postcats" id="postcats" class="form-control">';
								$val_cat2=$val_cat2.'<option  value="">'.__('Any Category','epfitness').'</option>';
								foreach ( $categories as $term ) {
									$val_cat2=$val_cat2. '<option  value="'.$term->slug.'" >'.$term->name.'</option>';
									
								}
							$val_cat2=$val_cat2.'</select>';
							endif;	
							$args3 = array(
									'type'                     => $custom_post_type,
									//'parent'                   => $term_parent->term_id,
									'orderby'                  => 'name',
									'order'                    => 'ASC',
									'hide_empty'               => 0,
									'hierarchical'             => 1,
									'exclude'                  => '',
									'include'                  => '',
									'number'                   => '',
									'taxonomy'                 => $custom_post_type.'_tag',
									'pad_counts'               => false

								);
								$tags='';
								$p_tag = get_categories( $args3 );												
								if ( $p_tag && !is_wp_error( $p_tag ) ) :


									foreach ( $p_tag as $term ) {
										$tags=$tags.'<div class="col-md-4"><label class="form-group"><input type="checkbox" name="tag_arr[]" id="tag_arr[]" value="'. $term->slug.'"> '.$term->name.'</label></div>';
									
									}

								endif;
							
					echo json_encode(array("msg" => $val_cat2,"tags" => $tags));
					exit(0);
				
				}
				public function no_comments_on_page( $file )
				{
					
					$current_user = wp_get_current_user(); $user_role= '';	
					global $post ;	
							//echo'<pre>'; print_r($post); echo'</pre>';
					$active_module=get_option('_ep_fitness_active_visibility_page'); 					
					
					if($active_module=='yes'){	
							if(isset($current_user->ID) AND $current_user->ID!=''){
								$user_role= $current_user->roles[0];
								if(isset($current_user->roles[0]) and $current_user->roles[0]=='administrator'){
									return $file;
								}														
							}else{							
								 $user_role= 'visitor';					
								
							}					
						$have_access=0;
						$store_array=get_option('_iv_visibility_serialize_role');	
						//echo'<pre>'; print_r($store_array); echo'</pre>'.$user_role;
						if(isset($store_array[$user_role]))	{	
							$post_category='';
							if(get_the_category($post->ID)){ 
								 $post_category = get_the_category($post->ID);  // the value is recieved properly
								if(isset($post_category[0]->category_nicename)){
									$post_category=$post_category[0]->category_nicename;
								}
								
							}
							if(in_array($post_category, $store_array[$user_role])){
								$have_access=1; 
							}else{
								 $have_access=0;
							}							
						}
						$have_access_page=0;
						$store_array_page=get_option('_iv_visibility_serialize_page_role');	
						 if(isset($store_array_page[$user_role])){	
							if(in_array($post->post_name, $store_array_page[$user_role])){
								$have_access_page=1;							
							}else{
								$have_access_page=0;
								
							}							
						}
						if($have_access == 0 AND $have_access_page == 0){
							
							 $file =wp_ep_fitness_DIR . '/admin/pages/empty-comment-file.php';
						}
						
					}
					
					
					
					return $file;
				}

				public function ep_fitness_content_protected($content) { 
					
					$current_user = wp_get_current_user();
					global $post ;
					
					$active_module=get_option('_ep_fitness_active_visibility'); 
					ob_start();	
					

					if($active_module=='yes' AND $post->post_type=='post'){	
										
						if(isset($current_user->ID) AND $current_user->ID!=''){
							$user_role= $current_user->roles[0];
							if(isset($current_user->roles[0]) and $current_user->roles[0]=='administrator'){
								
								return $content;
							}
							$message=get_option('_iv_visibility_login_message');
							$iv_redirect = get_option( '_ep_fitness_profile_page');							
							$reg_page= '<a href="'.get_permalink( $iv_redirect).'?&profile=level "> Here </a';
							$message= str_replace('[here_link]', $reg_page, $message);							

						}else{							
							$user_role= 'visitor';
							
							$message=get_option('_iv_visibility_visitor_message');
							$iv_redirect = get_option( '_ep_fitness_login_page');							
							$reg_page= '<a href="'.get_permalink( $iv_redirect).' "> Here </a';
							$message= str_replace('[here_link]', $reg_page, $message);	
						}
						
						$post_category='';
							if(get_the_category($post->ID)){ 
								 $post_category = get_the_category($post->ID);  // the value is recieved properly
								if(isset($post_category[0]->category_nicename)){
									$post_category=$post_category[0]->category_nicename;
								}
								
							}
						$store_array=get_option('_iv_visibility_serialize_role');
						
						 if(isset($store_array[$user_role]))
						{	
							if(in_array($post_category, $store_array[$user_role])){
								
								return $content;
							}else{
								
								$content = ob_get_clean();
								$content =  $message; 
								return $content;
							}
							//print_r($store_array['Silver']);
						}else{ 
							$content='';
							$content = ob_get_clean();
							$content =  $message; 
							return $content;
							
						}
						
						$output='';
						$output = $content;
					}
					
					
					return $content;
				}
				
				/**
				 * Checks that the WordPress setup meets the plugin requirements
				 * @global string $wp_version
				 * @return boolean
				 */
				private function check_requirements() {
					global $wp_version;
					if (!version_compare($wp_version, $this->wp_version, '>=')) {
						add_action('admin_notices', 'wp_ep_fitness::display_req_notice');
						return false;
					}
					return true;
				}
				
				/**
				 * Display the requirement notice
				 * @static
				 */
				static function display_req_notice() {
					global $wp_ep_fitness;
					echo '<div id="message" class="error"><p><strong>';
					echo __('Sorry, BootstrapPress re requires WordPress ' . $wp_ep_fitness->wp_version . ' or higher.
						Please upgrade your WordPress setup', 'wp-pb');
					echo '</strong></p></div>';
				}
				public function ep_fitness_user_exist_check(){
						global $wpdb;
					
					parse_str($_POST['form_data'], $data_a2);
						
						
					
					if(isset($data_a2['contact_captcha'])){
						$captcha_answer="";
						if(isset($data_a2['captcha_answer'])){
							$captcha_answer=$data_a2['captcha_answer'];
						}
						if($data_a2['contact_captcha']!=$captcha_answer){
							echo json_encode('captcha_error');
							exit(0);
						}						
					}
					$userdata = array();
					$user_name='';
					if(isset($data_a2['iv_member_user_name'])){
						$userdata['user_login']=$data_a2['iv_member_user_name'];
					}					
					if(isset($data_a2['iv_member_email'])){
						$userdata['user_email']=$data_a2['iv_member_email'];
					}					
					if(isset($data_a2['iv_member_password'])){
						$userdata['user_pass']=$data_a2['iv_member_password'];
					}
					
					
					if($userdata['user_login']!='' and $userdata['user_email']!='' and $userdata['user_pass']!='' ){
					
						$user_id = username_exists( $userdata['user_login'] );
						if ( !$user_id and email_exists($userdata['user_email']) == false ) {							
							 //$user_id = wp_insert_user( $userdata ) ;
								echo json_encode('success');
								exit(0);
						} else {
								echo json_encode('User or Email exists');
								exit(0);
						}
					}
									
				
						
				
				}
				public function ep_fitness_registration_submit() {
					
					global $wpdb;
					
					parse_str($_POST['form_data'], $data_a2);
						
						
						
					if(isset($data_a2['contact_captcha'])){
						$captcha_answer="";
						if(isset($data_a2['captcha_answer'])){
							$captcha_answer=$data_a2['captcha_answer'];
						}
						if($data_a2['contact_captcha']!=$captcha_answer){
							echo json_encode('captcha_error');
							exit(0);
						}						
					}
					$userdata = array();
					$user_name='';
					if(isset($data_a2['iv_member_user_name'])){
						$userdata['user_login']=$data_a2['iv_member_user_name'];
					}					
					if(isset($data_a2['iv_member_email'])){
						$userdata['user_email']=$data_a2['iv_member_email'];
					}					
					if(isset($data_a2['iv_member_password'])){
						$userdata['user_pass']=$data_a2['iv_member_password'];
					}
					if(isset($data_a2['iv_member_password'])){
						$userdata['first_name']=$data_a2['iv_member_fname'];
					}
					if(isset($data_a2['iv_member_password'])){
						$userdata['last_name']=$data_a2['iv_member_lname'];
					}							
					
					//print_r($userdata);
					
					if($userdata['user_login']!='' and $userdata['user_email']!='' and $userdata['user_pass']!='' ){
					
						$user_id = username_exists( $userdata['user_login'] );
						if ( !$user_id and email_exists($userdata['user_email']) == false ) {							
							 $user_id = wp_insert_user( $userdata ) ;
						} else {
							echo json_encode('User or Email exists');
							exit(0);
						}
					}
					
					//$hidden_form_name = $data_a2['ep_fitness_registration'];
					$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'ep_fitness_account_form'");
					
					$ep_fitness_auto_email = get_post_meta($post_id, 'ep_fitness_auto_email', true);
					$admin_mail = get_option('admin_email');
					$wp_title = get_bloginfo();
					
						// email for user
					
					foreach ($data_a2 as $key => $item) {
						$search_sort_key = '[' . $key . ']';
						$ep_fitness_auto_email = str_replace($search_sort_key, $item, $ep_fitness_auto_email);
						$search_sort_key = '[ ' . $key . ' ]';
						$ep_fitness_auto_email = str_replace($search_sort_key, $item, $ep_fitness_auto_email);
					}
					
					$ep_fitness_admin_email = get_post_meta($post_id, 'ep_fitness_admin_email', true);
						
						
					$cilent_email_address = $userdata['user_email'];
							
					$auto_subject=  get_post_meta($post_id, 'ep_fitness_auto_email_subject', true); 
							
					$headers = array("From: " . $wp_title . " <" . $admin_mail . ">", "Content-Type: text/html");
					$h = implode("\r\n", $headers) . "\r\n";
							
					if (get_option('ep_fitness_auto_reply') == 'yes') {
								wp_mail($cilent_email_address, $auto_subject, $ep_fitness_auto_email, $h);
					}
						
						
				
										
				
						echo json_encode('success');
						exit(0);
				}
				
				
				
				private function load_dependencies() {
					
						// Admin Panel
					if (is_admin()) {
						require_once ('admin/forms.php');
						require_once ('admin/notifications.php');
						require_once ('admin/tables.php');
						require_once ('admin/admin.php');
					}
					
						// Front-End Site
					if (!is_admin()) {
					}
					
						// Global
					require_once ('inc/widget.php');
				}
				
				/**
				 * Called every time the plug-in is activated.
				 */
				public function activate() {
					require_once ('install/install.php');
					
						
				}
				
				/**
				 * Called when the plug-in is deactivated.
				 */
				public function deactivate() {
					global $wpdb;			
					
					$page_name='price-table';						
					$query = "delete from {$wpdb->prefix}posts where  post_name='".$page_name."' LIMIT 1";
					$wpdb->query($query);
					
					$page_name='registration';						
					$query = "delete from {$wpdb->prefix}posts where  post_name='".$page_name."' LIMIT 1";
					$wpdb->query($query);
					
					$page_name='my-account';						
					$query = "delete from {$wpdb->prefix}posts where  post_name='".$page_name."' LIMIT 1";
					$wpdb->query($query);
					
					$page_name='profile-public';						
					$query = "delete from {$wpdb->prefix}posts where  post_name='".$page_name."' LIMIT 1";
					$wpdb->query($query);
					
					$page_name='thank-you';						
					$query = "delete from {$wpdb->prefix}posts where  post_name='".$page_name."' LIMIT 1";
					$wpdb->query($query);
					
					$page_name='login';						
					$query = "delete from {$wpdb->prefix}posts where  post_name='".$page_name."' LIMIT 1";
					$wpdb->query($query);
					
					$page_name='user-directory';						
					$query = "delete from {$wpdb->prefix}posts where  post_name='".$page_name."' LIMIT 1";
					$wpdb->query($query);
					
					$page_name='iv-reminder-email-cron-job';						
					$query = "delete from {$wpdb->prefix}posts where  post_name='".$page_name."' LIMIT 1";
					$wpdb->query($query);
					
					
					
				}
				
				/**
				 * Called when the plug-in is uninstalled
				 */
				static function uninstall() {
				}
				
				/**
				 * Register the widgets
				 */
				public function register_widget() {
					//register_widget("wp_ep_fitness_widget");
				}
				
				/**
				 * Internationalization
				 */
				public function i18n() {
					
					load_plugin_textdomain('epfitness', false, basename(dirname(__FILE__)) . '/languages/' );
					//die( basename(dirname(__FILE__)) . '/languages/');
				}
				
				/**
				 * Starts the plug-in main functionality
				 */
				public function start() {
				}
				public function ep_fitness_display_func($atts = '', $content = '') {
					global $wpdb;					
						
					if (isset($atts['form'])) {
						$form_name = $atts['form'];
						$post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '" . $form_name . "'");
						
						$content_post = get_post($post_id);
						$content = $content_post->post_content;
						
					}
					return $content;
				}
				
				public function ep_fitness_price_table_func($atts = '', $content = '') {
					
							ob_start();						  //include the specified file
							if(isset($atts['style']) and $atts['style']!="" ){
									$tempale=$atts['style']; 
							}else{
								
							
								if(get_option('ep_fitness_price-table')){
									$tempale=	get_option('ep_fitness_price-table');
									
								}else{
									$tempale=	'style-1';
								}
							}
						
						ob_start();						  //include the specified file
							if($tempale=='style-1'){
									include( wp_ep_fitness_template. 'price-table/price-table-1.php');
							}
							if($tempale=='style-2'){
									include( wp_ep_fitness_template. 'price-table/price-table-2.php');
							}
							if($tempale=='style-3'){
									include( wp_ep_fitness_template. 'price-table/price-table-3.php');
							}
							if($tempale=='style-4'){
									include( wp_ep_fitness_template. 'price-table/price-table-4.php');
							}
							if($tempale=='style-5'){
									include( wp_ep_fitness_template. 'price-table/price-table-5.php');
							}
							if($tempale=='style-6'){
									include( wp_ep_fitness_template. 'price-table/price-table-6.php');
							}
							if($tempale=='style-7'){
									include( wp_ep_fitness_template. 'price-table/price-table-7.php');
							}
						
						$content = ob_get_clean();	
					
					return $content;
					
					
					
				}
				
				public function ep_fitness_registration_form_func($atts = '', $content = '') {
						
						$package_id=0;
						if(isset($_REQUEST['package_id'])){
							$package_id=$_REQUEST['package_id'];
						}
						
						global $wpdb;
						
						$post_name='ep_fitness_account_form';
						$post_content = $wpdb->get_row("SELECT post_content FROM $wpdb->posts WHERE post_name = '" . $post_name . "'");	
						
						if(sizeof($post_content)>0){
							$content = $post_content->post_content;
							$content = str_replace('ep_fitness_package_id_change', $package_id, $content);
						}
						
					
					return $content;
				}
				public function ep_fitness_payment_form_func($atts = '', $content = '') {
						
						
						
						global $wpdb;
						
						$post_name='ep_fitness_payment_form';
						$post_content = $wpdb->get_row("SELECT post_content FROM $wpdb->posts WHERE post_name = '" . $post_name . "'");	
						
						if(sizeof($post_content)>0){
							$content = $post_content->post_content;
							//$content = str_replace('ep_fitness_package_id_change', $package_id, $content);
						}
						
					
					return $content;
				}
				public function iv_archive_directories_func(){
					ob_start();	
					$template_path=wp_ep_fitness_template.'directories/';
					include( $template_path. 'archive-directories.php');
					
					$content = ob_get_clean();
					return $content;
				}
				public function ep_fitness_form_wizard_func($atts = '') {
						
						//global $wpdb;
						$template_path=wp_ep_fitness_template.'signup/';
						
						ob_start();	 //include the specified file
							
						include( $template_path. 'wizard-style-2.php');	
						$content = ob_get_clean();	
					
					return $content;
				}
				
				public function ep_fitness_profile_template_func($atts = '') {
						
						//global $wpdb;
					 global $current_user;
					 ob_start();
					 if($current_user->ID==0){
							require_once(wp_ep_fitness_template. 'private-profile/profile-login.php');
					 
					 }else{
					  //update_option('ep_fitness_profile-template', 'style-1' );
						$tempale=get_option('ep_fitness_profile-template'); 
												  //include the specified file
							if($tempale=='style-1'){
									include( wp_ep_fitness_template. 'private-profile/profile-template-1.php');
							}
							if($tempale=='style-2'){
									include( wp_ep_fitness_template. 'private-profile/profile-template-1.php');
							}
						
						
					}
					
					$content = ob_get_clean();	
					
					return $content;
						
					
				}
				public function ep_fitness_reminder_email_cron_func ($atts = ''){
					
					include( wp_ep_fitness_ABSPATH. 'inc/reminder-email-cron.php');
					
				
				}
				public function ep_fitness_cron_job(){
					
					include( wp_ep_fitness_ABSPATH. 'inc/all_cron_job.php');
					exit(0);
				}
				
				public function listing_categories_func($atts = ''){
					ob_start();						
					include( wp_ep_fitness_template. 'listing/listing_categories.php');					
					$content = ob_get_clean();
					return $content;
				}
				
				public function directorypro_map_func($atts = ''){
					ob_start();	
						include( wp_ep_fitness_template. 'directories/directories-map.php');
					$content = ob_get_clean();
					return $content;
				}				
				public function listing_featured_func($atts = ''){
					ob_start();
					include( wp_ep_fitness_template. 'listing/listing_featured.php');
					$content = ob_get_clean();
					return $content;
				}		
				
				
				public function ep_fitness_paypal_notify_url(){				
						include( wp_ep_fitness_ABSPATH. 'inc/paypal_deal_notify_mail.php');	
						exit(0);
				}
				public function ep_fitness_message_send(){
					parse_str($_POST['form_data'], $form_data);					
					
					include( wp_ep_fitness_ABSPATH. 'inc/message-mail.php');	
					
					echo json_encode(array("msg" => 'Message Sent'));
					exit(0);
					
				}
				
				public function paging() {
					global $wp_query;
					
				} 
				public function check_write_access($arg=''){
					 $current_user = wp_get_current_user();
					 $userId=$current_user->ID;
					
					if(isset($current_user->roles[0]) and $current_user->roles[0]=='administrator'){
							return true;
					}		
					 $package_id=get_user_meta($userId,'ep_fitness_package_id',true);
					 
					 $access=get_post_meta($package_id, 'ep_fitness_package_'.$arg, true);
						if($access=='yes'){
							return true;
						}else{
							return false;
						}
				} 
				public function check_reading_access($arg='',$id=0){
					 global $post;
					  
					 $current_user = wp_get_current_user();
					 $userId=$current_user->ID;
					 if($id>0){
						$post = get_post($id);
					 }			 
					 
					 if($post->post_author==$userId){
						 return true;
					 }
					 $package_id=get_user_meta($userId,'ep_fitness_package_id',true);					 
					 $access=get_post_meta($package_id, 'ep_fitness_package_'.$arg, true);
					 
					 					
					  $active_module=get_option('_ep_fitness_active_visibility'); 
					 
						if($active_module=='yes' ){		
											
								if(isset($current_user->ID) AND $current_user->ID!=''){
									$user_role= $current_user->roles[0];
									if(isset($current_user->roles[0]) and $current_user->roles[0]=='administrator'){
										return true;
									}																
								}else{							
									$user_role= 'visitor';
								}	
								
								$store_array=get_option('_iv_visibility_serialize_role');	
								//echo'<pre>';
								//echo $user_role;
								//print_r($store_array);

								 if(isset($store_array[$user_role]))
								{	
									if(in_array($arg, $store_array[$user_role])){
										return true;
									}else{
										
										return false;
									}
									
								}else{ 
										
										return false;
								
								}
								
								
							}else{
								return true;
							}
					
								
				}
				
			}
	}


/*
 * Creates a new instance of the BoilerPlate Class
*/
function ep_fitnessBootstrap() {
	return wp_ep_fitness::instance();
}

ep_fitnessBootstrap(); ?>
