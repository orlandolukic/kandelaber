<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php if ( is_singular() && pings_open( get_queried_object() ) ) { ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php } ?>

    <script type="text/javascript">
        window.currentPage = "opened-category";
    </script>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">
<?php
// Hook to include default WordPress hook after body tag open
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
}

// Hook to include additional content after body tag open
do_action( 'lucent_action_after_body_tag_open' );
?>
<div id="qodef-page-wrapper" class="<?php echo esc_attr( lucent_get_page_wrapper_classes() ); ?>">
    <?php
    // Hook to include page header template
    do_action( 'lucent_action_page_header_template' ); ?>
    <div id="qodef-page-outer">
        <div id="qodef-page-inner" class="<?php echo esc_attr( lucent_get_page_inner_classes() ); ?>">
<?php

// Print footer
get_footer();
