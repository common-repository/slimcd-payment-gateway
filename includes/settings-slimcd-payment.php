<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$slimcdHostCheck = array(
    '127.0.0.1',
    '::1'
);

if (!in_array($_SERVER['REMOTE_ADDR'], $slimcdHostCheck)) {

    $slimcdPostbackUrl = site_url() . '/?wc-api=postback_slimcd_payment';
    $slimcdRedirectUrl = site_url() . '/?wc-api=redirect_slimcd_payment';
} else {
    $slimcdPostbackUrl = "Not available on localhost";
    $slimcdRedirectUrl = "Not available on localhost";
}

$this->form_fields = apply_filters('slimcd_woo_pay_field', array(

    'post_back_url' => array(
        'title' => __('Post Back and Redirect URL in form settings', 'slimcd-payment-gateway'),
        'type' => 'title',
        'description' =>  sprintf(__('<b style="color:red">POST BACK URL: </b> %1$s <br> <b style="color:red">REDIRECT URL: </b> %2$s <a href="%2$s" target="_blank"> <br>For more details visit</a>', 'slimcd-payment-gateway'), $slimcdPostbackUrl, $slimcdRedirectUrl, 'https://stats.slimcd.com/soft/interface/default.asp'),
    ),

    'enabled' => array(
        'title' => __('Enable/Disable', 'slimcd-payment-gateway'),
        'type' => 'checkbox',
        'label' => __('Enable or Disable Slicm CD payment', 'slimcd-payment-gateway'),
        'default' => 'no'
    ),

    'title' => array(
        'title' => __('Title', 'slimcd-payment-gateway'),
        'type' => 'text',
        'description' => __('Add a new title for the slimcd payment gateway that consumers will see when they are in checkout page', 'slimcd-payment-gateway'),
        'default' => __('Slim CD payment gateway', 'slimcd-payment-gateway'),
        'desc_tip' => true
    ),

    'description' => array(
        'title' => __('Description', 'slimcd-payment-gateway'),
        'type' => 'textarea',
        'description' => __('Add a new description for the Slim CD payment gateway that consumers will see when they are in checkout page', 'slimcd-payment-gateway'),
        'default' => __('Please remit your payment for the delivery to be made', 'slimcd-payment-gateway'),
        'desc_tip' => true
    ),

    'instructions' => array(
        'title' => __('Instructions', 'slimcd-payment-gateway'),
        'type' => 'textarea',
        'description' => __('Instructions that will be added to the thank you page', 'slimcd-payment-gateway'),
        'default' => __('Default instructions', 'slimcd-payment-gateway'),
        'desc_tip' => true
    ),

    'slimcd_api_details' => array(
        'title' => __('API credentials', 'slimcd-payment-gateway'),
        'type' => 'title',
        'description' => sprintf(__('Enter your Slim CD API credentials to process payment. Learn how to access your <a href="%s" target="_blank">Slim CD credentials</a>.', 'slimcd-payment-gateway'), 'https://stats.slimcd.com/soft/interface/default.asp'),
    ),

    'slimcd_username' => array(
        'title' => __('Username', 'slimcd-payment-gateway'),
        'type' => 'text',
        'description' => __('Add your Slim CD username', 'slimcd-payment-gateway'),
        'desc_tip' => true,
    ),

    'slimcd_clientid' => array(
        'title' => __('Client id', 'slimcd-payment-gateway'),
        'type' => 'text',
        'description' => __('Add your Slim CD client id', 'slimcd-payment-gateway'),
        'desc_tip' => true,
    ),

    'slimcd_siteid' => array(
        'title' => __('Site id ', 'slimcd-payment-gateway'),
        'type' => 'text',
        'description' => __('Add your Slim CD siteid', 'slimcd-payment-gateway'),
        'desc_tip' => true,
    ),

    'slimcd_priceid' => array(
        'title' => __('Price id', 'slimcd-payment-gateway'),
        'type' => 'text',
        'description' => __('Add your Slim CD priceid', 'slimcd-payment-gateway'),
        'desc_tip' => true,
    ),


    'slimcd_password' => array(
        'title' => __('Password', 'slimcd-payment-gateway'),
        'type' => 'text',
        'description' => __('Add your Slim CD password', 'slimcd-payment-gateway'),
        'desc_tip' => true,
    ),

    'slimcd_wc_status_after_payment_details' => array(
        'title' => __('WooCommerce Status', 'slimcd-payment-gateway'),
        'type' => 'title',
        'description' => __('Enter desired WooCommerce status after payment completion ', 'slimcd-payment-gateway'),
    ),

    'slimcd_wc_status_after_payment' => array(
        'title' => __('Order status', 'slimcd-payment-gateway'),
        'type' => 'select',
        'options' => array('processing' => __('Processing', 'slimcd-payment-gateway'), 'completed' => __('Completed', 'slimcd-payment-gateway')),
        'description' => __('Add your the order status after payment', 'slimcd-payment-gateway'),
        'desc_tip' => true,
    ),

    'slimcd_formname_card_details' => array(
        'title' => __('Credit Card Options', 'slimcd-payment-gateway'),
        'type' => 'title',
        'description' => sprintf(__('Enter your Slim CD form name to process payment. Learn how to access your <a href="%s" target="_blank">Slim CD card payment</a>.', 'slimcd-payment-gateway'), 'https://stats.slimcd.com/soft/interface/default.asp'),
    ),

    'slimcd_formname_card' => array(
        'title' => __('Form name for card', 'slimcd-payment-gateway'),
        'type' => 'text',
        'description' => __('Add your Slim CD card form name', 'slimcd-payment-gateway'),
        'desc_tip' => true,
    ),

    'slimcd_formname_card_trans_type' => array(
        'title' => __('Transtype', 'slimcd-payment-gateway'),
        'type' => 'select',
        'options' => array('SALE' => __('SALE', 'slimcd-payment-gateway'), 'AUTH' => __('AUTH', 'slimcd-payment-gateway')),
        'description' => __('Add your Slim CD card form name', 'slimcd-payment-gateway'),
        'desc_tip' => true,
    ),

    'credit_card_surcharge_disclaimer' => array(
        'title' => __('Surcharge Disclaimer', 'slimcd-payment-gateway'),
        'type' => 'textarea',
        'description' => __('credit card surchage disclaimer to display in the checkout page for card', 'slimcd-payment-gateway'),
        'default' => __('Please be aware that credit cards will incur a surcharge, but debit/check card will not', 'slimcd-payment-gateway'),
        'desc_tip' => true
    ),

    'credit_card_convenience_fee_disclaimer' => array(
        'title' => __('Convenience fee disclaimer', 'slimcd-payment-gateway'),
        'type' => 'textarea',
        'description' => __('Convenience fee disclaimer to display in the checkout page for card', 'slimcd-payment-gateway'),
        'default' => __('There is a $4.95 convenience fee applied a checkout', 'slimcd-payment-gateway'),
        'desc_tip' => true
    ),



    'slimcd_formname_cheque_details' => array(
        'title' => __('Check payment details', 'slimcd-payment-gateway'),
        'type' => 'title',
        'description' => sprintf(__('Enter your Slim CD cheque payment form name to process payment. Learn how to access your <a href="%s" target="_blank">SlimCD cheque payment</a>.', 'slimcd-payment-gateway'), 'https://stats.slimcd.com/soft/interface/default.asp'),
    ),

    'slimcd_formname_cheque' => array(
        'title' => __('Form name for check', 'slimcd-payment-gateway'),
        'type' => 'text',
        'description' => __('Add your Slim CD formname for check', 'slimcd-payment-gateway'),
        'desc_tip' => true,
    ),

    'check_convenience_fee_disclaimer' => array(
        'title' => __('Convenience fee disclaimer', 'slimcd-payment-gateway'),
        'type' => 'textarea',
        'description' => __('Convenience fee disclaimer to display in the checkout page for check', 'slimcd-payment-gateway'),
        'default' => __('There is a $4.95 convenience fee applied a checkout', 'slimcd-payment-gateway'),
        'desc_tip' => true
    ),

));
