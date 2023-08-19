<?php if ( isset( $query_result ) && intval( $query_result->max_num_pages ) > 1 ) { ?>
	<div class="qodef-m-pagination qodef--load-more" <?php echo lucent_is_installed( 'framework' ) ? qode_framework_get_inline_style( $pagination_type_load_more_top_margin ) : ''; ?>>
		<div class="qodef-m-pagination-inner">
			<?php if( !empty($params['load_more_post_text'])){
                $params['load_text'] = $params['load_more_post_text'];
            }
            else {
                $params['load_text'] = esc_html__( 'Load More', 'lucent' );
            }
			$button_params = array(
				'custom_class'  => 'qodef-load-more-button',
				'button_layout' => 'textual-with-arrow',
				'link'          => '#',
                'text'          => $params['load_text'],
			);
			
			lucent_render_button_element( $button_params ); ?>
		</div>
	</div>
	<?php
	// Include loading spinner
	lucent_render_icon( 'qodef-loading-spinner fa fa-spinner fa-spin', 'font-awesome', '' ); ?>
<?php } ?>
