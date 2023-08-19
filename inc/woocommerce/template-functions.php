<?php

/**
 * Global templates hooks
 */

if ( ! function_exists( 'lucent_add_main_woo_page_template_holder' ) ) {
	/**
	 * Function that render additional content for main shop page
	 */
	function lucent_add_main_woo_page_template_holder() {
		echo '<main id="qodef-page-content" class="qodef-grid qodef-layout--template qodef--no-bottom-space ' . esc_attr( lucent_get_grid_gutter_classes() ) . '"><div class="qodef-grid-inner clear">';
	}
}

if ( ! function_exists( 'lucent_add_main_woo_page_template_holder_end' ) ) {
	/**
	 * Function that render additional content for main shop page
	 */
	function lucent_add_main_woo_page_template_holder_end() {
		echo '</div></main>';
	}
}

if ( ! function_exists( 'lucent_add_main_woo_page_holder' ) ) {
	/**
	 * Function that render additional content around WooCommerce pages
	 */
	function lucent_add_main_woo_page_holder() {
		$classes = array();
		
		// add class to pages with sidebar and on single page
		if ( lucent_is_woo_page( 'shop' ) || lucent_is_woo_page( 'category' ) || lucent_is_woo_page( 'tag' ) || lucent_is_woo_page( 'single' ) ) {
			$classes[] = 'qodef-grid-item';
		}
		
		// add class to pages with sidebar
		if ( lucent_is_woo_page( 'shop' ) || lucent_is_woo_page( 'category' ) || lucent_is_woo_page( 'tag' ) ) {
			$classes[] = lucent_get_page_content_sidebar_classes();
		}
		
		$classes[] = lucent_get_woo_main_page_classes();
		
		echo '<div id="qodef-woo-page" class="' . implode( ' ', $classes ) . '">';
	}
}

if ( ! function_exists( 'lucent_add_main_woo_page_holder_end' ) ) {
	/**
	 * Function that render additional content around WooCommerce pages
	 */
	function lucent_add_main_woo_page_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'lucent_add_main_woo_page_sidebar_holder' ) ) {
	/**
	 * Function that render sidebar layout for main shop page
	 */
	function lucent_add_main_woo_page_sidebar_holder() {
		
		if ( ! is_singular( 'product' ) ) {
			// Include page content sidebar
			lucent_template_part( 'sidebar', 'templates/sidebar' );
		}
	}
}

if ( ! function_exists( 'lucent_woo_render_product_categories' ) ) {
	/**
	 * Function that render product categories
	 *
	 * @param string $before
	 * @param string $after
	 */
	function lucent_woo_render_product_categories( $before = '', $after = '' ) {
		echo lucent_woo_get_product_categories( $before, $after );
	}
}

if ( ! function_exists( 'lucent_woo_get_product_categories' ) ) {
	/**
	 * Function that render product categories
	 *
	 * @param string $before
	 * @param string $after
	 *
	 * @return string
	 */
	function lucent_woo_get_product_categories( $before = '', $after = '' ) {
		$product = lucent_woo_get_global_product();
		
		return ! empty( $product ) ? wc_get_product_category_list( $product->get_id(), '<span class="qodef-category-separator"></span>', $before, $after ) : '';
	}
}

/**
 * Shop page templates hooks
 */

if ( ! function_exists( 'lucent_add_results_and_ordering_holder' ) ) {
	/**
	 * Function that render additional content around results and ordering templates on main shop page
	 */
	function lucent_add_results_and_ordering_holder() {
		echo '<div class="qodef-woo-results">';
	}
}

if ( ! function_exists( 'lucent_add_results_and_ordering_holder_end' ) ) {
	/**
	 * Function that render additional content around results and ordering templates on main shop page
	 */
	function lucent_add_results_and_ordering_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'lucent_add_product_list_item_holder' ) ) {
	/**
	 * Function that render additional content around product list item on main shop page
	 */
	function lucent_add_product_list_item_holder() {
		echo '<div class="qodef-woo-product-inner">';
	}
}

if ( ! function_exists( 'lucent_add_product_list_item_holder_end' ) ) {
	/**
	 * Function that render additional content around product list item on main shop page
	 */
	function lucent_add_product_list_item_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'lucent_add_product_list_item_image_holder' ) ) {
	/**
	 * Function that render additional content around image template on main shop page
	 */
	function lucent_add_product_list_item_image_holder() {
		echo '<div class="qodef-woo-product-image">';
	}
}

if ( ! function_exists( 'lucent_add_product_list_item_image_holder_end' ) ) {
	/**
	 * Function that render additional content around image template on main shop page
	 */
	function lucent_add_product_list_item_image_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'lucent_add_product_list_item_additional_image_holder' ) ) {
	/**
	 * Function that render additional content around image and sale templates on main shop page
	 */
	function lucent_add_product_list_item_additional_image_holder() {
		echo '<div class="qodef-woo-product-image-inner">';
	}
}

if ( ! function_exists( 'lucent_add_product_list_item_additional_image_holder_end' ) ) {
	/**
	 * Function that render additional content around image and sale templates on main shop page
	 */
	function lucent_add_product_list_item_additional_image_holder_end() {
		// Hook to include additional content inside product list item image
		do_action( 'lucent_action_product_list_item_additional_image_content' );
		
		echo '</div>';
	}
}

if ( ! function_exists( 'lucent_add_product_list_item_content_holder' ) ) {
	/**
	 * Function that render additional content around product info on main shop page
	 */
	function lucent_add_product_list_item_content_holder() {
		echo '<div class="qodef-woo-product-content">';
	}
}

if ( ! function_exists( 'lucent_add_product_list_item_content_holder_end' ) ) {
	/**
	 * Function that render additional content around product info on main shop page
	 */
	function lucent_add_product_list_item_content_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'lucent_add_product_list_item_categories' ) ) {
	/**
	 * Function that render product categories
	 */
	function lucent_add_product_list_item_categories() {
		lucent_woo_render_product_categories( '<div class="qodef-woo-product-categories">', '</div>' );
	}
}

/**
 * Product single page templates hooks
 */

if ( ! function_exists( 'lucent_add_product_single_content_holder' ) ) {
	/**
	 * Function that render additional content around image and summary templates on single product page
	 */
	function lucent_add_product_single_content_holder() {
		echo '<div class="qodef-woo-single-inner">';
	}
}

if ( ! function_exists( 'lucent_add_product_single_content_holder_end' ) ) {
	/**
	 * Function that render additional content around image and summary templates on single product page
	 */
	function lucent_add_product_single_content_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'lucent_add_product_single_image_holder' ) ) {
	/**
	 * Function that render additional content around featured image on single product page
	 */
	function lucent_add_product_single_image_holder() {
		echo '<div class="qodef-woo-single-image">';
	}
}

if ( ! function_exists( 'lucent_add_product_single_image_holder_end' ) ) {
	/**
	 * Function that render additional content around featured image on single product page
	 */
	function lucent_add_product_single_image_holder_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'lucent_woo_product_render_social_share_html' ) ) {
	/**
	 * Function that render social share html
	 */
	function lucent_woo_product_render_social_share_html() {
		
		if ( class_exists( 'LucentCoreSocialShareShortcode' ) ) {
			$params           = array();
			$params['layout'] = 'list';
			$params['title']  = esc_html__( 'Share:', 'lucent' );
			
			echo LucentCoreSocialShareShortcode::call_shortcode( $params );
		}
	}
}

/**
 * Override default WooCommerce templates
 */

if ( ! function_exists( 'lucent_woo_disable_page_heading' ) ) {
	/**
	 * Function that disable heading template on main shop page
	 *
	 * @return bool
	 */
	function lucent_woo_disable_page_heading() {
		return false;
	}
}

if ( ! function_exists( 'lucent_add_product_list_holder' ) ) {
	/**
	 * Function that add additional content around product lists on main shop page
	 *
	 * @param string $html
	 *
	 * @return string which contains html content
	 */
	function lucent_add_product_list_holder( $html ) {
		$classes = array();
		$layout  = lucent_get_post_value_through_levels( 'qodef_product_list_item_layout' );
		$option  = lucent_get_post_value_through_levels( 'qodef_woo_product_list_columns_space' );
		
		if ( ! empty( $layout ) ) {
			$classes[] = 'qodef-item-layout--' . $layout;
		}
		
		if ( ! empty( $option ) ) {
			$classes[] = 'qodef-gutter--' . $option;
		}
		
		$classes = implode( ' ', $classes );
		
		return '<div class="qodef-woo-product-list ' . esc_attr( $classes ) . '">' . $html;
	}
}

if ( ! function_exists( 'lucent_add_product_list_holder_end' ) ) {
	/**
	 * Function that add additional content around product lists on main shop page
	 *
	 * @param string $html
	 *
	 * @return string which contains html content
	 */
	function lucent_add_product_list_holder_end( $html ) {
		return $html . '</div>';
	}
}

if ( ! function_exists( 'lucent_woo_product_list_columns' ) ) {
	/**
	 * Function that set number of columns for main shop page
	 *
	 * @param int $columns
	 *
	 * @return int
	 */
	function lucent_woo_product_list_columns( $columns ) {
		$option = lucent_get_post_value_through_levels( 'qodef_woo_product_list_columns' );
		
		if ( ! empty( $option ) ) {
			$columns = intval( $option );
		} else {
			$columns = 4;
		}
		
		return $columns;
	}
}

if ( ! function_exists( 'lucent_woo_products_per_page' ) ) {
	/**
	 * Function that set number of items for main shop page
	 *
	 * @param int $products_per_page
	 *
	 * @return int
	 */
	function lucent_woo_products_per_page( $products_per_page ) {
		$option = lucent_get_post_value_through_levels( 'qodef_woo_product_list_products_per_page' );
		
		if ( ! empty( $option ) ) {
			$products_per_page = intval( $option );
		}
		
		return $products_per_page;
	}
}

if ( ! function_exists( 'lucent_core_woo_change_existing_euro_currency_symbol' ) ) {
    /**
     * Function that change euro currency symbol to EUR
     *
     * @param string $currency_symbol
     *
     * @param string $currency
     *
     * @return string
     */
    function lucent_core_woo_change_existing_euro_currency_symbol( $currency_symbol, $currency ) {
        $option = lucent_get_post_value_through_levels( 'qodef_woo_global_change_euro_currency' );

        if ( ! empty( $option ) && $option === 'yes' ) {
            switch( $currency ) {
                case 'EUR': $currency_symbol = 'EUR'; break;
            }
        }

        return $currency_symbol;
    }

    add_filter('woocommerce_currency_symbol', 'lucent_core_woo_change_existing_euro_currency_symbol', 10, 2);
}

if ( ! function_exists( 'lucent_woo_pagination_args' ) ) {
	/**
	 * Function that override pagination args on main shop page
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function lucent_woo_pagination_args( $args ) {
		$args['prev_text']          = lucent_get_svg_icon( 'pagination-arrow-left' );
		$args['next_text']          = lucent_get_svg_icon( 'pagination-arrow-right' );
		$args['type']               = 'plain';
		
		return $args;
	}
}

if ( ! function_exists( 'lucent_add_single_product_classes' ) ) {
	/**
	 * Function that render additional content around WooCommerce pages
	 */
	function lucent_add_single_product_classes( $classes, $class = '', $post_id = 0 ) {
		if ( ! $post_id || ! in_array( get_post_type( $post_id ), array( 'product', 'product_variation' ), true ) ) {
			return $classes;
		}
		
		$product = wc_get_product( $post_id );
		
		if ( $product ) {
			$new = get_post_meta( $post_id, 'qodef_show_new_sign', true );
			
			if ( $new === 'yes' ) {
				$classes[] = 'new';
			}

            $single_product_layout = lucent_get_post_value_through_levels( 'qodef_woo_single_layout', get_queried_object_id());
            if ( ! empty ( $single_product_layout ) ) {
                $classes[] = 'qodef-layout--' . $single_product_layout;
            }
		}
		
		return $classes;
	}
}

if ( ! function_exists( 'lucent_add_sale_flash_on_product' ) ) {
	/**
	 * Function for adding on sale template for product
	 */
	function lucent_add_sale_flash_on_product() {
		$product = lucent_woo_get_global_product();
		
		if ( ! empty( $product ) && $product->is_on_sale() ) {
			echo lucent_woo_set_sale_flash();
		}
	}
}

if ( ! function_exists( 'lucent_woo_set_sale_flash' ) ) {
	/**
	 * Function that override on sale template for product
	 *
	 * @return string which contains html content
	 */
	function lucent_woo_set_sale_flash() {
		$product = lucent_woo_get_global_product();
		
		return '<span class="qodef-woo-product-mark qodef-woo-onsale">' . lucent_woo_get_woocommerce_sale( $product ) . '</span>';
	}
}

if ( ! function_exists( 'lucent_woo_get_woocommerce_sale' ) ) {
	/**
	 * Function that return sale mark label
	 *
	 * @param object $product
	 *
	 * @return string
	 */
	function lucent_woo_get_woocommerce_sale( $product ) {
		$enable_percent_mark = lucent_get_post_value_through_levels( 'qodef_woo_enable_percent_sign_value' );
		$price               = floatval( $product->get_regular_price() );
		$sale_price          = floatval( $product->get_sale_price() );
		
		if ( $price > 0 && $enable_percent_mark === 'yes' ) {
			return '-' . ( 100 - round( ( $sale_price * 100 ) / $price ) ) . '%';
		} else {
			return esc_html__( 'On Sale', 'lucent' );
		}
	}
}

if ( ! function_exists( 'lucent_add_out_of_stock_mark_on_product' ) ) {
	/**
	 * Function for adding out of stock template for product
	 */
	function lucent_add_out_of_stock_mark_on_product() {
		$product = lucent_woo_get_global_product();
		
		if ( ! empty( $product ) && ! $product->is_in_stock() ) {
			echo lucent_get_out_of_stock_mark();
		}
	}
}

if ( ! function_exists( 'lucent_get_out_of_stock_mark' ) ) {
	/**
	 * Function for adding out of stock template for product
	 *
	 * @return string
	 */
	function lucent_get_out_of_stock_mark() {
		return '<span class="qodef-woo-product-mark qodef-out-of-stock">' . esc_html__( 'Out of stock', 'lucent' ) . '</span>';
	}
}

if ( ! function_exists( 'lucent_add_new_mark_on_product' ) ) {
	/**
	 * Function for adding out of stock template for product
	 */
	function lucent_add_new_mark_on_product() {
		$product = lucent_woo_get_global_product();
		
		if ( ! empty( $product ) && $product->get_id() !== '' ) {
			echo lucent_get_new_mark( $product->get_id() );
		}
	}
}

if ( ! function_exists( 'lucent_get_new_mark' ) ) {
	/**
	 * Function for adding out of stock template for product
	 *
	 * @param int $product_id
	 *
	 * @return string
	 */
	function lucent_get_new_mark( $product_id ) {
		$option = get_post_meta( $product_id, 'qodef_show_new_sign', true );
		
		if ( $option === 'yes' ) {
			return '<span class="qodef-woo-product-mark qodef-new">' . esc_html__( 'New', 'lucent' ) . '</span>';
		}
		
		return false;
	}
}

if ( ! function_exists( 'lucent_woo_shop_loop_item_title' ) ) {
	/**
	 * Function that override product list item title template
	 */
	function lucent_woo_shop_loop_item_title() {
		$option    = lucent_get_post_value_through_levels( 'qodef_woo_product_list_title_tag' );
		$title_tag = ! empty( $option ) ? esc_attr( $option ) : 'h5';
		
		echo '<' . $title_tag . ' class="qodef-woo-product-title woocommerce-loop-product__title">' . get_the_title() . '</' . $title_tag . '>';
	}
}

if ( ! function_exists( 'lucent_woo_template_single_title' ) ) {
	/**
	 * Function that override product single item title template
	 */
	function lucent_woo_template_single_title() {
		$option    = lucent_get_post_value_through_levels( 'qodef_woo_single_title_tag' );
		$title_tag = ! empty( $option ) ? esc_attr( $option ) : 'h2';
		
		echo '<' . $title_tag . ' class="qodef-woo-product-title product_title entry-title">' . get_the_title() . '</' . $title_tag . '>';
	}
}

if ( ! function_exists( 'lucent_woo_single_thumbnail_images_columns' ) ) {
	/**
	 * Function that set number of columns for thumbnail images on single product page
	 *
	 * @param int $columns
	 *
	 * @return int
	 */
	function lucent_woo_single_thumbnail_images_columns( $columns ) {
		$option = lucent_get_post_value_through_levels( 'qodef_woo_single_thumbnail_images_columns' );
		
		if ( ! empty( $option ) ) {
			$columns = intval( $option );
		}
		
		return $columns;
	}
}

if ( ! function_exists( 'lucent_woo_single_thumbnail_images_size' ) ) {
	/**
	 * Function that set thumbnail images size on single product page
	 *
	 * @param string $size
	 *
	 * @return string
	 */
	function lucent_woo_single_thumbnail_images_size( $size ) {

        $single_product_layout = lucent_get_post_value_through_levels( 'qodef_woo_single_layout', get_queried_object_id());

        if ( $single_product_layout === 'wide-gallery' ) {
            return apply_filters( 'lucent_filter_woo_single_thumbnail_size', 'full' );
        } else {
            return apply_filters( 'lucent_filter_woo_single_thumbnail_size', 'woocommerce_thumbnail' );
        }
	}
}

if ( ! function_exists( 'lucent_woo_single_thumbnail_images_wrapper' ) ) {
	/**
	 * Function that add additional wrapper around thumbnail images on single product
	 */
	function lucent_woo_single_thumbnail_images_wrapper() {
		echo '<div class="qodef-woo-thumbnails-wrapper">';
	}
}

if ( ! function_exists( 'lucent_woo_single_thumbnail_images_wrapper_end' ) ) {
	/**
	 * Function that add additional wrapper around thumbnail images on single product
	 */
	function lucent_woo_single_thumbnail_images_wrapper_end() {
		echo '</div>';
	}
}

if ( ! function_exists( 'lucent_woo_single_upsells_product_list_columns' ) ) {
    /**
     * Function that set number of columns for related product list on single product page
     *
     * @return int
     */
    function lucent_woo_single_upsells_product_list_columns() {
        $option = lucent_get_post_value_through_levels( 'qodef_woo_single_upsells_product_list_columns' );

        $columns = 4;

        if ( ! empty( $option ) ) {
            $columns        = intval( $option );
        }

        return $columns;
    }
}

if ( ! function_exists( 'lucent_woo_single_related_product_list_columns' ) ) {
	/**
	 * Function that set number of columns for related product list on single product page
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	function lucent_woo_single_related_product_list_columns( $args ) {
		$option = lucent_get_post_value_through_levels( 'qodef_woo_single_related_product_list_columns' );
		
		if ( ! empty( $option ) ) {
			$args['posts_per_page'] = intval( $option );
			$args['columns']        = intval( $option );
		}
		
		return $args;
	}
}

if ( ! function_exists( 'lucent_woo_product_get_rating_html' ) ) {
	/**
	 * Function that override ratings templates
	 *
	 * @param string $html - contains html content
	 * @param float $rating
	 * @param int $count - total number of ratings
	 *
	 * @return string
	 */
	function lucent_woo_product_get_rating_html( $html, $rating, $count ) {
		if ( ! empty( $rating ) ) {
            $html = '';

            $single_product_layout = lucent_get_post_value_through_levels( 'qodef_woo_single_layout', get_queried_object_id());

            if ( $single_product_layout === 'wide-gallery' ) {
                $html .= '<div class="qodef-woo-rating-text">';
                $html .= esc_html__('Reviews:', 'lucent');
                $html .= '</div>';
            }


			$html .= '<div class="qodef-woo-ratings qodef-m"><div class="qodef-m-inner">';
			$html .= '<div class="qodef-m-star qodef--initial">';
			for ( $i = 0; $i < 5; $i ++ ) {
				$html .= lucent_get_svg_icon( 'star', 'qodef-m-star-item' );
			}
			$html .= '</div>';
			$html .= '<div class="qodef-m-star qodef--active" style="width:' . ( ( $rating / 5 ) * 100 ) . '%">';
			for ( $i = 0; $i < 5; $i ++ ) {
				$html .= lucent_get_svg_icon( 'star', 'qodef-m-star-item' );
			}
			$html .= '</div>';
			$html .= '</div></div>';
		}
		
		return $html;
	}
}

if ( ! function_exists( 'lucent_woo_product_get_price_html' ) ) {
    /**
     * Function that override price templates
     *
     * @param string $html - contains html content
     * @param float $rating
     * @param int $count - total number of ratings
     *
     * @return string
     */
    function lucent_woo_product_get_price_html( $price ) {

        $single_product_layout = lucent_get_post_value_through_levels( 'qodef_woo_single_layout', get_queried_object_id());
        $html = '';

        if ( $single_product_layout === 'wide-gallery' ) {
            $html .= '<span class="qodef-price-text">';
            $html .= esc_html__('Price:', 'lucent');
            $html .= '</span>';
            $html .= $price;
        } else {
            $html .= $price;
        }

        return $html;
    }
}

if ( ! function_exists( 'lucent_woo_get_product_search_form' ) ) {
	/**
	 * Function that override product search widget form
	 *
	 * @param string $html
	 *
	 * @return string which contains html content
	 */
	function lucent_woo_get_product_search_form( $html ) {
		return lucent_get_template_part( 'woocommerce', 'templates/product-searchform' );
	}
}

if ( ! function_exists( 'lucent_woo_get_content_widget_product' ) ) {
	/**
	 * Function that override product content widget
	 *
	 * @param string $located
	 * @param string $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return string which contains html content
	 */
	function lucent_woo_get_content_widget_product( $located, $template_name, $args, $template_path, $default_path ) {
		
		if ( $template_name === 'content-widget-product.php' && file_exists( LUCENT_INC_ROOT_DIR . '/woocommerce/templates/content-widget-product.php' ) ) {
			$located = LUCENT_INC_ROOT_DIR . '/woocommerce/templates/content-widget-product.php';
		}
		
		return $located;
	}
}

if ( ! function_exists( 'lucent_woo_get_quantity_input' ) ) {
	/**
	 * Function that override quantity input
	 *
	 * @param string $located
	 * @param string $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return string which contains html content
	 */
	function lucent_woo_get_quantity_input( $located, $template_name, $args, $template_path, $default_path ) {
		
		if ( $template_name === 'global/quantity-input.php' && file_exists( LUCENT_INC_ROOT_DIR . '/woocommerce/templates/global/quantity-input.php' ) ) {
			$located = LUCENT_INC_ROOT_DIR . '/woocommerce/templates/global/quantity-input.php';
		}
		
		return $located;
	}
}

if ( ! function_exists( 'lucent_woo_get_single_product_meta' ) ) {
	/**
	 * Function that override single product meta
	 *
	 * @param string $located
	 * @param string $template_name
	 * @param array $args
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return string which contains html content
	 */
	function lucent_woo_get_single_product_meta( $located, $template_name, $args, $template_path, $default_path ) {
		
		if ( $template_name === 'single-product/meta.php' && file_exists( LUCENT_INC_ROOT_DIR . '/woocommerce/templates/single-product/meta.php' ) ) {
			$located = LUCENT_INC_ROOT_DIR . '/woocommerce/templates/single-product/meta.php';
		}
		
		return $located;
	}
}

if ( ! function_exists( 'lucent_woo_get_single_product_standard_rating' ) ) {
    /**
     * Function that override single product meta
     *
     * @return string which contains html content
     */
    function lucent_woo_get_single_product_standard_rating() {

        $single_product_layout = lucent_get_post_value_through_levels( 'qodef_woo_single_layout', get_queried_object_id());

        if ( $single_product_layout === 'standard' || ! lucent_is_installed( 'core' ) ) {
            return woocommerce_template_single_rating();
        }
    }
}

if ( ! function_exists( 'lucent_woo_get_single_product_wide_gallery_rating' ) ) {
    /**
     * Function that override single product meta
     *
     * @return string which contains html content
     */
    function lucent_woo_get_single_product_wide_gallery_rating() {

        $single_product_layout = lucent_get_post_value_through_levels( 'qodef_woo_single_layout', get_queried_object_id());

        if ( $single_product_layout === 'wide-gallery' ) {
            return woocommerce_template_single_rating();
        }
    }
}

if ( ! function_exists( 'lucent_woo_single_wide_gallery_sku' ) ) {
    /**
     * Function that prints single product sku on wide gallery layout
     *
     * @return string which contains html content
     */
    function lucent_woo_single_wide_gallery_sku() {
        global $product;

        $sku_enabled = wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) );

        $author_id     = get_the_author_meta('ID');
        $autor_name = get_the_author_meta( 'display_name' );
        $author_links   = get_author_posts_url( $author_id );
        $author_url   = get_the_author_meta( 'url' );

        if ( ! empty ($author_url) ) {
            $url = $author_url;
        } else {
            $url = $author_links;
        }

        $single_product_layout = lucent_get_post_value_through_levels( 'qodef_woo_single_layout', get_queried_object_id());
        $design_by_author_enabled = lucent_get_post_value_through_levels( 'qodef_woo_single_enable_design_by_author', get_queried_object_id());
	    $is_enabled = lucent_get_post_value_through_levels( 'qodef_woo_single_enable_design_info', get_queried_object_id() );

        if ( $sku_enabled && $single_product_layout === 'wide-gallery' ) : ?>
            <div class="qodef-sku-and-author-wrapper">

                <?php if ($sku_enabled) { ?>
                    <span class="sku_wrapper">
                        <span class="qodef-woo-meta-label"><?php esc_html_e( 'SKU:', 'lucent' ); ?></span>
                        <span class="sku qodef-woo-meta-value"><?php echo esc_attr( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'lucent' ); ?></span>
                    </span>
                <?php } ?>

                <?php if ($single_product_layout === 'wide-gallery' && $design_by_author_enabled === 'yes' && $is_enabled !== 'yes' ) {
                    echo '<span class="qodef-woo-author">' . sprintf('<span class="qodef-woo-meta-label">' . esc_html__( 'Design by', 'lucent' ) . '</span><span class="qodef-woo-meta-value"><a href="%1$s" target="_self">%2$s</a></span>', $url ,$autor_name) . '</span>';
                } ?>
            </div>
        <?php endif;
    }
}

if ( ! function_exists( 'lucent_woo_single_wide_gallery_reviews_heading' ) ) {
    /**
     * Function that changes reviews tab heading on wide gallery layout
     *
     * @return string which contains html content
     */
    function lucent_woo_single_wide_gallery_reviews_heading( $heading, $count, $product ) {

        $single_product_layout = lucent_get_post_value_through_levels( 'qodef_woo_single_layout', get_queried_object_id());

        $html = '';
        $html .= esc_html('Reviews', 'lucent');
        $html .= ' (';
        $html .= $product->get_review_count();
        $html .= ')';

        if ($single_product_layout === 'wide-gallery' ) {
            return $html;
        } else {
            return $heading;
        }
    }

    add_filter( 'woocommerce_reviews_title', 'lucent_woo_single_wide_gallery_reviews_heading', 10, 3 );
}

if ( ! function_exists( 'lucent_woo_single_change_review_gravatar_size' ) ) {
    /**
     * Function that changes product reviews gravatar size
     *
     * @return string which contains value
     */
    function lucent_woo_single_change_review_gravatar_size() {
        return 91;
    };

    add_filter( 'woocommerce_review_gravatar_size', 'lucent_woo_single_change_review_gravatar_size', 10, 1 );
}
