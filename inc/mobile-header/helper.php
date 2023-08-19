<?php

if ( ! function_exists( 'lucent_load_page_mobile_header' ) ) {
	/**
	 * Function which loads page template module
	 */
	function lucent_load_page_mobile_header() {
		// Include mobile header template
		echo apply_filters( 'lucent_filter_mobile_header_template', lucent_get_template_part( 'mobile-header', 'templates/mobile-header' ) );
	}
	
	add_action( 'lucent_action_page_header_template', 'lucent_load_page_mobile_header' );
}

if ( ! function_exists( 'lucent_register_mobile_navigation_menus' ) ) {
	/**
	 * Function which registers navigation menus
	 */
	function lucent_register_mobile_navigation_menus() {
		$navigation_menus = apply_filters( 'lucent_filter_register_mobile_navigation_menus', array( 'mobile-navigation' => esc_html__( 'Mobile Navigation', 'lucent' ) ) );
		
		if ( ! empty( $navigation_menus ) ) {
			register_nav_menus( $navigation_menus );
		}
	}
	
	add_action( 'lucent_action_after_include_modules', 'lucent_register_mobile_navigation_menus' );
}