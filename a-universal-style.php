<?php
/**
 * Plugin Name: A Universal Style
 * Description: A Universal syle panel
 */
 /**
  * Register a custom menu page.
  */
 function cruniversal_register_my_custom_menu_page(){
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
 add_action( 'admin_menu', 'cruniversal_register_my_custom_menu_page' );

/**
 * Render the Style Admin Panel
 * @return void
 */
function universal_stylesheet_render() {
  echo 'Universal Style Sheet Render <br>';

  echo '<textarea name="universal_stylesheet" id="universal_stylesheet" cols="80" rows="10"></textarea></br>';

  echo '<button id="saveUniversal">Save Styles</button>';
}


/**
 * Create Ajax to Save our Universal Styles
 * @return void
 */
function save_univeral_ajax_enqueue() {
    $localization_array = array(
      '_nonce'  =>  wp_create_nonce('save_universal'),
      'action'  =>  'save_universal',
      'url'     =>  site_url(),
      '_ajax_url' => admin_url( 'admin-ajax.php' ),

    );

    wp_enqueue_script(
      'universal-ajax', // ID
      plugins_url('/js/save_universal.js', __FILE__ ), // Script Location
      array( 'jquery' ) // Dependencies
    );

    wp_localize_script(
      'universal-ajax', // ID
      '_universal',      // Picnic Basket
      $localization_array // Lunch that can be access from the Picnic Basket
    );
}
add_action( 'admin_enqueue_scripts', 'save_univeral_ajax_enqueue' );


/**
 * Ajax Call back - Where we save our Styles
 * @return [type] [description]
 */
function save_universal() {
	global $wpdb; // this is how you get access to the database
  check_ajax_referer( 'save_universal', '_ajax_nonce' );

  error_log( 'save universal'  );
	$sent_stylees = $_POST['styles'];
  error_log( print_r( $sent_stylees, true ) );
	// Update our options

  // Return Success

  echo 'Success';

	wp_die(); // this is required to terminate immediately and return a proper response
}
add_action( 'wp_ajax_save_universal', 'save_universal' );
