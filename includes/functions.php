<?php
/*
* @Author 		pluginbazar
* Copyright: 	2015 pluginbazar
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}  // if direct access

if ( ! function_exists( 'woc_product_can_order' ) ) {
	/**
	 * Check if a product is ready to order or not
	 *
	 * @param bool $product_id
	 *
	 * @return bool|mixed|void
	 */
	function woc_product_can_order( $product_id = false ) {

		$allowed_products    = wooopenclose()->get_option( 'woc_allowed_products', array() );
		$disallowed_products = wooopenclose()->get_option( 'woc_disallowed_products', array() );

		if ( in_array( $product_id, $allowed_products ) ) {
			return true;
		}

		if ( in_array( $product_id, $disallowed_products ) ) {
			return false;
		}

		return woc_is_open();
	}
}


if ( ! function_exists( 'woc_get_template_part' ) ) {
	/**
	 * Get Template Part
	 *
	 * @param $slug
	 * @param string $name
	 * @param array $args
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 */
	function woc_get_template_part( $slug, $name = '', $args = array(), $main_template = false ) {

		$template   = '';
		$plugin_dir = WOC_PLUGIN_DIR;

		/**
		 * Locate template
		 */
		if ( $name ) {
			$template = locate_template( array(
				"{$slug}-{$name}.php",
				"woc/{$slug}-{$name}.php"
			) );
		}

		/**
		 * Check directory for templates from Addons
		 */
		$backtrace      = debug_backtrace( 2, true );
		$backtrace      = empty( $backtrace ) ? array() : $backtrace;
		$backtrace      = reset( $backtrace );
		$backtrace_file = isset( $backtrace['file'] ) ? $backtrace['file'] : '';

		// Search in WOC Pro
		if ( strpos( $backtrace_file, 'woc-open-close-pro' ) !== false && defined( 'WOCP_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? WOC_PLUGIN_DIR : WOCP_PLUGIN_DIR;
		}


		/**
		 * Search for Template in Plugin
		 *
		 * @in Plugin
		 */
		if ( ! $template && $name && file_exists( untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php" ) ) {
			$template = untrailingslashit( $plugin_dir ) . "/templates/{$slug}-{$name}.php";
		}


		/**
		 * Search for Template in Theme
		 *
		 * @in Theme
		 */
		if ( ! $template ) {
			$template = locate_template( array( "{$slug}.php", "woc/{$slug}.php" ) );
		}


		/**
		 * Allow 3rd party plugins to filter template file from their plugin.
		 *
		 * @filter woc_filters_get_template_part
		 */
		$template = apply_filters( 'woc_filters_get_template_part', $template, $slug, $name );


		if ( $template ) {
			load_template( $template, false );
		}
	}
}


if ( ! function_exists( 'woc_get_template' ) ) {
	/**
	 * Get Template
	 *
	 * @param $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 *
	 * @return WP_Error
	 */
	function woc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '', $main_template = false ) {

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		/**
		 * Check directory for templates from Addons
		 */
		$backtrace      = debug_backtrace( 2, true );
		$backtrace      = empty( $backtrace ) ? array() : $backtrace;
		$backtrace      = reset( $backtrace );
		$backtrace_file = isset( $backtrace['file'] ) ? $backtrace['file'] : '';

		$located = woc_locate_template( $template_name, $template_path, $default_path, $backtrace_file, $main_template );


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
	/**
	 *  Locate template
	 *
	 * @param $template_name
	 * @param string $template_path
	 * @param string $default_path
	 * @param string $backtrace_file
	 * @param bool $main_template | When you call a template from extensions you can use this param as true to check from main template only
	 *
	 * @return mixed|void
	 */
	function woc_locate_template( $template_name, $template_path = '', $default_path = '', $backtrace_file = '', $main_template = false ) {

		$plugin_dir = WOC_PLUGIN_DIR;

		/**
		 * Template path in Theme
		 */
		if ( ! $template_path ) {
			$template_path = 'woc/';
		}

		// Check for WOC Pro
		if ( ! empty( $backtrace_file ) && strpos( $backtrace_file, 'woc-open-close-pro' ) !== false && defined( 'WOCP_PLUGIN_DIR' ) ) {
			$plugin_dir = $main_template ? WOC_PLUGIN_DIR : WOCP_PLUGIN_DIR;
		}


		/**
		 * Template default path from Plugin
		 */
		if ( ! $default_path ) {
			$default_path = untrailingslashit( $plugin_dir ) . '/templates/';
		}

		/**
		 * Look within passed path within the theme - this is priority.
		 */
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		/**
		 * Get default template
		 */
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		/**
		 * Return what we found with allowing 3rd party to override
		 *
		 * @filter woc_filters_locate_template
		 */
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


if ( ! function_exists( 'woc_get_option' ) ) {
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
		return wooopenclose()->is_open();
	}
}


if ( ! function_exists( 'wooopenclose' ) ) {
	function wooopenclose() {

		global $wooopenclose;

		if ( empty( $wooopenclose ) ) {
			$wooopenclose = new WOC_Functions();
		}

		return $wooopenclose;
	}
}


if ( ! function_exists( 'woc_pro_available' ) ) {
	function woc_pro_available() {

		if ( defined( 'WOCP_PLUGIN_FILE' ) ) {
			return true;
		}

		return false;
	}
}