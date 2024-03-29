<?php
// Hook to include additional content before page header
do_action( 'lucent_action_before_page_header' );
?>
<header id="qodef-page-header" <?php lucent_class_attribute( apply_filters( 'lucent_filter_header_class', array() ) ); ?>>
	<?php
	// Hook to include additional content before page header inner
	do_action( 'lucent_action_before_page_header_inner' );

    // Include top info bar
    lucent_template_part( 'header', 'templates/parts/top-info-bar' );
	?>
	<div id="qodef-page-header-inner" <?php lucent_class_attribute( apply_filters( 'lucent_filter_header_inner_class', array(), 'default' ) ); ?>>
       <div class="container">
           <div class="row">
               <?php
               // Include module content template
               echo apply_filters( 'lucent_filter_header_content_template', lucent_get_template_part( 'header', 'templates/header-content' ) ); ?>
           </div>
       </div>
	</div>
	<?php
	// Hook to include additional content after page header inner
	do_action( 'lucent_action_after_page_header_inner' );
	?>
</header>