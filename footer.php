			</div><!-- close #qodef-page-inner div from header.php -->
		</div><!-- close #qodef-page-outer div from header.php -->
		<?php
        // Hook to add content before footer template
        do_action( 'kandelaber_action_before_page_template_footer' );

		// Hook to include page footer template
		do_action( 'lucent_action_page_footer_template' );
		
		// Hook to include additional content before wrapper close tag
		do_action( 'lucent_action_before_wrapper_close_tag' );
		?>
	</div><!-- close #qodef-page-wrapper div from header.php -->
	<?php
	// Hook to include additional content before body tag closed
	do_action( 'lucent_action_before_body_tag_close' );
	?>
<?php wp_footer(); ?>
</body>
</html>