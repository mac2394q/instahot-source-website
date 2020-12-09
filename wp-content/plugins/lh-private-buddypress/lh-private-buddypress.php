<?php
/**
 * Plugin Name: LH Private BuddyPress
 * Description: Protect your BuddyPress Installation from strangers. Only registered users will be allowed to view member and activity pages.
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
 * Plugin URI: https://lhero.org/portfolio/lh-private-buddypress/
 * Version: 1.11
 * Text Domain: lh_private_buddypress
 * Domain Path: /languages
 */
 
 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if (!class_exists('LH_private_buddypress_plugin')) {
 

class LH_private_buddypress_plugin {
    
    private static $instance;
    
static function return_plugin_namespace(){

    return 'lh_private_buddypress';

    } 
    
    
static function curpageurl() {
	$pageURL = 'http';

	if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")){
		$pageURL .= "s";
}

	$pageURL .= "://";

	if (($_SERVER["SERVER_PORT"] != "80") and ($_SERVER["SERVER_PORT"] != "443")){
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];

	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

}

	return $pageURL;
}

static function LoginRequired() {

if (is_front_page() or bp_is_register_page() or bp_is_activation_page() or bp_is_blog_page() ){

$return = false;

} else {

$return = true;

}

return apply_filters('lh_private_buddypress_login_required_check', $return);

		
}

static function IsBuddyPressFeed() {

		// Get BuddyPress
		global $bp;
		
		// Default value
		$isBuddyPressFeed = false;

if (function_exists('is_buddypress') && is_buddypress() && !is_feed('lh-inline-css-to-file')){



		
		// Check if the current BuddyPress page is a feed
if ( $bp->current_action == 'feed' ){
	$isBuddyPressFeed = true;
} elseif (count($bp->action_variables)>0){
	if ($bp->action_variables[0] == 'feed'){
		$isBuddyPressFeed = true;
	}
}

}
		// Return false if no BuddyPress feed has been called
		return apply_filters('lh_private_buddypress_is_buddypress_feed', $isBuddyPressFeed);
	}

    
    
static function handle_auth_required_redirect(){
    
    // Get current position
$redirect_to = apply_filters('lh_private_buddypress_redirect_to_after_login', self::curpageurl());

// Redirect to login page if for current page a is required
$loginPage = apply_filters('lh_private_buddypress_redirect_login_page', add_query_arg(self::return_plugin_namespace().'-login_required','true', wp_login_url( $redirect_to )), $redirect_to);
wp_redirect($loginPage);
exit;
			
    
}


		

	


		


public function force_login_if_required() {


// Check if user is logged in
if (is_user_logged_in() ){

return true;

} else {
    
if (did_action('bp_init') === 0) {

do_action("bp_init");

}


if (self::LoginRequired() or  self::IsBuddyPressFeed() ){
    
self::handle_auth_required_redirect();


		
}

}
}


public function display_login_message($message){
    
  if (!empty($_GET[self::return_plugin_namespace().'-login_required']) && ($_GET[self::return_plugin_namespace().'-login_required'] == 'true')){
      
      $message = __('To view members content please login', self::return_plugin_namespace());
      
      
      $message = apply_filters(self::return_plugin_namespace().'_display_login_message', $message);
      
      
  }
    
    
 return $message;   
    
}



public function init(){
    
//load translations
load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' ); 

// Add login redirect function
add_action('template_redirect', array($this, 'force_login_if_required'), 2);

//display a message explaining the need to login
add_filter( 'login_message', array($this,'display_login_message'),10,1);


}

    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }




public function __construct() {

add_action( 'bp_include', array($this,'init'));

}

}


// Init the plugin at WordPress startup
$lh_private_buddypress_instance = LH_private_buddypress_plugin::get_instance();

}

?>