<?php
/**
 * Plugin Name: A Universal Style
 * Description: A Universal syle panel
 */

// Define plugin apc_define_constants
define('UNIVERSALFILE', plugin_dir_url( __DIR__ ).'css/universal-stylesheet.css');
define('UNIVERSALLOCATION', plugins_url('css/universal-stylesheet.css', __DIR__ ));
define('STYLELOCATION', ABSPATH . 'wp-content/plugins/a-universal-panel/css/universal-stylesheet.css' );

error_log('ABSPATH');
error_log(ABSPATH);

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

    <textarea name="universal_stylesheet" id="universal_stylesheet" cols="80" rows="10"><?php echo $style_content; ?></textarea></br>

    <button id="saveUniversal">Save Styles</button>
    <div id="saving" class="hidden"> .. Saving ..</div>
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

  error_log( 'save universal'  );
	$sent_styles = $_POST['styles'];
  error_log( print_r( $sent_styles, true ) );
	// Update our options
  // $dynamic_css_location = plugin_dir_url( __DIR__ ).'css/universal-stylesheet.css';
  error_log('dynamic lcoation');
  error_log(UNIVERSALLOCATION);
  $file_present = check_file();

  if (!$file_present) {
    // delete file
    error_log('file present');
  }

  // Create new file
  $stylesheet = fopen(STYLELOCATION, "w");
  fwrite($stylesheet, $sent_styles);
  fclose($stylesheet);
  chmod(STYLELOCATION, 0644);


  // Return Success

  echo 'Success';

	wp_die(); // this is required to terminate immediately and return a proper response
}
add_action( 'wp_ajax_save_universal', 'save_universal' );

function check_file() {
  $file_exists = false;
  if (file_exists(STYLELOCATION)) {
    $file_exists = true;
  }
  return $file_exists;
}

function create_file($new_styles) {
  // create new file with new styles
  //
}

function cr_enqueue_dynamic_stylesheet(){
  if (true === check_file() ) {
    wp_enqueue_style( 'universal-style', plugins_url( 'css/universal-stylesheet.css', __FILE__ ) );
  }
}
add_action( 'wp_enqueue_scripts', 'cr_enqueue_dynamic_stylesheet' );
