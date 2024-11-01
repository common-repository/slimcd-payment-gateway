<?php

/**
 * Plugin Name: SlimCD payment gateway
 * Plugin URI: https://slimcd.com/woocommerce/
 * Description: Slim CD’s gateway system was designed to allow merchants to take any kind of electronic payment with a single piece of software – quickly, easily, painlessly, from any PC.
 * Version: 1.0.3
 * Author: SlimCD
 * Author URI: https://slimcd.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: slimcd-payment-gateway
 * Domain Path: /languages/
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return;

add_action('plugins_loaded', 'slim_payment_gateway_init', 11);


/**
 * initilizing slimcd payment gateway
 */
function slim_payment_gateway_init()
{
    if (class_exists('WC_Payment_Gateway')) {

        include __DIR__ . '/includes/class-slimcd-payment-gateway.php';
        include __DIR__ . '/includes/slimcd-checkout-description-fields.php';
    }
}

add_filter('woocommerce_payment_gateways', 'slimcd_payment_gateway');

/**
 * Adding payment gateways.
 *
 * @return array
 */
function slimcd_payment_gateway($gateways)
{
    $gateways[] = 'slimcd_payment_gateway';
    return $gateways;
}
