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

function universal_stylesheet_render() {
  echo 'Universal Style Sheet Render <br>';

  echo '<textarea name="" id="" cols="30" rows="10"></textarea>';
}
