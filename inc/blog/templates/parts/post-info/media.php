<div class="qodef-e-media">
	<?php switch ( get_post_format() ) {
		case 'gallery':
			lucent_template_part( 'blog', 'templates/parts/post-format/gallery' );
			break;
		case 'video':
			lucent_template_part( 'blog', 'templates/parts/post-format/video' );
			break;
		case 'audio':
			lucent_template_part( 'blog', 'templates/parts/post-format/audio' );
			break;
		default:
			lucent_template_part( 'blog', 'templates/parts/post-info/image' );
			break;
	} ?>
</div>