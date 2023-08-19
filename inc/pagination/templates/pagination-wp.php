<?php if ( get_the_posts_pagination() !== '' ): ?>

    <div class="qodef-m-pagination qodef--wp">
		<?php
		// Load posts pagination (in order to override template use navigation_markup_template filter hook)
		the_posts_pagination( array(
            'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
            'next_text'          => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
            //'before_page_number' => '0',
		) ); ?>
    </div>

<?php endif; ?>
