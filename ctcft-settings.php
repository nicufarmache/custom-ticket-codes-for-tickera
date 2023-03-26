<?php

add_action( 'admin_menu', 'ctcft_settings_init' );
function ctcft_settings_init() {
  // Register settings
	register_setting( 'ctcft', 'ctcft_code_template' );
  register_setting( 'ctcft', 'ctcft_api_key_length' );

  // Titcket Code

  // Add page section
  add_settings_section(
		'ctcft_section_template',
		'Ticket Code', 
		'ctcft_section_template_callback',
		'ctcft'
	);

  // Add field in section
	add_settings_field(
		'ctcft_field_template', // id -- As of WP 4.6 this value is used only internally. Use $args' label_for to populate the id inside the callback.
	  'Ticket code template', // title
		'ctcft_field_template_cb',
		'ctcft',
		'ctcft_section_template',
		array(
			'label_for'         => 'ctcft_field_template',
			'class'             => 'ctcft_row',
			'ctcft_custom_data' => 'custom',
		)
	);

  // Key length

  // Add page section
  add_settings_section(
		'ctcft_section_key',
		'API Key', 
		'ctcft_section_key_callback',
		'ctcft'
	);

  // Add field in section
	add_settings_field(
		'ctcft_field_key', // id -- As of WP 4.6 this value is used only internally. Use $args' label_for to populate the id inside the callback.
	  'API key lenght', // title
		'ctcft_field_key_cb',
		'ctcft',
		'ctcft_section_key',
		array(
			'label_for'         => 'ctcft_field_key',
			'class'             => 'ctcft_row',
			'ctcft_custom_data' => 'custom',
		)
	);
}

// Render section description
function ctcft_section_template_callback( $args ) {
	?>
	<p>
  <?php esc_html_e( 'The elements that you can use in the template are:', 'ctcft' ); ?>
    <ul style="margin-top: 0;">
      <li><code>[order]</code> - <?php esc_html_e( 'Order ID (Everything before the "-" charater in the original code)', 'ctcft' ); ?></li>
      <li><code>[nr]</code> - <?php esc_html_e( 'Ticket number (Everything after the "-" charater in the original code)', 'ctcft' ); ?></li>
      <li><code>[x]</code> - <?php esc_html_e( 'Random digit (0 to 9)', 'ctcft' ); ?></li>
      <li><code>[default]</code> - <?php esc_html_e( 'The original code without any change', 'ctcft' ); ?></li>
      <li><?php esc_html_e( 'Any other text will be sown as is', 'ctcft' ); ?></li>
    </ul>
  </p>
  <p>
    <?php esc_html_e( 'For example: ', 'ctcft' ); ?> <code>TEXT-[order]-[nr]-[x][x][x][x][x]</code> <?php esc_html_e( ' will generate something like: ', 'ctcft' ); ?> <code>TEXT-12345-1-90401</code>
  </p>
  <p>
  <?php esc_html_e( 'To be a valid template either both ', 'ctcft' ); ?><code>[order]</code> and <code>[nr]</code> <?php esc_html_e( 'or alternatively just', 'ctcft' ); ?> <code>[default]</code> are required to be included.
  </p>
	<?php
}

function ctcft_section_key_callback( $args ) {
	?>
	<p>
    <?php esc_html_e( 'Set the API key length for new keys', 'ctcft' ); ?>
  </p>
	<?php
}

// Render setting form field
function ctcft_field_template_cb( $args ) {
	// Get the value of the setting we've registered with register_setting()
  list(
    'default_template_value' => $default_template_value,
    'regex' => $regex
  ) = ctcft_get_vars();
	$code_template = get_option('ctcft_code_template', $default_template_value);
	?>
  <input 
    type="text"
    class="regular-text"
    pattern="<?php echo esc_attr( $regex ); ?>"
    required
    id="<?php echo esc_attr( $args['label_for'] ); ?>"
    name="ctcft_code_template"
    value="<?php echo esc_attr( $code_template ) ?>">
	<p class="description">
    <?php esc_html_e( 'Sample code: ', 'ctcft' ); ?> 
    <code id="<?php echo esc_attr( $args['label_for'] ); ?>-output"></code>
	</p>
  <p class="description" style="display: none;">
    <?php esc_html_e( 'Backend sample code:  ', 'ctcft' ); ?> 
    <code><?php echo esc_attr( ctcft_modify_ticket_code('12345-1') ); ?></code>
	</p>
  <p class="description">
	</p>
  <script>
    {
      const input = document.getElementById('<?php echo esc_attr( $args['label_for'] ); ?>');
      const output = document.getElementById('<?php echo esc_attr( $args['label_for'] ); ?>-output');
      const ctcftSampleGenerator = (e) => {
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
      };
      
      input.addEventListener('input', ctcftSampleGenerator);
      ctcftSampleGenerator();
    };
    
  </script>
	<?php
}

function ctcft_field_key_cb( $args ) {
  list('default_key_length_value' => $default_key_length_value) = ctcft_get_vars();
  $length = get_option('ctcft_api_key_length', $default_key_length_value);
	?>
  <input 
    type="number"
    class="small-text"
    min="8"
    max="20"
    required
    id="<?php echo esc_attr( $args['label_for'] ); ?>"
    name="ctcft_api_key_length"
    value="<?php echo esc_attr( $length ) ?>">
	<?php
}

// Add menu entry
add_action( 'admin_menu', 'ctcft_options_page' );
function ctcft_options_page() {
    add_menu_page(
        'Custom Ticket Code Options',
        'Ticket Code',
        'manage_options',
        'ctcft',
        'ctcft_options_page_html',
        'dashicons-tickets-alt',
        20
    );
    add_submenu_page(
        'edit.php?post_type=tc_events',
        'Custom Ticket Code Options',
        'Custom Ticket Code',
        'manage_options',
        'ctcft',
        'ctcft_options_page_html',
    );
}

// Render Settings page
function ctcft_options_page_html() {
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
      <?php
      // output security fields for the registered setting "ctcft_options"
      settings_fields( 'ctcft' );
      // output setting sections and their fields
      // (sections are registered for "ctcft", each field is registered to a specific section)
      do_settings_sections( 'ctcft' );
      // output save settings button
      submit_button( __( 'Save Settings', 'textdomain' ) );
      ?>
    </form>
  </div>
  <?php
}