<?php

if ( ! function_exists( 'lucent_is_installed' ) ) {
	/**
	 * Function that checks if forward plugin installed
	 *
	 * @param string $plugin - plugin name
	 *
	 * @return bool
	 */
	function lucent_is_installed( $plugin ) {
		
		switch ( $plugin ) {
			case 'framework';
				return class_exists( 'QodeFramework' );
				break;
			case 'core';
				return class_exists( 'LucentCore' );
				break;
			case 'woocommerce';
				return class_exists( 'WooCommerce' );
				break;
			case 'gutenberg-page';
				$current_screen = function_exists( 'get_current_screen' ) ? get_current_screen() : array();
				
				return method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor();
				break;
			case 'gutenberg-editor':
				return class_exists( 'WP_Block_Type' );
				break;
			default:
				return false;
		}
	}
}

if ( ! function_exists( 'lucent_include_theme_is_installed' ) ) {
	/**
	 * Function that set case is installed element for framework functionality
	 *
	 * @param bool $installed
	 * @param string $plugin - plugin name
	 *
	 * @return bool
	 */
	function lucent_include_theme_is_installed( $installed, $plugin ) {
		
		if ( $plugin === 'theme' ) {
			return class_exists( 'LucentHandler' );
		}
		
		return $installed;
	}
	
	add_filter( 'qode_framework_filter_is_plugin_installed', 'lucent_include_theme_is_installed', 10, 2 );
}

if ( ! function_exists( 'lucent_template_part' ) ) {
	/**
	 * Function that echo module template part.
	 *
	 * @param string $module name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array  $params array of parameters to pass to template
	 */
	function lucent_template_part( $module, $template, $slug = '', $params = array() ) {
		echo lucent_get_template_part( $module, $template, $slug, $params );
	}
}

if ( ! function_exists( 'lucent_get_template_part' ) ) {
	/**
	 * Function that load module template part.
	 *
	 * @param string $module   name of the module from inc folder
	 * @param string $template full path of the template to load
	 * @param string $slug
	 * @param array  $params   the array of parameters to pass to the template
	 *
	 * @return string - string containing html of template
	 */
	function lucent_get_template_part( $module, $template, $slug = '', $params = array() ) {
		// HTML Content from template
		$html          = '';
		$template_path = LUCENT_INC_ROOT_DIR . '/' . $module;
		$temp = $template_path . '/' . $template;
		// The array of parameters to pass to the template
		if ( is_array( $params ) && count( $params ) ) {
			extract( $params, EXTR_SKIP ); // @codingStandardsIgnoreLine
		}
		$template = '';
		if ( ! empty( $temp ) ) {
			if ( ! empty( $slug ) ) {
				$template = "{$temp}-{$slug}.php";
				if ( ! file_exists( $template ) ) {
					$template = $temp . '.php';
				}
			} else {
				$template = $temp . '.php';
			}
		}
		if ( $template ) {
			ob_start();
			include( $template ); // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			$html = ob_get_clean();
		}
		return $html;
	}
}

if ( ! function_exists( 'lucent_get_page_id' ) ) {
	/**
	 * Function that returns current page id
	 * Additional conditional is to check if current page is any wp archive page (archive, category, tag, date etc.) and returns -1
	 *
	 * @return int
	 */
	function lucent_get_page_id() {
		$page_id = get_queried_object_id();
		
		if ( lucent_is_wp_template() ) {
			$page_id = -1;
		}
		
		return apply_filters( 'lucent_filter_page_id', $page_id );
	}
}

if ( ! function_exists( 'lucent_is_wp_template' ) ) {
	/**
	 * Function that checks if current page default wp page
	 *
	 * @return bool
	 */
	function lucent_is_wp_template() {
		return is_archive() || is_search() || is_404() || ( is_front_page() && is_home() );
	}
}

if ( ! function_exists( 'lucent_get_ajax_status' ) ) {
	/**
	 * Function that return status from ajax functions
	 *
	 * @param string $status - success or error
	 * @param string $message - ajax message value
	 * @param string|array $data - returned value
	 * @param string $redirect - url address
	 */
	function lucent_get_ajax_status( $status, $message, $data = null, $redirect = '' ) {
		$response = array(
			'status'   => esc_attr( $status ),
			'message'  => esc_html( $message ),
			'data'     => $data,
			'redirect' => ! empty( $redirect ) ? esc_url( $redirect ) : '',
		);
		
		$output = json_encode( $response );
		
		exit( $output );
	}
}

if ( ! function_exists( 'lucent_get_icon' ) ) {
	/**
	 * Function that return icon html
	 *
	 * @param string $icon - icon class name
	 * @param string $icon_pack - icon pack name
	 * @param string $backup_text - backup text label if framework is not installed
	 * @param array $params - icon parameters
	 *
	 * @return string|mixed
	 */
	function lucent_get_icon( $icon, $icon_pack, $backup_text, $params = array() ) {
		$value = lucent_is_installed( 'framework' ) && lucent_is_installed( 'core' ) ? qode_framework_icons()->render_icon( $icon, $icon_pack, $params ) : $backup_text;
		
		return $value;
	}
}

if ( ! function_exists( 'lucent_render_icon' ) ) {
	/**
	 * Function that render icon html
	 *
	 * @param string $icon - icon class name
	 * @param string $icon_pack - icon pack name
	 * @param string $backup_text - backup text label if framework is not installed
	 * @param array $params - icon parameters
	 */
	function lucent_render_icon( $icon, $icon_pack, $backup_text, $params = array() ) {
		echo lucent_get_icon( $icon, $icon_pack, $backup_text, $params );
	}
}

if ( ! function_exists( 'lucent_get_button_element' ) ) {
	/**
	 * Function that returns button with provided params
	 *
	 * @param array $params - array of parameters
	 *
	 * @return string - string representing button html
	 */
	function lucent_get_button_element( $params, $class ) {
		if ( class_exists( 'LucentCoreButtonShortcode' ) ) {
			return LucentCoreButtonShortcode::call_shortcode( $params );
		} else {
			$link   = isset( $params['link'] ) ? $params['link'] : '#';
			$target = isset( $params['target'] ) ? $params['target'] : '_self';
			$text   = isset( $params['text'] ) ? $params['text'] : '';

			if ( ! empty( $class ) && 'qodef-arrow-only' === $class ) {
				$return_html = '<a itemprop="url" class="qodef-theme-button' . ' ' . $class . '"' . 'href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">';
				$return_html .= '<span class="qodef-m-text">' . esc_html( $text ) . '</span></a>';

				return $return_html;

			} else if ( ! empty ( $class ) && $class === 'qodef-textual-with-arrow' ) {
				$return_html = '<a itemprop="url" class="qodef-theme-button' . ' ' . $class .'"'. 'href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">';
				$return_html .= '<span class="qodef-m-text">' . esc_html( $text ) . '</span></a>';

				return $return_html;

			} else {
				return '<a itemprop="url" class="qodef-theme-button" href="' . esc_url( $link ) . '" target="' . esc_attr( $target ) . '">' . esc_html( $text ) . '</a>';
			}
		}
	}
}

if ( ! function_exists( 'lucent_render_button_element' ) ) {
	/**
	 * Function that render button with provided params
	 *
	 * @param array $params - array of parameters
	 */
	function lucent_render_button_element( $params, $class_name = '' ) {
		$class = isset( $class_name ) && ! empty( $class_name ) ? esc_attr( $class_name ) : '';
		echo lucent_get_button_element( $params, $class );
	}
}

if ( ! function_exists( 'lucent_class_attribute' ) ) {
	/**
	 * Function that render class attribute
	 *
	 * @param string|array $class
	 */
	function lucent_class_attribute( $class ) {
		echo lucent_get_class_attribute( $class );
	}
}

if ( ! function_exists( 'lucent_get_class_attribute' ) ) {
	/**
	 * Function that return class attribute
	 *
	 * @param string|array $class
	 *
	 * @return string|mixed
	 */
	function lucent_get_class_attribute( $class ) {
		$value = lucent_is_installed( 'framework' ) ? qode_framework_get_class_attribute( $class ) : '';
		
		return $value;
	}
}

if ( ! function_exists( 'lucent_get_post_value_through_levels' ) ) {
	/**
	 * Function that returns meta value if exists
	 *
	 * @param string $name name of option
	 * @param int    $post_id id of
	 *
	 * @return string value of option
	 */
	function lucent_get_post_value_through_levels( $name, $post_id = null ) {
		return lucent_is_installed( 'framework' ) && lucent_is_installed( 'core' ) ? lucent_core_get_post_value_through_levels( $name, $post_id ) : '';
	}
}

if ( ! function_exists( 'lucent_get_space_value' ) ) {
	/**
	 * Function that returns spacing value based on selected option
	 *
	 * @param string $text_value - textual value of spacing
	 *
	 * @return int
	 */
	function lucent_get_space_value( $text_value ) {
		return lucent_is_installed( 'core' ) ? lucent_core_get_space_value( $text_value ) : 0;
	}
}

if ( ! function_exists( 'lucent_wp_kses_html' ) ) {
	/**
	 * Function that does escaping of specific html.
	 * It uses wp_kses function with predefined attributes array.
	 *
	 * @see wp_kses()
	 *
	 * @param string $type - type of html element
	 * @param string $content - string to escape
	 *
	 * @return string escaped output
	 */
	function lucent_wp_kses_html( $type, $content ) {
		return lucent_is_installed( 'framework' ) ? qode_framework_wp_kses_html( $type, $content ) : $content;
	}
}

if ( ! function_exists( 'lucent_render_svg_icon' ) ) {
	/**
	 * Function that print svg html icon
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 */
	function lucent_render_svg_icon( $name, $class_name = '' ) {
		echo lucent_get_svg_icon( $name, $class_name );
	}
}

if ( ! function_exists( 'lucent_get_svg_icon' ) ) {
	/**
	 * Returns svg html
	 *
	 * @param string $name - icon name
	 * @param string $class_name - custom html tag class name
	 *
	 * @return string|html
	 */
	function lucent_get_svg_icon( $name, $class_name = '' ) {
		$html  = '';
		$class = isset( $class_name ) && ! empty( $class_name ) ?  'class="' . esc_attr( $class_name ) . '"' : '';
		
		switch ( $name ) {
			case 'menu':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="70" height="70" viewBox="0 0 82 81" style="fill: #000; stroke: #000;"><rect width="82" height="81"></rect><circle cx="41" cy="41" r="6"></circle></svg>';
				break;
			case 'menu-close':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 -28 15 70" style="enable-background:new 0 0 15 15;width: 70px;height: 70px;" xml:space="preserve"><style type="text/css">.qodef-mobile-menu-close{fill:none;stroke:#000000;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}</style><line class="qodef-mobile-menu-close" x1="13.5" y1="1.5" x2="1.5" y2="13.5"></line><line class="qodef-mobile-menu-close" x1="1.5" y1="1.5" x2="13.5" y2="13.5"></line></svg>';
				break;
			case 'search':
				$html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20" height="20" viewBox="0 0 20 20"><path d="M18.869 19.162l-5.943-6.484c1.339-1.401 2.075-3.233 2.075-5.178 0-2.003-0.78-3.887-2.197-5.303s-3.3-2.197-5.303-2.197-3.887 0.78-5.303 2.197-2.197 3.3-2.197 5.303 0.78 3.887 2.197 5.303 3.3 2.197 5.303 2.197c1.726 0 3.362-0.579 4.688-1.645l5.943 6.483c0.099 0.108 0.233 0.162 0.369 0.162 0.121 0 0.242-0.043 0.338-0.131 0.204-0.187 0.217-0.503 0.031-0.706zM1 7.5c0-3.584 2.916-6.5 6.5-6.5s6.5 2.916 6.5 6.5-2.916 6.5-6.5 6.5-6.5-2.916-6.5-6.5z"></path></svg>';
				break;
			case 'search-2':
				$html = '<svg ' . $class . ' http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>';
				break;
            case 'star':
                $html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><g><path d="M 20.756,11.768L 15.856,1.84L 10.956,11.768L0,13.36L 7.928,21.088L 6.056,32L 15.856,26.848L 25.656,32L 23.784,21.088L 31.712,13.36 z"></path></g></svg>';
                break;
            case 'menu-arrow-right':
                $html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><g><path d="M 13.8,24.196c 0.39,0.39, 1.024,0.39, 1.414,0l 6.486-6.486c 0.196-0.196, 0.294-0.454, 0.292-0.71 c0-0.258-0.096-0.514-0.292-0.71L 15.214,9.804c-0.39-0.39-1.024-0.39-1.414,0c-0.39,0.39-0.39,1.024,0,1.414L 19.582,17 L 13.8,22.782C 13.41,23.172, 13.41,23.806, 13.8,24.196z"></path></g></svg>';
                break;
            case 'slider-arrow-left':
                $html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 26 50.9" xml:space="preserve"><polyline points="25.6,0.4 0.7,25.5 25.6,50.6 "/></svg>';
                break;
            case 'slider-arrow-right':
                $html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 26 50.9" xml:space="preserve"><polyline points="0.4,50.6 25.3,25.5 0.4,0.4 "/></svg>';
                break;
            case 'pagination-arrow-left':
                $html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>';
                break;
            case 'pagination-arrow-right':
                $html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>';
                break;
            case 'close':
                $html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><g><path d="M 10.050,23.95c 0.39,0.39, 1.024,0.39, 1.414,0L 17,18.414l 5.536,5.536c 0.39,0.39, 1.024,0.39, 1.414,0 c 0.39-0.39, 0.39-1.024,0-1.414L 18.414,17l 5.536-5.536c 0.39-0.39, 0.39-1.024,0-1.414c-0.39-0.39-1.024-0.39-1.414,0 L 17,15.586L 11.464,10.050c-0.39-0.39-1.024-0.39-1.414,0c-0.39,0.39-0.39,1.024,0,1.414L 15.586,17l-5.536,5.536 C 9.66,22.926, 9.66,23.56, 10.050,23.95z"></path></g></svg>';
                break;
            case 'spinner':
                $html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" width="512" height="512" viewBox="0 0 512 512"><path d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>';
                break;
            case 'link':
                $html = '<svg ' . $class . ' xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32.06999969482422" height="33.58000183105469" viewBox="0 0 32.06999969482422 33.58000183105469"><g><path d="M 7.54,15.77c 1.278,1.278, 3.158,1.726, 4.868,1.216L 2.96,7.54C 2.652,7.232, 2.49,6.786, 2.49,6.254 c0-0.88, 0.46-2.004, 1.070-2.614c 0.8-0.8, 2.97-1.686, 3.98-0.682l 9.446,9.448c 0.138-0.462, 0.208-0.942, 0.208-1.422 c0-1.304-0.506-2.526-1.424-3.446L 9.364,1.134C 7.44-0.79, 3.616-0.068, 1.734,1.814C 0.642,2.906-0.036,4.598-0.036,6.23 c0,1.268, 0.416,2.382, 1.17,3.136L 7.54,15.77zM 24.46,16.23c-1.278-1.278-3.158-1.726-4.868-1.216l 9.448,9.448c 0.308,0.308, 0.47,0.752, 0.47,1.286 c0,0.88-0.46,2.004-1.070,2.614c-0.8,0.8-2.97,1.686-3.98,0.682L 15.014,19.594c-0.138,0.462-0.208,0.942-0.208,1.422 c0,1.304, 0.506,2.526, 1.424,3.446l 6.404,6.404c 1.924,1.924, 5.748,1.202, 7.63-0.68c 1.092-1.092, 1.77-2.784, 1.77-4.416 c0-1.268-0.416-2.382-1.17-3.136L 24.46,16.23zM 9.164,9.162C 8.908,9.416, 8.768,9.756, 8.768,10.116s 0.14,0.698, 0.394,0.952l 11.768,11.77 c 0.526,0.524, 1.38,0.524, 1.906,0c 0.256-0.254, 0.394-0.594, 0.394-0.954s-0.14-0.698-0.394-0.952L 11.068,9.162 C 10.544,8.638, 9.688,8.638, 9.164,9.162z"></path></g></svg>';
                break;
        }
		
		return $html;
	}
}
