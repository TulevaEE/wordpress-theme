<?php
/**
 * Plugin Name: Tuleva local dev
 * Description: Local-only behaviour: disables production plugins and blocks outgoing mail.
 *
 * Lives in mu-plugins/ so it always loads, cannot be switched off in wp-admin,
 * and survives re-importing the database.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Neutralise production-only plugins without writing to the database.
 *
 * README steps 8 and 9 asked people to hand-edit the serialized active_plugins
 * array — delete one entry from a:24:{i:0;s:19:"...";...} and both the element
 * count and every following integer key are wrong, which silently deactivates
 * everything. Filtering at read time is idempotent, survives a fresh import,
 * and cannot fail when the plugin files are absent.
 */
add_filter( 'option_active_plugins', static function ( $plugins ) {
	if ( ! is_array( $plugins ) ) {
		return $plugins;
	}

	$disabled = defined( 'TULEVA_DISABLED_PLUGINS' )
		? array_map( 'trim', explode( ',', TULEVA_DISABLED_PLUGINS ) )
		: array( 'really-simple-ssl', 'wordfence' );

	return array_values(
		array_filter(
			$plugins,
			static function ( $plugin_file ) use ( $disabled ) {
				return ! in_array( strtok( $plugin_file, '/' ), $disabled, true );
			}
		)
	);
}, PHP_INT_MAX );

/**
 * Serve missing media from production instead of bundling gigabytes of uploads.
 *
 * The export skips wp-content/uploads (several GB), so image, PDF and font URLs
 * that WordPress emits point at files that do not exist locally. When a request
 * for one 404s, redirect the browser to the same path on tuleva.ee. It is a 302,
 * not a proxy: the browser fetches the file directly, so nothing streams through
 * this container. If a file DOES exist locally (e.g. WITH_UPLOADS=1), it is
 * served normally and this never fires.
 */
add_action( 'template_redirect', static function () {
	// Gate on the file being missing (below), not is_404(): WordPress routes a
	// missing static upload to index.php and often returns 200, not 404, so an
	// is_404() check would never fire.
	if ( ! defined( 'TULEVA_UPLOADS_FALLBACK' ) ) {
		return;
	}

	$path = parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
	if ( ! is_string( $path ) || ! preg_match( '#/wp-content/uploads/(.+)$#', urldecode( $path ), $m ) ) {
		return;
	}

	$relative = ltrim( $m[1], '/' );
	if ( str_contains( $relative, '..' ) ) {
		return;
	}

	$uploads = wp_get_upload_dir();
	if ( file_exists( trailingslashit( $uploads['basedir'] ) . $relative ) ) {
		return;
	}

	nocache_headers();
	wp_redirect( trailingslashit( TULEVA_UPLOADS_FALLBACK ) . $relative, 302 );
	exit;
}, 1 );

/**
 * Never send real email from a laptop running a copy of the production
 * database. Suppressed mail is logged so it is still visible in ./setup.sh --logs.
 */
add_filter( 'pre_wp_mail', static function ( $short_circuit, $atts ) {
	$to = $atts['to'] ?? '';
	error_log( sprintf(
		'Local dev: suppressed outgoing mail: to=%s, subject=%s',
		is_array( $to ) ? implode( ',', $to ) : (string) $to,
		(string) ( $atts['subject'] ?? '' )
	) );

	return true;
}, 10, 2 );

/**
 * Make it impossible to mistake this for production.
 */
add_action( 'admin_notices', static function () {
	echo '<div class="notice notice-warning"><p><strong>Local development copy.</strong> '
		. 'Production-only plugins are disabled and outgoing email is blocked.</p></div>';
} );
