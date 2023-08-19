<?php

if ( ! function_exists( 'lucent_register_required_plugins' ) ) {
	/**
	 * Function that registers theme required and optional plugins. Hooks to tgmpa_register hook
	 */
	function lucent_register_required_plugins() {
		$plugins = array(
			array(
				'name'               => esc_html__( 'Qode Framework', 'lucent' ),
				'slug'               => 'qode-framework',
				'source'             => LUCENT_INC_ROOT_DIR . '/plugins/qode-framework.zip',
				'version'            => '1.2.2',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Lucent Core', 'lucent' ),
				'slug'               => 'lucent-core',
				'source'             => LUCENT_INC_ROOT_DIR . '/plugins/lucent-core.zip',
				'version'            => '1.0.3',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Lucent Membership', 'lucent' ),
				'slug'               => 'lucent-membership',
				'source'             => LUCENT_INC_ROOT_DIR . '/plugins/lucent-membership.zip',
				'version'            => '1.1',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Revolution Slider', 'lucent' ),
				'slug'               => 'revslider',
				'source'             => LUCENT_INC_ROOT_DIR . '/plugins/revslider.zip',
				'version'            => '6.6.13',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'     => esc_html__( 'Elementor Page Builder', 'lucent' ),
				'slug'     => 'elementor',
				'required' => true,
			),
			array(
				'name'     => esc_html__( 'Qi Addons For Elementor', 'lucent' ),
				'slug'     => 'qi-addons-for-elementor',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Qi Blocks', 'lucent' ),
				'slug'     => 'qi-blocks',
				'required' => true,
			),
			array(
				'name'     => esc_html__( 'WooCommerce Plugin', 'lucent' ),
				'slug'     => 'woocommerce',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Contact Form 7', 'lucent' ),
				'slug'     => 'contact-form-7',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Custom Twitter Feeds', 'lucent' ),
				'slug'     => 'custom-twitter-feeds',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Instagram Feed', 'lucent' ),
				'slug'     => 'instagram-feed',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Envato Market', 'lucent' ),
				'slug'     => 'envato-market',
				'source'   => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
				'required' => false,
			)
		);
		
		$config = array(
			'domain'       => 'lucent',
			'default_path' => '',
			'parent_slug'  => 'themes.php',
			'capability'   => 'edit_theme_options',
			'menu'         => 'install-required-plugins',
			'has_notices'  => true,
			'is_automatic' => false,
			'message'      => '',
			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'lucent' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'lucent' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'lucent' ),
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'lucent' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'lucent' ),
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'lucent' ),
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this website for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'lucent' ),
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'lucent' ),
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'lucent' ),
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this website for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'lucent' ),
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'lucent' ),
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this website for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'lucent' ),
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'lucent' ),
				'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'lucent' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'lucent' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'lucent' ),
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'lucent' ),
				'nag_type'                        => 'updated'
			),
		);
		
		tgmpa( $plugins, $config );
	}
	
	add_action( 'tgmpa_register', 'lucent_register_required_plugins' );
}
