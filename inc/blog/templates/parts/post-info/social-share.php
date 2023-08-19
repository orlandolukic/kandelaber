<?php if ( class_exists( 'LucentCoreSocialShareShortcode' ) ) { ?>
	<div class="qodef-e-info-item qodef-e-info-social-share">
		<?php
		$params = array();
		$params['layout'] = 'dropdown';
        $params['title'] = '';
		
		echo LucentCoreSocialShareShortcode::call_shortcode( $params ); ?>
	</div>
<?php } ?>
