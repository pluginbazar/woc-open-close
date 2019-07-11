<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access



add_shortcode( 'test___p', function() {

	ob_start();

	global $wooopenclose;

	echo "<pre>"; print_r( $wooopenclose->get_next_time( 'close') ); echo "</pre>";


	return ob_get_clean();
} );


if ( ! function_exists( 'woc_get_template_part' ) ) {
	function woc_get_template_part( $slug, $name = '', $args = array() ) {

		global $wooopenclose;

		$template = '';

		// Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php.
		if ( $name ) {
			$template = locate_template( array(
				"{$slug}-{$name}.php",
				$wooopenclose->template_path() . "{$slug}-{$name}.php"
			) );
		}

		// Get default slug-name.php.
		if ( ! $template && $name && file_exists( $wooopenclose->plugin_path() . "/templates/{$slug}-{$name}.php" ) ) {
			$template = $wooopenclose->plugin_path() . "/templates/{$slug}-{$name}.php";
		}

		// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php.
		if ( ! $template ) {
			$template = locate_template( array( "{$slug}.php", $wooopenclose->template_path() . "{$slug}.php" ) );
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'woc_filters_get_template_part', $template, $slug, $name );

		if ( $template ) {
			load_template( $template, false );
		}
	}
}


if ( ! function_exists( 'woc_get_template' ) ) {
	function woc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		$located = woc_locate_template( $template_name, $template_path, $default_path );

		if ( ! file_exists( $located ) ) {
			return new WP_Error( 'invalid_data', __( '%s does not exist.', 'woc-open-close' ), '<code>' . $located . '</code>' );
		}

		$located = apply_filters( 'woc_filters_get_template', $located, $template_name, $args, $template_path, $default_path );

		do_action( 'woc_before_template_part', $template_name, $template_path, $located, $args );

		include $located;

		do_action( 'woc_after_template_part', $template_name, $template_path, $located, $args );
	}
}


if ( ! function_exists( 'woc_locate_template' ) ) {
	function woc_locate_template( $template_name, $template_path = '', $default_path = '' ) {

		global $wooopenclose;

		if ( ! $template_path ) {
			$template_path = $wooopenclose->template_path();
		}

		if ( ! $default_path ) {
			$default_path = $wooopenclose->plugin_path() . '/templates/';
		}

		// Look within passed path within the theme - this is priority.
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		// Get default template/.
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'woc_filters_locate_template', $template, $template_name, $template_path );
	}
}


if ( ! function_exists( 'woc_status_bar_classes' ) ) {
	function woc_status_bar_classes( $echo = false ) {

		$classes       = array();
		$woc_bar_where = get_option( 'woc_bar_where', 'woc-bar-footer' );
		$woc_bar_where = empty( $woc_bar_where ) ? 'woc-bar-footer' : $woc_bar_where;

		$classes[] = $woc_bar_where;

		if ( $echo ) {
			echo implode( ' ', apply_filters( 'woc_filters_status_bar_classes', $classes ) );
		} else {
			return apply_filters( 'woc_filters_status_bar_classes', $classes );
		}
	}
}


if( ! function_exists( 'woc_get_option' ) ) {
	/**
	 * Get Option value
	 *
	 * @param string $option_key
	 * @param string $default_val
	 *
	 * @return mixed|string|void
	 */
	function woc_get_option( $option_key = '', $default_val = '' ) {

		if ( empty( $option_key ) ) {
			return '';
		}

		$option_val = get_option( $option_key, $default_val );
		$option_val = empty( $option_val ) ? $default_val : $option_val;

		return apply_filters( 'woc_filters_option_' . $option_key, $option_val );
	}
}


if ( ! function_exists( 'woc_is_open' ) ) {
	function woc_is_open() {
		global $wooopenclose;

		return $wooopenclose->is_open();
	}
}
