<?php
/**
* Plugin Name: Custom Ticket Codes For Tickera
* Plugin URI:  https://github.com/nicufarmache
* Description: More options to customize Tickera ticket codes 
* Version:     1.0
* License:     GPL v2 or later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Author:      Nicu Farmache
* Author URI:  https://github.com/nicufarmache
* Text Domain: custom-ticket-codes-for-tickera
* Domain Path: /languages
* 
* @package         Custom_Ticket_Codes_For_Tickera
*/

// tc_ticket_code
// tc_rand_api_key_length

register_activation_hook(__FILE__, 'ctcft_activate');
register_deactivation_hook(__FILE__, 'ctcft_deactivate');
register_uninstall_hook(__FILE__, 'ctcft_uninstall');

function ctcft_activate() { 
}

function ctcft_deactivate() {
}

function ctcft_uninstall() {
}



function ctcft_options_page_html() {
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
      <?php
      // output security fields for the registered setting "wporg_options"
      settings_fields( 'wporg_options' );
      // output setting sections and their fields
      // (sections are registered for "wporg", each field is registered to a specific section)
      do_settings_sections( 'wporg' );
      // output save settings button
      submit_button( __( 'Save Settings', 'textdomain' ) );
      ?>
    </form>
  </div>
  <?php
}

// Menu entry

add_action( 'admin_menu', 'wporg_options_page' );
function wporg_options_page() {
    add_menu_page(
        'Ticket Codes',
        'Custom Ticket Codes WPOrg Options',
        'manage_options',
        'ctcft',
        'ctcft_options_page_html',
        'dashicons-tickets-altg',
        20
    );
}


add_filter( 'tc_ticket_code', 'ctcft_modify_ticket_code' );
function ctcft_modify_ticket_code($code) {
    return $code . '-' . rand(10000, 99999);
}

// add_filter( 'tc_rand_api_key_length', 'ctcft_modify_api_key_length' );
function ctcft_modify_api_key_length($code) {
  return 12;
}


