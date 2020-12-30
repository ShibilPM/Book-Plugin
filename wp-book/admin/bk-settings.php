<?php

/**************************************************
* 6. Create a custom admin settings page for Book *
***************************************************/

class create_settings_page
{

	function __construct() {

		add_action( 'admin_menu', array( $this, 'bk_submenu_settings' ) );
		add_action( 'admin_init', array( $this, 'bk_submenu_settings_init' ) );

	}

	function bk_submenu_settings() {

  	add_submenu_page(
    	'edit.php?post_type=books', //custom post type name
    	'Book Settings', //title
    	'Book Settings', //name
    	'manage_options', // access
    	'book_settings', //slug
    	array( $this, 'bk_submenu_settings_callback' ) // call back function
  	);

	}


	function bk_submenu_settings_callback() {

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<form action= "options.php" method="post">
				<?php
					//security field
					settings_fields( 'book_settings' );

					//output settings section
					do_settings_sections( 'book_settings' );

					// save settings
					submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php

	}

	//settings template
	function bk_submenu_settings_init() {

		// setup the setting section
		add_settings_section(
			'bk_settings_section',
			'Book Settings Page',
			'',
			'book_settings'
		);

		//Register input fields
		register_setting(
			'book_settings',
			'bk_settings_input_field',
			array(
				'type' => '',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => ''
			)
		);

		//Add settings field
		add_settings_field(
			'bk_settings_input_field',
			__( 'Post Per Page' ),
			array( $this, 'bk_settings_input_field_callback' ),
			'book_settings',
			'bk_settings_section'
		);

		//Register select fields
		register_setting(
			'book_settings',
			'bk_settings_select_field',
			array(
				'type' => '',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => ''
			)
		);

		//Add settings field
		add_settings_field(
			'bk_settings_select_field',
			__( 'Select Currency' ),
			array( $this, 'bk_settings_select_field_callback' ),
			'book_settings',
			'bk_settings_section'
		);
	}

	//settings input field template
	function bk_settings_input_field_callback() {
		$bk_input_field = get_option( 'bk_settings_input_field' );
		?>
		<input type="number" name="bk_settings_input_field" value="<?php echo isset( $bk_input_field ) ? esc_attr( $bk_input_field ) : ''; ?>" />
		<?php
	}

	//settings option field template
	function bk_settings_select_field_callback() {
    $bk_select_field = get_option( 'bk_settings_select_field' );
		?>
		<select class="text-field" name="bk_settings_select_field">
			<option value="">Select Currency</option>
			<option value="option1" <?php selected( 'option1', $bk_select_field ) ?> >Rs.</option>
			<option value="option2" <?php selected( 'option2', $bk_select_field ) ?> >U.S. dollar</option>
			<option value="option3" <?php selected( 'option3', $bk_select_field ) ?> >Euro</option>
		</select>
		<?php

	}

}

$settingsPage = new create_settings_page();
