<?php
$additional_info_enabled  = lucent_get_post_value_through_levels( 'qodef_blog_list_enable_additional_info' ) !== 'no';
$excerpt_enabled  = lucent_get_post_value_through_levels( 'qodef_blog_list_enable_excerpt' ) !== 'no';
?>

<article <?php post_class( 'qodef-blog-item qodef-e' ); ?>>
	<div class="qodef-e-inner">
		<?php
		// Include post media
		lucent_template_part( 'blog', 'templates/parts/post-info/media' );
		?>
		<div class="qodef-e-content">
			<div class="qodef-e-info qodef-info--top">
				<?php
				// Include post category info
				lucent_template_part( 'blog', 'templates/parts/post-info/category' );

                // Include post date info
                lucent_template_part( 'blog', 'templates/parts/post-info/date' );

                // Include post author info
                lucent_template_part( 'blog', 'templates/parts/post-info/author' );
				?>
			</div>
			<div class="qodef-e-text">
				<?php
				// Include post title
				lucent_template_part( 'blog', 'templates/parts/post-info/title', '', array( 'title_tag' => 'h3' ) );
                if ( $excerpt_enabled ) {
                    // Include post excerpt
                    lucent_template_part('blog', 'templates/parts/post-info/excerpt');
                }
				// Hook to include additional content after blog single content
				do_action( 'lucent_action_after_blog_single_content' );
				?>
			</div>
			<div class="qodef-e-info qodef-info--bottom">
				<div class="qodef-e-info-left">
					<?php
					// Include post read more
					lucent_template_part( 'blog', 'templates/parts/post-info/read-more' );
					?>
				</div>
				<div class="qodef-e-info-right">
                    <?php

                        // Include post tag info
                        lucent_template_part('blog', 'templates/parts/post-info/tags');

                    if ( $additional_info_enabled ) {
                        // Include post comments info
                        lucent_template_part('blog', 'templates/parts/post-info/comments');
                    }
					?>
				</div>
			</div>
		</div>
	</div>
</article>
