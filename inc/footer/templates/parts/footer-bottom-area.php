<?php if ( lucent_is_footer_bottom_area_enabled() ) { ?>
	<div id="qodef-page-footer-bottom-area">
		<div id="qodef-page-footer-bottom-area-inner" class="<?php echo esc_attr( lucent_get_footer_bottom_area_classes() ); ?>">
			<div class="<?php echo esc_attr( lucent_get_footer_bottom_area_columns_classes() ); ?>">
				<div class="qodef-grid-inner clear">
                    <div class="qodef-grid-item">
                        <?php lucent_get_footer_widget_area( 'bottom', 1 ); ?>
                    </div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>