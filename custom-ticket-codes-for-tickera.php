<?php
/**
* Plugin Name: Custom Ticket Codes For Tickera
* Plugin URI:  https://github.com/nicufarmache
* Description: More options to customize Tickera ticket codes 
* Version:     1.1
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

include 'ctcft-vars.php';
include 'ctcft-filter-function.php';
include 'ctcft-filter-api-key-lenght.php';
include 'ctcft-settings.php';

register_activation_hook(__FILE__, 'ctcft_activate');
register_deactivation_hook(__FILE__, 'ctcft_deactivate');
register_uninstall_hook(__FILE__, 'ctcft_uninstall');

function ctcft_activate() { 
  list(
    'default_template_value' => $default_template_value,
    'default_key_length_value' => $default_key_length_value
  ) = ctcft_get_vars();
  add_option('ctcft_code_template', $default_template_value, '', false);
  add_option('ctcft_api_key_length', $default_key_length_value, '', false);
}

function ctcft_deactivate() {
  delete_option('ctcft_code_template');
  delete_option('ctcft_api_key_length');
}

function ctcft_uninstall() {
  delete_option('ctcft_code_template');
  delete_option('ctcft_api_key_length');
}

add_filter( 'tc_ticket_code', 'ctcft_modify_ticket_code' );
add_filter( 'tc_rand_api_key_length', 'ctcft_modify_api_key_length' );
