<?php

if ( ! function_exists( 'lucent_register_justified_gallery_scripts' ) ) {
	/**
	 * Function that register module 3rd party scripts
	 */
	function lucent_register_justified_gallery_scripts() {
		wp_register_script( 'justified-gallery', LUCENT_INC_ROOT . '/justified-gallery/assets/js/plugins/jquery.justifiedGallery.min.js', array( 'jquery' ), true );
	}
	
	add_action( 'lucent_action_before_main_js', 'lucent_register_justified_gallery_scripts' );
}

if ( ! function_exists( 'lucent_include_justified_gallery_scripts' ) ) {
	/**
	 * Function that enqueue modules 3rd party scripts
	 *
	 * @param array $atts
	 */
	function lucent_include_justified_gallery_scripts( $atts ) {
		
		if ( isset( $atts['behavior'] ) && $atts['behavior'] == 'justified-gallery' ) {
			wp_enqueue_script( 'justified-gallery' );
		}
	}
	
	add_action( 'lucent_core_action_list_shortcodes_load_assets', 'lucent_include_justified_gallery_scripts' );
}

if ( ! function_exists( 'lucent_register_justified_gallery_scripts_for_list_shortcodes' ) ) {
	/**
	 * Function that set module 3rd party scripts for list shortcodes
	 *
	 * @param array $scripts
	 *
	 * @return array
	 */
	function lucent_register_justified_gallery_scripts_for_list_shortcodes( $scripts ) {
		
		$scripts['justified-gallery'] = array(
			'registered' => true
		);
		
		return $scripts;
	}
	
	add_filter( 'lucent_core_filter_register_list_shortcode_scripts', 'lucent_register_justified_gallery_scripts_for_list_shortcodes' );
}