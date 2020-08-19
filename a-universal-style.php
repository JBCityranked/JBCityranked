<?php
/**
 * Plugin Name: A Univeral Style
 * Description: A universal syle panel
 */
 /**
  * Register a custom menu page.
  */
 function wpdocs_register_my_custom_menu_page(){
     add_menu_page(
         'Universal Style Sheet',
         'Universal Style Sheet',
         'manage_options',
         'cr-universal-stylesheet',
         'universal_stylesheet_render',
         'dashicons-edit',
         1
     );
 }
 add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/**
 * Render the Style Admin Panel
 * @return void
 */
function universal_stylesheet_render() {
  echo 'Universal Style Sheet Render <br>';

  echo '<textarea name="universal_stylesheet" id="universal_stylesheet" cols="80" rows="10"></textarea></br>';

  echo '<button id="saveUniversal">Save Styles</button>';
}

add_action( 'wp_ajax_save_universal', 'save_universal' );

/**
 * Create Ajax to Save our Universal Styles
 * @return void
 */
function save_univeral_ajax_enqueue() {
    $localization_array = array(
      '_nonce'  =>  wp_create_nonce(),
      'action'  =>  'save_universal',
      'url'     =>  site_url()
    );

    wp_enqueue_script( 'universal-ajax', plugins_url('/js/save_universal.js', __FILE__ ), array( 'jquery' ) );
}
add_action( 'admin_enqueue_scripts', 'save_univeral_ajax_enqueue' );


/**
 * Ajax Call back - Where we save our Styles
 * @return [type] [description]
 */
function save_universal() {
	global $wpdb; // this is how you get access to the database

	$sent_stylees = $_POST['styles'];

	// Update our options

  // Return Success

  echo 'Success';

	wp_die(); // this is required to terminate immediately and return a proper response
}
