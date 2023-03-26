<?php
function ctcft_modify_api_key_length($code) {
  list('default_key_length_value' => $default_key_length_value) = ctcft_get_vars();
  $length = get_option('ctcft_api_key_length', $default_key_length_value);
  return intval($length);
}