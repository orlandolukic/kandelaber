<?php

class Product_Category_Listing_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'product-category-listing';
    }

    public function get_title() {
        return 'Prikaz kategorija proizvoda';
    }

    public function get_icon() {
        return 'fa fa-home';
    }

    public function get_categories() {
        return ['general'];
    }

    protected function register_controls() {}

    protected function render() {
        ?>
        <div id="product-category-listing"></div>
    <?php
    }

    protected function content_template() {}
}