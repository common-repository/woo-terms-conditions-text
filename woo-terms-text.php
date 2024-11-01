<?php

/*
Plugin Name: Woo Terms & Conditions Text
Plugin URI: https://raajtram.com/plugins/wtc
Description: Allows you to change the Terms & Conditions text for WooCommerce.
Version: 1.0.3
Author: Raaj Trambadia
Author URI: https://raajtram.com/
*/

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );

/* Register Settings */

function wtc_init() {
	register_setting( 'wtc_settings_group', 'wtc_settings' );

	add_settings_section(
		'wtc_section',
		'',
		'wtc_cb',
		'woo-terms-text'
	);

	add_settings_field(
		'wtc_front',
		'Terms Text',
		'wtc_front_cb',
		'woo-terms-text',
		'wtc_section'
	);

}

add_action( 'admin_init', 'wtc_init');

/* Callback */

function wtc_cb() {}

/* Callback */

function wtc_front_cb() {
	$options = get_option( 'wtc_settings' );
	if( !isset( $options['wtc_new_text'] ) ) $options['wtc_new_text'] = '';
	echo '<input type="text" name="wtc_settings[wtc_new_text]" value="' . esc_attr( $options['wtc_new_text'] ) . '" placeholder="e.g. I have read and agree to the Return Policy and the Terms and Conditions of this store." style="width: 100%;">';
}

/* Options - HTML */

function wtc_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

		?>

		<div class="wrap wtc_options">
			<h2>Woo Terms and Conditions Text - Settings </h2>
			<p>Add your custom Terms and Conditions text below, and it will replace the default text by added WooCommerce. Use HTML to add links.</p>
			<p>Link example: <code>&#x3C;a href=&#x22;https://YourURL.com&#x22;&#x3E;Link Text&#x3C;/a&#x3E;</code></p>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'wtc_settings_group' );
					do_settings_sections( 'woo-terms-text' );
				submit_button();
			?>
		</div>

		<?php

}

/* Admin Menu */

function wtc_menu() {
	add_options_page(
		'Woo Terms and Conditions Text',
		'Woo Terms Text',
		'manage_options',
		'woo-terms-text',
		'wtc_options'
	);
}

add_action( 'admin_menu', 'wtc_menu' );

/* Core function */

function wtc_text_strings( $translated_text, $text, $domain ) {

	global $post;
	global $woocommerce;
	$options = get_option( 'wtc_settings' );
	$wtc_terms_text = !empty( $options['wtc_new_text'] ) ? $options['wtc_new_text'] : 'I&rsquo;ve read and accept the terms &amp; conditions';

	switch ( $translated_text ) {
			case 'I&rsquo;ve read and accept the <a href="%s" target="_blank" class="woocommerce-terms-and-conditions-link">terms &amp; conditions</a>' :
				$translated_text = __( '' . $wtc_terms_text . '', 'woocommerce' );
				break;
		}
		return $translated_text;
}
add_filter( 'gettext', 'wtc_text_strings', 20, 3 );
