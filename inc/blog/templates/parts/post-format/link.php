<?php
$link_url_meta  = get_post_meta( get_the_ID(), 'qodef_post_format_link', true );
$link_url       = ! empty( $link_url_meta ) ? $link_url_meta : get_the_permalink();
$link_text_meta = get_post_meta( get_the_ID(), 'qodef_post_format_link_text', true );

if ( ! empty( $link_url ) ) {
	$link_text = ! empty( $link_text_meta ) ? $link_text_meta : get_the_title();
	$title_tag = isset( $title_tag ) && ! empty( $title_tag ) ? $title_tag : 'h5';
	?>
	<div class="qodef-e-link">
		<span class="qodef-e-link-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="37" height="37" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-link"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
        </span>
		<<?php echo esc_attr( $title_tag ); ?> class="qodef-e-link-text"><?php echo esc_html( $link_text ); ?></<?php echo esc_attr( $title_tag ); ?>>
		<a itemprop="url" class="qodef-e-link-url" href="<?php echo esc_url( $link_url ); ?>" target="_blank"></a>
	</div>
<?php } ?>
