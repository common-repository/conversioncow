<?php
/**
 * Plugin Name:       ConversionCow
 * Plugin URI:        https://conversioncow.com
 * Description:       Easily embed your ConversionCow campaigns on your WordPress website.
 * Version:           1.1.1
 * Author:            ConversionCow
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       conversioncow
 * Domain Path:       /languages
 */

// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar

if ( ! defined( 'WPINC' ) ) {
	die;
}

// -------------------------------------------------------------------------------------------------
// Plugin Constants
// -------------------------------------------------------------------------------------------------

define( 'CONVERSIONCOW_VERSION', '1.1.1' );
define( 'CONVERSIONCOW_OPTION_NAME', 'conversioncow_options' );

// -------------------------------------------------------------------------------------------------
// Plugin Init
// -------------------------------------------------------------------------------------------------

add_action( 'plugins_loaded', 'conversioncow_load_plugin' );
/**
 * Load the text domain.
 */
function conversioncow_load_plugin() {
	load_plugin_textdomain(
		'conversioncow',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '\/languages\/'
	);
}

// -------------------------------------------------------------------------------------------------
// Plugin Core
// -------------------------------------------------------------------------------------------------

add_action( 'wp_head', 'conversioncow_wp_head' );
/**
 * Embed both the unique user code and the popout script in the footer.
 */
function conversioncow_wp_head() {
	$options = get_option( CONVERSIONCOW_OPTION_NAME );
	if ( isset( $options['user_code'] ) ) {
		echo '<script>' . PHP_EOL;
		echo '!function(c,o,w){c[o]=function(){(c[o].w=c[o].w||[]).push(arguments)};var d=document,s=d.createElement(w);s.type="text/javascript",s.async=!0,s.src="https://cdn.conversioncow.io/prod/bundle.min.js";var e=d.getElementsByTagName(w)[0];e.parentNode.insertBefore(s,e)}(window,"ConversionCow","script");' . PHP_EOL;
		echo 'ConversionCow("init", "' . esc_attr( $options['user_code'] ) . '");' . PHP_EOL;
		echo '</script>' . PHP_EOL;
	}
}

// -------------------------------------------------------------------------------------------------
// Plugin Admin
// -------------------------------------------------------------------------------------------------

add_action( 'admin_menu', 'conversioncow_add_settings_page' );
/**
 * Add our plugin's options page.
 */
function conversioncow_add_settings_page() {
	add_options_page(
		__( 'ConversionCow', 'conversioncow' ),
		__( 'ConversionCow', 'conversioncow' ),
		'manage_options',
		'conversioncow',
		'conversioncow_render_plugin_settings_page'
	);
}

add_action( 'admin_init', 'conversioncow_register_settings' );
/**
 * Register our one setting on the options page.
 */
function conversioncow_register_settings() {
	register_setting(
		'conversioncow_options',
		CONVERSIONCOW_OPTION_NAME,
		array(
			'sanitize_callback' => 'conversioncow_plugin_options_validate',
		)
	);
	add_settings_section(
		'api_settings',
		__( 'Global Settings', 'conversioncow' ),
		'conversioncow_plugin_section_text',
		'conversioncow'
	);
	add_settings_field(
		'conversioncow_plugin_setting_user_code',
		__( 'User Code', 'conversioncow' ),
		'conversioncow_plugin_setting_user_code',
		'conversioncow',
		'api_settings'
	);
}

/**
 * Text displayed immediately under the settings title.
 */
function conversioncow_plugin_section_text() {
	echo '<p>' . esc_html__( 'Enter your user code from the ConversionCow portal below.', 'conversioncow' ) . '</p>';
	echo '<p><a href="https://conversioncow.io/install">' . esc_html__( 'Click here to access your user code.', 'conversioncow' ) . '</a></p>';
}

/**
 * Render our one setting.
 */
function conversioncow_render_plugin_settings_page() {
	?>
	<h1><?php esc_html_e( 'ConversionCow', 'conversioncow' ); ?></h1>
	<form action="options.php" method="post">
		<?php
		settings_fields( 'conversioncow_options' );
		do_settings_sections( 'conversioncow' );
		?>
		<input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save', 'conversioncow' ); ?>" />
	</form>
	<?php
}

/**
 * Validate our one setting.
 *
 * @param array $input Array of setting inputs.
 */
function conversioncow_plugin_options_validate( $input ) {
	$input['user_code'] = trim( $input['user_code'] );

	return $input;
}

/**
 * Render the input for our one setting.
 */
function conversioncow_plugin_setting_user_code() {
	$options = get_option( CONVERSIONCOW_OPTION_NAME );
	echo '<input id="conversioncow_plugin_setting_user_code" name="conversioncow_options[user_code]" type="text" value="' . esc_attr( $options['user_code'] ) . '" />';
}
