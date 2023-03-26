<?php

function ctcft_get_vars() {
  return [
    'default_template_value' => '[default]',
    'default_key_length_value' => '8',
    'regex' => '.*((\[order\].*\[nr\])|(\[nr\].*\[order\])|(\[default\])).*',
  ];
}


