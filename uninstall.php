<?php
/**
 * Delete our one option on uninstall!
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'conversioncow_options' );
