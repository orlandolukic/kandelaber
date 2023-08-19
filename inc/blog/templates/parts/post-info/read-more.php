<?php if ( ! post_password_required() ) { ?>
	<div class="qodef-e-read-more">
		<?php
		if ( lucent_post_has_read_more() ) {
			$button_params = array(
				'link'          => get_permalink() . '#more-' . get_the_ID(),
				'button_layout' => 'textual-with-arrow',
				'text'          => esc_html__( 'Continue Reading', 'lucent' ),
			);

			lucent_render_button_element( $button_params, 'qodef-textual-with-arrow' );

		} else {
			$button_params = array(
				'link'          => get_the_permalink(),
				'button_layout' => 'arrow',
				'text'          => esc_html__( '', 'lucent' ),
			);

			lucent_render_button_element( $button_params, 'qodef-arrow-only' );
		}

		?>
	</div>
<?php } ?>
