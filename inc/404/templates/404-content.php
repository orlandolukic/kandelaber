<div id="qodef-404-page">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <img class="not-found-image" src="<?= LUCENT_ASSETS_ROOT . '/img/404.png' ?>" />

                <h1 class="qodef-404-title"><?php echo esc_html( $title ); ?></h1>

                <p class="qodef-404-text"><?php echo esc_html( $text ); ?></p>

                <div class="qodef-404-button">
                    <a href="<?= get_home_url() ?>/proizvodi/">
                        <button class="submit-form-button button-404" onclick="window.showLoader(); window.reactMain.scrollToTop();">
                            <i aria-hidden="true" class="far fa-gem"></i>
                            <?= $button_text ?>
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>