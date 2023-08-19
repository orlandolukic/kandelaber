<header id="qodef-page-mobile-header">
	<?php
	// Hook to include additional content before page mobile header inner
	do_action( 'lucent_action_before_page_mobile_header_inner' );
	?>
	<div id="qodef-page-mobile-header-inner" <?php lucent_class_attribute( apply_filters( 'lucent_filter_mobile_header_inner_class', array(), 'mobile' ) ); ?>>
		<?php
		// Include module content template
		echo apply_filters( 'lucent_filter_mobile_header_content_template', lucent_get_template_part( 'mobile-header', 'templates/mobile-header-content' ) ); ?>
	</div>
	<?php
	// Hook to include additional content after page mobile header inner
	do_action( 'lucent_action_after_page_mobile_header_inner' );
	?>
</header>