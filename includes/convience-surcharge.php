<?php

if ( ! defined( 'ABSPATH' ) ) exit;

if ($_POST['surcharge'] != "0.00" && $_POST['conveniencefee'] != "0.00") {
    $slimcdType = "Surcharge / convenience fee";
    $slimcdFee =  (float) sanitize_text_field($_POST['surcharge']) + (float) sanitize_text_field($_POST['conveniencefee']);
} else  if ($_POST['surcharge'] != "0.00" && $_POST['conveniencefee'] == "0.00") {
    $slimcdType = sanitize_text_field($_POST['receiptlabel']);
    $slimcdFee =  (float) sanitize_text_field($_POST['surcharge']);
} else if ($_POST['surcharge'] = "0.00" && $_POST['conveniencefee'] != "0.00") {
    $slimcdType = sanitize_text_field($_POST['receiptlabel']);
    $slimcdFee =  (float) sanitize_text_field($_POST['conveniencefee']);
}

/* removes full colon if there */
$slimcdType = rtrim($slimcdType, ':');

update_post_meta(wc_sanitize_order_id($_POST['order_id']), '_order_total', (float) $order->get_total() + $slimcdFee);

global $wpdb;
$wpdb->insert($wpdb->prefix . "woocommerce_order_items", array(
    'order_item_name' => $slimcdType,
    'order_item_type' => 'fee',
    'order_id' => wc_sanitize_order_id($_POST['order_id'])
));

$wpdb->insert($wpdb->prefix . "woocommerce_order_itemmeta", array(
    'meta_key' => '_line_total',
    'meta_value' => $slimcdFee,
    'order_item_id' => $wpdb->insert_id
));
