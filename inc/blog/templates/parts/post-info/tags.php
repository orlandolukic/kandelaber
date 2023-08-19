<?php
$tags_enabled = lucent_get_post_value_through_levels( 'qodef_blog_list_enable_tags' ) !== 'no';
$tags         = get_the_tags();

if ( ( $tags && $tags_enabled ) || ( $tags && is_single() ) ) { ?>
	<div class="qodef-e-info-item qodef-e-info-tags">
		<?php the_tags( '', '', '' ); ?>
	</div>
<?php } ?>
