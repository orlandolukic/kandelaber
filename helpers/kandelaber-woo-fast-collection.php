<?php

if ( ! class_exists('KandelaberWooFastCollection') ) {

    class KandelaberWooFastCollection {

        private static $instance;

        public static function get() {
            if (self::$instance == NULL) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        public function __construct() {

            add_action( 'woocommerce_product_data_panels', array($this, 'custom_tab_panel'));
            add_action( 'woocommerce_process_product_meta', array($this, 'save_custom_tab_data') );

            add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_custom_tab_content_to_tabs' ) );
            add_filter( 'woocommerce_product_meta_start', array($this, 'display_custom_tab_data_in_product_meta') );

        }

        public function add_custom_tab_content_to_tabs($tabs) {
            $tabs['custom_tab'] = array(
                'label'    => __('Brza kolekcija', 'kandelaber'),
                'target'   => 'fast_collection_tab',
                'class'    => array(),
                'priority' => 21,
            );
            return $tabs;
        }

        public function custom_tab_panel() {
            global $post;
            $id = $post->ID;

            $fast_collection_products = $this->get_fast_collection_products();

            $is_active      = get_post_meta($id, 'fast_collection_is_active', true);
            $title          = get_post_meta($id, 'fast_collection_title', true);
            $subtitle       = get_post_meta($id, 'fast_collection_subtitle', true);
            $title_icon     = get_post_meta($id, 'fast_collection_title_icon', true);
            $button_text    = get_post_meta($id, 'fast_collection_button_text', true);
            $button_icon    = get_post_meta($id, 'fast_collection_button_icon', true);
            $modal_title    = get_post_meta($id, 'fast_collection_modal_title', true);
            $modal_subtitle = get_post_meta($id, 'fast_collection_modal_subtitle', true);
            $modal_icon     = get_post_meta($id, 'fast_collection_modal_icon', true);
            $activated      = get_post_meta($id, 'fast_collection_activated', true);

            // Content for your custom tab
            ?>
            <div id="fast_collection_tab" class="panel woocommerce_options_panel">
                <div class="options_group">
                    <?php

                    woocommerce_wp_checkbox(
                        array(
                            'id'          =>  'fast_collection_is_active',
                            'label'       =>  __('Aktivno', 'kandelaber'),
                            'placeholder' =>  __('Da li je aktivna brza kolekcija', 'kandelaber'),
                            'value'       => $is_active
                        )
                    );

                    woocommerce_wp_text_input(
                        array(
                            'id'          => 'fast_collection_title',
                            'label'       =>  __('Naslov', 'kandelaber'),
                            'value'       => $title
                        )
                    );

                    woocommerce_wp_text_input(
                        array(
                            'id'          => 'fast_collection_title_icon',
                            'label'       =>  __('Ikonica naslova', 'kandelaber'),
                            'value'       => $title_icon
                        )
                    );

                    woocommerce_wp_text_input(
                        array(
                            'id'          => 'fast_collection_subtitle',
                            'label'       =>  __('Podnaslov', 'kandelaber'),
                            'value'       => $subtitle
                        )
                    );

                    woocommerce_wp_text_input(
                        array(
                            'id'          => 'fast_collection_button_text',
                            'label'       =>  __('Tekst dugmeta', 'kandelaber'),
                            'value'       => $button_text
                        )
                    );

                    woocommerce_wp_text_input(
                        array(
                            'id'          => 'fast_collection_button_icon',
                            'label'       =>  __('Ikonica dugmeta', 'kandelaber'),
                            'value'       => $button_icon
                        )
                    );

                    ?>
                </div>
                <div class="options_group">
                    <?php

                    woocommerce_wp_text_input(
                        array(
                            'id'          => 'fast_collection_modal_title',
                            'label'       =>  __('Naslov modala', 'kandelaber'),
                            'value'       => $modal_title
                        )
                    );

                    woocommerce_wp_text_input(
                        array(
                            'id'          => 'fast_collection_modal_subtitle',
                            'label'       =>  __('Podnaslov modala', 'kandelaber'),
                            'value'       => $modal_subtitle
                        )
                    );

                    woocommerce_wp_text_input(
                        array(
                            'id'          => 'fast_collection_modal_icon',
                            'label'       =>  __('Ikonica modala', 'kandelaber'),
                            'value'       => $modal_icon
                        )
                    );

                    ?>
                </div>
                <?php
                if ($fast_collection_products->have_posts()) {
                ?>
                <div class="option-group">
                    <?php

                    $products = $fast_collection_products->get_posts();
                    for ($i=0; $i<count($products); $i++) {
                        $product = $products[$i];
                        woocommerce_wp_checkbox(
                            array(
                                'id'          =>  'fast_collection_activated_' .$product->ID,
                                'label'       =>  __('Proizvod \'<strong>' . $product->post_title . '</strong>\'', 'kandelaber'),
                                'description' => __('<strong>' . $product->fast_collection_title . '</strong> (' . $product->fast_collection_subtitle . ')', 'kandelaber'),
                                'value'       => isset($activated["product_" . $product->ID]) ? $activated["product_" . $product->ID] : false
                            )
                        );
                    }

                    ?>
                </div>
                <?php
                }
                ?>

            </div>
            <script type="text/javascript">
                (function ($) {
                    let isActive = <?= $is_active === 'yes' ? 'true' : 'false' ?>;
                    let values = {
                        title: null,
                        title_icon: null,
                        subtitle: null,
                        button_text: null,
                        button_icon: null,
                        modal_title: null,
                        modal_subtitle: null,
                        modal_icon: null,
                    };
                    $("#fast_collection_title").attr("disabled", !isActive);
                    $("#fast_collection_title_icon").attr("disabled", !isActive);
                    $("#fast_collection_subtitle").attr("disabled", !isActive);
                    $("#fast_collection_button_text").attr("disabled", !isActive);
                    $("#fast_collection_button_icon").attr("disabled", !isActive);
                    $("#fast_collection_modal_title").attr("disabled", !isActive);
                    $("#fast_collection_modal_subtitle").attr("disabled", !isActive);
                    $("#fast_collection_modal_icon").attr("disabled", !isActive);

                    $("#fast_collection_is_active").on("click", function (e) {

                        if (!this.checked) {
                            values.title = $("#fast_collection_title").val();
                            values.title_icon = $("#fast_collection_title_icon").val();
                            values.subtitle = $("#fast_collection_subtitle").val();
                            values.button_text = $("#fast_collection_button_text").val();
                            values.button_icon = $("#fast_collection_button_icon").val();
                            values.modal_title = $("#fast_collection_modal_title").val();
                            values.modal_subtitle = $("#fast_collection_modal_subtitle").val();
                            values.modal_icon = $("#fast_collection_modal_icon").val();
                            $("#fast_collection_title").val('');
                            $("#fast_collection_title_icon").val('');
                            $("#fast_collection_subtitle").val('');
                            $("#fast_collection_button_text").val('');
                            $("#fast_collection_button_icon").val('');
                            $("#fast_collection_modal_title").val('');
                            $("#fast_collection_modal_subtitle").val('');
                            $("#fast_collection_modal_icon").val('');
                        } else {
                            $("#fast_collection_title").val(values.title);
                            $("#fast_collection_title_icon").val(values.title_icon);
                            $("#fast_collection_subtitle").val(values.subtitle);
                            $("#fast_collection_button_text").val(values.button_text);
                            $("#fast_collection_button_icon").val(values.button_icon);
                            $("#fast_collection_modal_title").val(values.modal_title);
                            $("#fast_collection_modal_subtitle").val(values.modal_subtitle);
                            $("#fast_collection_modal_icon").val(values.modal_icon);
                        }

                        $("#fast_collection_title").attr("disabled", !this.checked);
                        $("#fast_collection_title_icon").attr("disabled", !this.checked);
                        $("#fast_collection_subtitle").attr("disabled", !this.checked);
                        $("#fast_collection_button_text").attr("disabled", !this.checked);
                        $("#fast_collection_button_icon").attr("disabled", !this.checked);
                        $("#fast_collection_modal_title").attr("disabled", !this.checked);
                        $("#fast_collection_modal_subtitle").attr("disabled", !this.checked);
                        $("#fast_collection_modal_icon").attr("disabled", !this.checked);
                    })
                })(jQuery);
            </script>
            <?php
        }

        public function save_custom_tab_data($product_id) {

            $fast_collection_products = $this->get_fast_collection_products();

            $is_active = isset($_POST['fast_collection_is_active']) ? wc_clean(wp_unslash($_POST['fast_collection_is_active'])) : false;
            $title = isset($_POST['fast_collection_title']) ? wc_clean(wp_unslash($_POST['fast_collection_title'])) : '';
            $subtitle = isset($_POST['fast_collection_subtitle']) ? wc_clean(wp_unslash($_POST['fast_collection_subtitle'])) : '';
            $title_icon = isset($_POST['fast_collection_title_icon']) ? wc_clean(wp_unslash($_POST['fast_collection_title_icon'])) : '';
            $button_text = isset($_POST['fast_collection_button_text']) ? wc_clean(wp_unslash($_POST['fast_collection_button_text'])) : '';
            $button_icon = isset($_POST['fast_collection_button_icon']) ? wc_clean(wp_unslash($_POST['fast_collection_button_icon'])) : '';
            $modal_title = isset($_POST['fast_collection_modal_title']) ? wc_clean(wp_unslash($_POST['fast_collection_modal_title'])) : '';
            $modal_subtitle = isset($_POST['fast_collection_modal_subtitle']) ? wc_clean(wp_unslash($_POST['fast_collection_modal_subtitle'])) : '';
            $modal_icon = isset($_POST['fast_collection_modal_icon']) ? wc_clean(wp_unslash($_POST['fast_collection_modal_icon'])) : '';


            $activated = array();
            $products = $fast_collection_products->get_posts();
            for ($i=0; $i < count($products); $i++) {
                $product = $products[$i];
                $activated["product_" . $product->ID] = isset($_POST['fast_collection_activated_' . $product->ID])
                    ? wc_clean(wp_unslash($_POST['fast_collection_activated_' . $product->ID])) : false;
            }

            update_post_meta($product_id, 'fast_collection_is_active', $is_active);
            update_post_meta($product_id, 'fast_collection_title', $title);
            update_post_meta($product_id, 'fast_collection_subtitle', $subtitle);
            update_post_meta($product_id, 'fast_collection_title_icon', $title_icon);
            update_post_meta($product_id, 'fast_collection_button_text', $button_text);
            update_post_meta($product_id, 'fast_collection_button_icon', $button_icon);
            update_post_meta($product_id, 'fast_collection_modal_title', $modal_title);
            update_post_meta($product_id, 'fast_collection_modal_subtitle', $modal_subtitle);
            update_post_meta($product_id, 'fast_collection_modal_icon', $modal_icon);
            update_post_meta($product_id, 'fast_collection_activated', $activated);

        }

        public function display_custom_tab_data_in_product_meta($post_id) {
            $custom_tab_field = get_post_meta($post_id, 'custom_tab_field', true);

            if ($custom_tab_field) {
                echo '<span class="wc-custom-tab-data">' . esc_html($custom_tab_field) . '</span>';
            }
        }

        private function get_fast_collection_products() {
            global $post;
            $id = $post->ID;

            $args = array(
                'post_type' => 'product',
                'post__not_in' => array($id),
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'fast_collection_is_active',
                        'compare' => '=',
                        'value' => 'yes',
                    ),
                    array(
                        'relation' => "OR",
                        array(
                            'key' => 'fast_collection_title',
                            'compare' => 'EXISTS',
                        ),
                        array(
                            'key' => 'fast_collection_subtitle',
                            'compare' => 'EXISTS',
                        ),
                    )
                ),
                'posts_per_page' => -1,

            );
            return new WP_Query($args);
        }

        public function get_fast_collection_metadata($id) {
            $title          = get_post_meta($id, 'fast_collection_title', true);
            $subtitle       = get_post_meta($id, 'fast_collection_subtitle', true);
            $title_icon     = get_post_meta($id, 'fast_collection_title_icon', true);
            $button_text    = get_post_meta($id, 'fast_collection_button_text', true);
            $button_icon    = get_post_meta($id, 'fast_collection_button_icon', true);
            $modal_title    = get_post_meta($id, 'fast_collection_modal_title', true);
            $modal_subtitle = get_post_meta($id, 'fast_collection_modal_subtitle', true);
            $modal_icon     = get_post_meta($id, 'fast_collection_modal_icon', true);
            return array(
                "title" => $title,
                "subtitle" => $subtitle,
                "title_icon" => $title_icon,
                "button_text" => $button_text,
                "button_icon" => $button_icon,
                "modal_title" => $modal_title,
                "modal_subtitle" => $modal_subtitle,
                "modal_icon" => $modal_icon,
            );
        }


    }

    // Create cutom fields for WooCommerce edit page
    KandelaberWooFastCollection::get();
}