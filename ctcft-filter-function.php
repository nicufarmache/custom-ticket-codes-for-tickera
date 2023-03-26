<?php
/*
    if (!input.validity.valid) {
      output.innerText = 'Template not valid!';
      return;
    }

    const initalCode = '12345-1';
    let text = input.value;
    const [order, nr] = initalCode.split('-');

    text = text.replaceAll('[default]', initalCode);
    text = text.replaceAll('[order]', order);
    text = text.replaceAll('[nr]', nr);
    text = text.replaceAll('[x]', () => Math.floor(Math.random()*10).toString());

    output.innerText = text;
*/

function ctcft_validate_template($template) {
  list('regex' => $regex) = ctcft_get_vars();
  return preg_match('/'.$regex.'/', $template);
}

function ctcft_modify_ticket_code($input) {
  list('default_template_value' => $default_value) = ctcft_get_vars();
  list('regex' => $regex) = ctcft_get_vars();
  $text = get_option('ctcft_code_template', $default_value);

  if(!ctcft_validate_template($text)){
    return $input;
  }
  
  list($order, $nr) = explode('-', $input) + array(null, '');

  $text = str_replace(
    array('[default]', '[order]', '[nr]'),
    array($input, $order, $nr),
    $text
  );

  // return $text;

  $r_text = '';
  foreach(explode('[x]', $text) as $k => $v) {
    $rand = '' . rand(0, 9);
    $r_text .= ($k == 0 ? '' : $rand).$v;
  }
  $text = $r_text;

  return $text;
}