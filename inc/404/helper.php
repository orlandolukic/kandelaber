<?php

if ( ! function_exists( 'lucent_set_404_page_inner_classes' ) ) {
	/**
	 * Function that return classes for the page inner div from header.php
	 *
	 * @param string $classes
	 *
	 * @return string
	 */
	function lucent_set_404_page_inner_classes( $classes ) {
		
		if ( is_404() ) {
			$classes = 'qodef-content-full-width';
		}
		
		return $classes;
	}
	
	add_filter( 'lucent_filter_page_inner_classes', 'lucent_set_404_page_inner_classes' );
}

if ( ! function_exists( 'lucent_get_404_page_parameters' ) ) {
	/**
	 * Function that set 404 page area content parameters
	 */
	function lucent_get_404_page_parameters() {
		
		$params = array(
			'title'       => esc_html__( 'Error Page', 'lucent' ),
			'text'        => esc_html__( 'The page you are looking for doesn\'t exist. It may have been moved or removed altogether. Please try searching for some other page, or return to the website\'s homepage to find what you\'re looking for.', 'lucent' ),
			'button_text' => esc_html__( 'Back to home', 'lucent' ),
		);
		
		return apply_filters( 'lucent_filter_404_page_template_params', $params );
	}
}
