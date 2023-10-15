<?php if ( has_nav_menu( 'main-navigation' ) ) : ?>
<div class="col-9">
	<nav class="qodef-header-navigation qodef-header-navigation-initial" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'lucent' ); ?>">
		<?php wp_nav_menu( array(
			'theme_location' => 'main-navigation',
			'container'      => '',
			'link_before'    => '<span class="qodef-menu-item-textdsd">dsadsd',
			'link_after'     => '</span>',
		) ); ?>
	</nav>
</div>
<?php endif; ?>