<?php
/**
 * Plugin Name: A Universal Style
 * Description: A plugin to generate network wide styles for multisite WordPress instllations
 * Author: Josh Brown @ City Ranked Media
 * Author URI: https://cityranked.com
 * Version:           0.1.0
 * Requires at least: 5.5
 * Requires PHP:      7.2
 */

// Define plugin apc_define_constants
define('UNIVERSALFILE', plugin_dir_url( __DIR__ ).'css/universal-stylesheet.css');
define('UNIVERSALLOCATION', plugins_url('css/universal-stylesheet.css', __DIR__ ));
define('STYLELOCATION', ABSPATH . 'wp-content/plugins/a-universal-panel/css/universal-stylesheet.css' );

 /**
  * Register a custom menu page: Register the Style Panel Admin Callback
  * @return void
  */
 function cruniversal_register_my_custom_menu_page(){
     add_menu_page(
         'Universal Style Sheet',
         'Universal Style Sheet',
         'manage_options',
         'cr-universal-stylesheet',
         'universal_stylesheet_render', // Name the callback function
         'dashicons-edit',
         1
     );
 }
 add_action( 'admin_menu', 'cruniversal_register_my_custom_menu_page' );

/**
 * Render the Style Admin Panel - The Style Panel Admin Callback
 * @return void
 */
function universal_stylesheet_render() {
  $style_content = file_get_contents(STYLELOCATION);
  ob_start();
?>
  <div id="universal-style-wrapper">
    <h3>Universal Stylesheet Render</h3>

    <textarea name="universal_stylesheet" id="universal_stylesheet" cols="80" rows="10"><?php echo esc_html($style_content); ?></textarea></br>

    <button id="saveUniversal">Save Styles</button>
    <div id="saving" class="hidden"> .. Saving ..</div>
    <div id="success" class="hidden">Save Complete!</div>
    <div id="error" class="hidden"> .. Error .. </div>
    <div id="error_message" class="hidden"></div>
  </div>
  <?php
  echo ob_get_clean();
}


/**
 * Register our javascript file - Pass security variables to that file
 * @return void
 */
function save_univeral_ajax_enqueue() {

    // Create an array of information we want to deliver to the jQuery
    // Our 'packed lunch'
    $localization_array = array(
      '_nonce'  =>  wp_create_nonce('save_universal'),
      'action'  =>  'save_universal',
      'url'     =>  site_url(),
      '_ajax_url' => admin_url( 'admin-ajax.php' ),

    );
    // Register our jQuery script with WordPress - an introduction
    wp_enqueue_script(
      'universal-ajax', // ID
      plugins_url('/js/save_universal.js', __FILE__ ), // Script Location
      array( 'jquery' ) // Dependencies
    );

    // Register the picnic basket and our 'packed lunch'
    wp_localize_script(
      'universal-ajax', // ID
      '_universal',      // Picnic Basket
      $localization_array // Lunch that can be access from the Picnic Basket
    );
    wp_enqueue_style( 'universal_admin_styles', plugins_url( '/css/constant-styles.css', __FILE__ ) );

}
add_action( 'admin_enqueue_scripts', 'save_univeral_ajax_enqueue' );


/**
 * Ajax Call back - Where we save our Styles
 * Save the leftovers to our picnic
 * Return a thank you note
 * @return string tell them it was a success
 */
function save_universal() {
  check_ajax_referer( 'save_universal', '_ajax_nonce' );

  if ( current_user_can( 'manage_options' ) ) {

    $return_object = (object) array(
      'error' => false,
      'errorMessage'  => false,
      'success' => false
    );
    $sent_parcel = isset($_POST['styles']) ? sanitize_textarea_field( $_POST['styles'] ) : '';
    if ('' != $sent_parcel) {
      $raw_styles = wp_unslash( $sent_parcel );
      // Get returned styles
      $sent_styles = wp_strip_all_tags($raw_styles);
    }

    $file_present = check_file();

    if ($file_present) {
      // delete stylesheet to begin again
      unlink(STYLELOCATION);
    }

    // Create stylesheet
    $stylesheet = fopen(STYLELOCATION, "w");
    // Report if there was error creating stylesheet
    if (false === $stylesheet) {
      $return_object->error = true;
      $return_object->errorMessage = 'Error creating the stylehseet file';
    } else {
      // If no error continue to write stylesheet
      $write_report = fwrite($stylesheet, $sent_styles);
      if (false === $write_report) {
        $return_object->error = true;
        $return_object->errorMessage = 'Error writing to stylesheet file';
      }
      fclose($stylesheet);
      chmod(STYLELOCATION, 0644);
      // Return Success
      $return_object->success = true;
    }
    echo json_encode($return_object);

  	wp_die(); // this is required to terminate immediately and return a proper response
  }
}
add_action( 'wp_ajax_save_universal', 'save_universal' );

/**
 * Check if the dynamic universal stylesheet exits
 * @return boolean
 */
function check_file() {
  $file_exists = false;
  if (file_exists(STYLELOCATION)) {
    $file_exists = true;
  }
  return $file_exists;
}

function cr_enqueue_dynamic_stylesheet(){
  if (true === check_file() ) {
    wp_enqueue_style( 'universal-style', plugins_url( 'css/universal-stylesheet.css', __FILE__ ) );
  }
}
add_action( 'wp_enqueue_scripts', 'cr_enqueue_dynamic_stylesheet' );
