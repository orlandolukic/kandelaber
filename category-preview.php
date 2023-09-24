<?php


get_header();

$product_handler = KandelaberProductsHandler::get_instance();

if ($product_handler->get_is_only_category()) {
    $queryVar = $product_handler->get_category();
    echo $queryVar;
} else {
    $queryVar = $product_handler->get_subcategory();
    echo $queryVar;
}


get_footer();
