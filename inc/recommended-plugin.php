<?php
/**
 * Recommend the companion plugin.
 *
 * A dismissible admin notice on the Dashboard, Themes and Plugins screens while
 * Serif ReadTime & Font Control is not active. No auto-install — just the name,
 * what it adds, and where to get it. Dismissal is per user and permanent.
 *
 * @package Serif
 */

namespace Serif;

const RECOMMENDED_PLUGIN_URL  = 'https://github.com/mgiannopoulos24/Serif-ReadTime-Font-Control';
const RECOMMENDED_PLUGIN_META = 'serif_dismissed_plugin_notice';

/**
 * Whether the companion plugin is active.
 *
 * @return bool
 */
function recommended_plugin_active(): bool {
	return defined( 'SERIF_RTFC_VERSION' );
}

/**
 * Whether to show the notice on the current screen for the current user.
 *
 * @return bool
 */
function should_show_plugin_notice(): bool {
	if ( recommended_plugin_active() || ! current_user_can( 'install_plugins' ) ) {
		return false;
	}
	if ( get_user_meta( get_current_user_id(), RECOMMENDED_PLUGIN_META, true ) ) {
		return false;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	return $screen && in_array( $screen->id, array( 'dashboard', 'themes', 'plugins' ), true );
}

/**
 * Persist a dismissal (link with nonce, no JS required).
 */
function handle_plugin_notice_dismiss() {
	if ( ! isset( $_GET['serif-dismiss-plugin-notice'] ) ) {
		return;
	}
	check_admin_referer( 'serif-dismiss-plugin-notice' );
	update_user_meta( get_current_user_id(), RECOMMENDED_PLUGIN_META, 1 );
	wp_safe_redirect( remove_query_arg( array( 'serif-dismiss-plugin-notice', '_wpnonce' ) ) );
	exit;
}
add_action( 'admin_init', __NAMESPACE__ . '\\handle_plugin_notice_dismiss' );

/**
 * Print the notice.
 */
function render_plugin_notice() {
	if ( ! should_show_plugin_notice() ) {
		return;
	}

	$dismiss_url = wp_nonce_url( add_query_arg( 'serif-dismiss-plugin-notice', '1' ), 'serif-dismiss-plugin-notice' );

	wp_admin_notice(
		sprintf(
			'<p><strong>%1$s</strong> %2$s</p><p><a class="button button-primary" href="%3$s" target="_blank" rel="noopener">%4$s</a> <a class="button" href="%5$s">%6$s</a></p>',
			esc_html__( 'Serif recommends the Serif ReadTime & Font Control plugin.', 'serif' ),
			esc_html__( 'It adds a “minutes to read” estimate to the post meta row and a floating reading-controls widget (text size, line height, contrast) that remembers each reader’s choices.', 'serif' ),
			esc_url( RECOMMENDED_PLUGIN_URL ),
			esc_html__( 'Get the plugin', 'serif' ),
			esc_url( $dismiss_url ),
			esc_html__( 'Dismiss', 'serif' )
		),
		array(
			'type'               => 'info',
			'paragraph_wrap'     => false,
			'additional_classes' => array( 'serif-plugin-notice' ),
		)
	);
}
add_action( 'admin_notices', __NAMESPACE__ . '\\render_plugin_notice' );

/**
 * Show the notice again after (re)activation, even if it was dismissed before.
 */
function reset_plugin_notice_on_switch() {
	delete_user_meta( get_current_user_id(), RECOMMENDED_PLUGIN_META );
}
add_action( 'after_switch_theme', __NAMESPACE__ . '\\reset_plugin_notice_on_switch' );
