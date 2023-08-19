<?php if ( isset( $query_result ) && intval( $query_result->max_num_pages ) > 1 ) { ?>
	<div class="qodef-m-pagination qodef--standard">
		<div class="qodef-m-pagination-inner">
			<nav class="qodef-m-pagination-items" role="navigation">
				<div class="qodef-m-pagination-item qodef--prev">
					<a href="#" data-paged="1">
<!--						--><?php //lucent_render_icon( 'fas fa-angle-left', 'font-awesome', '' ); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
					</a>
				</div>
				<?php for ( $i = 1; $i <= intval( $query_result->max_num_pages ); $i ++ ) {
					$classes     = $i === 1 ? 'qodef--active' : '';
					$formatted_i = sprintf( "%2d", $i );
					?>
					<div class="qodef-m-pagination-item qodef--number qodef--number-<?php echo esc_attr( $i ); ?> <?php echo esc_attr( $classes ); ?>">
						<a href="#" data-paged="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $formatted_i ); ?></a>
					</div>
				<?php } ?>
				<div class="qodef-m-pagination-item qodef--next">
					<a href="#" data-paged="2">
<!--						--><?php //lucent_render_icon( 'fas fa-angle-right', 'font-awesome', '' ); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
					</a>
				</div>
			</nav>
		</div>
	</div>
<?php } ?>
