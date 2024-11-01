<?php
class slimcd_payment_gateway extends WC_Payment_Gateway
{
    protected $instructions;

    /**
     * Constructor for the gateway.
     */
    
    public function __construct()
    {

        $this->id = 'slimcd_payment';
        $this->icon = apply_filters('slimcd_icon', plugins_url('../assets/images/icon.png', __FILE__));
        $this->has_fields = false;
        $this->method_title = __('Slim CD', 'slimcd-payment-gateway');
        $this->method_description  = __('Online payments using Slim CD Hosted Payment Pages.', 'slimcd-payment-gateway');
        $this->order_button_text = __('PROCEED TO PAYMENT', 'slimcd-payment-gateway');
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->instructions = $this->get_option('instructions');

        $this->init_form_fields();
        $this->init_settings();
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        add_action('woocommerce_api_postback_' . $this->id, array($this, 'slimcd_postback'));
        add_action('woocommerce_api_redirect_' . $this->id, array($this, 'slimcd_redirect'));
        add_action('woocommerce_thankyou_' . $this->id, array($this, 'slimcd_thankYouPage'));
        add_action('wp_footer', array($this, 'slimcd_loadScript'));
        add_action('wp_enqueue_scripts', array($this, 'slimcd_load_styles'));
        add_filter('woocommerce_gateway_description',  'slimcd_description_fileds_options', 20, 2);
    }




    /**
     * Initialise Gateway Settings Form Fields.
     */
    public function init_form_fields()
    {
        include __DIR__ . '/settings-slimcd-payment.php';
    }



    /**
     * Process the payment and return the result.
     *
     * @param int $order_id Order ID.
     * @return array
     * Add notice if error happens in payment process
     */
    public function process_payment($order_id)
    {

        $order = wc_get_order($order_id);
        if ($order->get_total() > 0) {

            if (!empty($this->get_option('slimcd_formname_card')) && !empty($this->get_option('slimcd_formname_cheque'))) {

                if ($_POST['payment_mode'] == "payment_mode_card") {

                    $formname = trim($this->get_option('slimcd_formname_card'));
                    $transtype = trim($this->get_option('slimcd_formname_card_trans_type'));
                    $paymentTitle = $this->get_option('title') . '(Credit Card)';
                } else  if ($_POST['payment_mode'] == "payment_mode_cheque") {
                    $formname = trim($this->get_option('slimcd_formname_cheque'));
                    $transtype = "SALE";
                    $paymentTitle = $this->get_option('title') . '(Checking Account)';
                }
            } else if (!empty($this->get_option('slimcd_formname_card')) && empty($this->get_option('slimcd_formname_cheque'))) {
                $formname = trim($this->get_option('slimcd_formname_card'));
                $transtype = trim($this->get_option('slimcd_formname_card_trans_type'));
                $paymentTitle = $this->get_option('title') . '(Credit Card)';
            } else {
                $formname = trim($this->get_option('slimcd_formname_cheque'));
                $transtype = "SALE";
                $paymentTitle = $this->get_option('title') . '(Checking Account)';
            }

            $receiptlabel =  (isset($_POST['receiptlabel'])) ? sanitize_text_field($_POST['receiptlabel']) : 'null';

            update_post_meta($order_id, '_payment_method_title', $paymentTitle);
            $response = $this->slimcd_generateSession($order, $formname, $transtype, $receiptlabel);
            if ($response && $response['response'] === "Success") {
                $redirect = 'https://stats.slimcd.com/soft/showsession.asp?sessionid=' . $response['sessionid'];
                return array(
                    'result'   => 'success',
                    'redirect' =>  $redirect,
                );
            } else {

                wc_add_notice($response['response'] . ' : ' .  $response['description'] . ' - Unable to process Slimcd payment gateway ', 'error');
            }
        }
    }

    /**
     * Generate SlimCD Session
     * @param $order
     * @return array
     */

    private function slimcd_generateSession($order, $formname, $transtype, $receiptlabel)
    {

        $data = array(
            "username" => trim($this->get_option('slimcd_username')),
            "clientid" => trim($this->get_option('slimcd_clientid')),
            "siteid" => trim($this->get_option('slimcd_siteid')),
            "priceid" => trim($this->get_option('slimcd_priceid')),
            "password" => $this->get_option('slimcd_password'),
            "formname" => $formname,
            "transtype" => $transtype,
            "amount" => $order->get_total(),
            "first_name" => $order->get_billing_first_name() ??  null,
            "last_name" => $order->get_billing_last_name() ?? null,
            "address" => $order->get_billing_address_1() ?? null,
            "city"  => $order->get_billing_city() ?? null,
            "state" =>  $order->get_billing_state() ?? null,
            "zip" =>  $order->get_billing_postcode() ?? null,
            "order_id" => $order->get_id(),
            "receiptlabel" => $receiptlabel,
            "company_name" => $order->get_billing_company() ?? null,
            "customer_note" => $order->get_customer_note() ?? null,
            "email" => $order->get_billing_email() ?? null,
        );


        $response = wp_remote_post('https://stats.slimcd.com/soft/json/jsonscript.asp?service=CreateSession', array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'        => json_encode($data),

        ));
        if (is_wp_error($response)) {
            return array(
                "response" => "Error",
                "description" => "Error while accessing API"
            );
        } else {
            $result = json_decode(wp_remote_retrieve_body($response));
            if ($result->reply->response === "Success") {
                return array(
                    "response" => $result->reply->response,
                    "sessionid" => $result->reply->datablock->sessionid
                );
            } else {
                return array(
                    "response" => $result->reply->response,
                    "description" => $result->reply->description
                );
            }
        }
    }
    /**
     * Post back url for form settings
     *
     * @return void
     */
    public function slimcd_postback()
    {
        header('Content-Type: text/plain');
        $postBackStatus = "Not OK";
        if (isset($_POST['approved']) && isset($_POST['order_id'])) {
            if ("Success" == $this->slimcd_payment_status(sanitize_key($_POST['sessionid']))) {
            $order_id = wc_sanitize_order_id($_POST['order_id']);
            $order = wc_get_order($order_id);
                if ($order->get_payment_method() == "slimcd_payment" && $_POST['approved'] == "Y" || $_POST['approved'] == "B") {
                    if (isset($_POST['surcharge']) && $_POST['surcharge'] != 0.00 || isset($_POST['conveniencefee']) && $_POST['conveniencefee'] != 0.00) {
                        include __DIR__ . '/convience-surcharge.php';
                    }
                    $this->slimcd_update_order_status($order_id);
                    add_post_meta($order_id, '_transaction_id', (int)$_POST['gateid']);
                    $postBackStatus = "OK";
                }
            }
        }
        echo $postBackStatus;
        die();
    }

    public function slimcd_update_order_status($order_id)
    {
        $order = wc_get_order($order_id);
        if (empty($this->get_option('slimcd_wc_status_after_payment'))) {
            $cartStatus = "processing";
        } else {
        $cartStatus = trim($this->get_option('slimcd_wc_status_after_payment'));
        }
        $order->update_status($cartStatus);
        WC()->cart->empty_cart();
    }

    /**
     * Redirect url for form settings
     *
     * @return void
     */
    public function slimcd_redirect()
    {

        if (!empty($_GET['order_id'])) {

            $order_id = wc_sanitize_order_id($_GET['order_id']);
            $order = wc_get_order($order_id );
            $currentOrderStatus = $order->get_status();
            if ($order->get_payment_method() == "slimcd_payment" && ($currentOrderStatus == "processing" || $currentOrderStatus == "completed")) {
                wp_redirect($order->get_checkout_order_received_url());
            } elseif ($_GET && $_GET['sessionid'] !== "") {
                if ("Success" == $this->slimcd_payment_status(sanitize_key($_GET['sessionid']))) {
                    wp_redirect($order->get_checkout_order_received_url());
                } else {
                    wc_add_notice('Issue in payment, Please choose another payment method', 'error');
                    wp_redirect(wc_get_checkout_url());
                }
            }
        }
        die();
    }
    /**
     * Check the status of payment
     *
     * @param string $sessionid
     * @return null|string
     *
     */
    private function slimcd_payment_status($sessionid = "")
    {
        $data = array(
            "username" => trim($this->get_option('slimcd_username')),
            "password" => $this->get_option('slimcd_password'),
            "sessionid" => $sessionid,
            "wait" => "5",
            "waitforcompleted" => "no",

        );

        $response = wp_remote_post('https://stats.slimcd.com/soft/json/jsonscript.asp?service=CheckSession', array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
            'body'        => json_encode($data),

        ));
        if (is_wp_error($response)) {
            return array(
                "response" => "Error",
            );
        } else {
            $result = json_decode(wp_remote_retrieve_body($response));
            if ($result->reply->response === "Success") {

                return $result->reply->response;
            } else {
                return;
            }
        }
    }

    /**
     * Loading scripts
     */

    public function slimcd_loadScript()
    {
        if (!is_admin()) {
            wp_register_script('slimcd_script', plugins_url('../assets/js/slimcd_script.js', __FILE__));
            wp_enqueue_script('slimcd_script');
        }
    }

    function slimcd_load_styles()
    {
        if (!is_admin()) {
            wp_register_style('slimcd_checkout_description_filed_style', plugins_url('../assets/styles/style.css', __FILE__));
            wp_enqueue_style('slimcd_checkout_description_filed_style');
        }
    }

    /**
     * Output for the order received page.
     */
    public function slimcd_thankYouPage()
    {
        if ($this->instructions) {
            echo wp_kses_post(wpautop(wptexturize($this->instructions)));
        }
    }
}
