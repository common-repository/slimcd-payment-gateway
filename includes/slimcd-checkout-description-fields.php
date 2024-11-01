<?php

function slimcd_description_fileds_options($description, $payment_id)
{
    if ('slimcd_payment' !== $payment_id) {
        return $description;
    }
    $cartCurrency = get_woocommerce_currency();
    if ($cartCurrency == "USD" || $cartCurrency == "CAD") {
        $slimcd_settings = WC()->payment_gateways->payment_gateways()['slimcd_payment'];
        if ((empty($slimcd_settings->settings['slimcd_formname_card']) && empty($slimcd_settings->settings['slimcd_formname_cheque'])) || empty($slimcd_settings->settings['slimcd_username'])) {
            $slimcdDescription = slimcd_description_no_forms($description);
        } else if (!empty($slimcd_settings->settings['slimcd_formname_card']) && !empty($slimcd_settings->settings['slimcd_formname_cheque'])) {
            $slimcdDescription =  slimcd_description_fileds($slimcd_settings, $description);
        } else if (!empty($slimcd_settings->settings['slimcd_formname_card']) && empty($slimcd_settings->settings['slimcd_formname_cheque'])) {
            $slimcdDescription =  slimcd_description_fileds_card($slimcd_settings, $description);
        } else if (empty($slimcd_settings->settings['slimcd_formname_card']) && !empty($slimcd_settings->settings['slimcd_formname_cheque'])) {
            $slimcdDescription =  slimcd_description_fileds_check($slimcd_settings, $description);
        }
    } else {
        $slimcdDescription = slimcd_description_not_supported_currency($description);
    }

    return $slimcdDescription;
}

/* Error message for un-supported currencies*/
function slimcd_description_not_supported_currency($description)
{
    ob_start();
    echo "<div id='slimcdDisclaimerError' class='text-danger'><p>Error: Payment currency must be USD or CAD</p></div>";
    $description .= ob_get_clean();
    return $description;
}


/** Error message when form is not are not available */

function slimcd_description_no_forms($description)
{
    ob_start();
    echo "<div id='slimcdDisclaimerError' class='text-danger'><p>Error: API or form info is missing. Please contact site administrator</p></div>";
    $description .= ob_get_clean();
    return $description;
}

/** Form for both card and check payment */
function slimcd_description_fileds($slimcd_settings, $description)
{
    ob_start();
    woocommerce_form_field(
        'payment_mode',
        array(
            'type' => 'radio',
            'label' => __('Payment Method:', 'slimcd-payment-gateway'),
            'class' => array('slimcd_payment_options'),
            'required' => true,
            'checked' => 'checked',
            'default' => 'payment_mode_card',
            'options' => array(
                'payment_mode_card' => __('Credit Card', 'slimcd-payment-gateway'),
                'payment_mode_cheque' => __('Checking Account', 'slimcd-payment-gateway'),
            ),
        )
    );

    $username = $slimcd_settings->settings['slimcd_username'];
    $password = $slimcd_settings->settings['slimcd_password'];
    $surcharge_convience = slimcd_check_for_surcharge_convience($username, $password);

    if ("Success" === $surcharge_convience['response']) {

        $receiptlabel = null;
        $disclaimerContentCreditCard = null;
        $disclaimerContentCheck = null;

        if ((int)$surcharge_convience['site']->surcharge_percentage > 0) {
            $receiptlabel = "Surcharge";
            $disclaimerContentCreditCard = $slimcd_settings->settings['credit_card_surcharge_disclaimer'];
        } else if ($surcharge_convience['site']->conveniencefee_enabled !== "False") {
            $receiptlabel = $surcharge_convience['site']->conveniencefee_receiptlabel;
            $disclaimerContentCreditCard = $slimcd_settings->settings['credit_card_convenience_fee_disclaimer'];
        }

        if ($surcharge_convience['site']->conveniencefee_enabled !== "False") {
            $receiptlabel = $surcharge_convience['site']->conveniencefee_receiptlabel;
            $disclaimerContentCheck = $slimcd_settings->settings['check_convenience_fee_disclaimer'];
        }

        echo "<div id='slimcdDisclaimer'>
     
        <div id='slimcdDisclaimerCreditCard' class='slimcdPaymentDisclaimer'><p>".
        esc_html($disclaimerContentCreditCard).
        "</p></div>

        <div id='slimcdDisclaimerChecks' class='slimcdPaymentDisclaimer'><p>".
        esc_html($disclaimerContentCheck).
        "</p></div>

        </div>";

        woocommerce_form_field('receiptlabel', array(
            'type'        => 'hidden',
            'required'    => true,
        ), $receiptlabel);
    } else {
        echo "<div id='slimcdDisclaimerError' class='text-danger'><p>Error : ". esc_html($surcharge_convience['description'])."</p> </div>";
    
    }

    $description .= ob_get_clean();
    return $description;
}

/** Description filed for card */
function slimcd_description_fileds_card($slimcd_settings, $description)
{

    $username = $slimcd_settings->settings['slimcd_username'];
    $password = $slimcd_settings->settings['slimcd_password'];
    $surcharge_convience = slimcd_check_for_surcharge_convience($username, $password);
    ob_start();
    if ("Success" === $surcharge_convience['response']) {
        $disclaimerContentCreditCard = null;
        $receiptlabel = null;
        if ((int)$surcharge_convience['site']->surcharge_percentage > 0) {
            $receiptlabel = "Surcharge";
            $disclaimerContentCreditCard = $slimcd_settings->settings['credit_card_surcharge_disclaimer'];
        } else if ($surcharge_convience['site']->conveniencefee_enabled !== "False") {
            $receiptlabel = $surcharge_convience['site']->conveniencefee_receiptlabel;
            $disclaimerContentCreditCard = $slimcd_settings->settings['credit_card_convenience_fee_disclaimer'];
        }

        echo "<div id='slimcdDisclaimer'><div id='slimcdDisclaimerCreditCard' class='slimcdPaymentDisclaimer'><p>".
        esc_html($disclaimerContentCreditCard)."</p></div></div>";

        woocommerce_form_field('receiptlabel', array(
            'type'        => 'hidden',
            'required'    => true,
        ), $receiptlabel);
    } else {
        echo "<div id='slimcdDisclaimerError' class='text-danger'><p>Error :".esc_html($surcharge_convience['description'])."</p></div>";
    }
    $description .= ob_get_clean();
    return $description;
}

/** Description filed for check */

function slimcd_description_fileds_check($slimcd_settings, $description)
{
    $disclaimerContentCheck = null;
    $username = $slimcd_settings->settings['slimcd_username'];
    $password = $slimcd_settings->settings['slimcd_password'];
    $surcharge_convience = slimcd_check_for_surcharge_convience($username, $password);
    ob_start();
    if ("Success" === $surcharge_convience['response']) {
        $receiptlabel = null;
        if ($surcharge_convience['site']->conveniencefee_enabled !== "False") {
            $receiptlabel = $surcharge_convience['site']->conveniencefee_receiptlabel;
            $disclaimerContentCheck = $slimcd_settings->settings['check_convenience_fee_disclaimer'];
        }
        echo "<div id='slimcdDisclaimer'><div id='slimcdDisclaimerCreditCard' class='slimcdPaymentDisclaimer'><p>".esc_html($disclaimerContentCheck)."</p> </div></div>";

        woocommerce_form_field('receiptlabel', array(
            'type'        => 'hidden',
            'required'    => true,
        ), $receiptlabel);
    } else {
        echo "<div id='slimcdDisclaimerError' class='text-danger'><p>Error :". esc_html($surcharge_convience['description'])."</p> </div>";
    }

    $description .= ob_get_clean();
    return $description;
}

/** Api Call for checking convinece and sur-charge */
function slimcd_check_for_surcharge_convience($username, $password)
{
    $data = array(
        "username" => $username,
        "clientid" => "0",
        "siteid" => "0",
        "password" => $password,
    );

    $response = wp_remote_post('https://stats.slimcd.com/soft/json/jsonscript.asp?service=GetUserClientSite3', array(
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
                "site" => $result->reply->datablock->SiteList->Site
            );
        } else {
            return array(
                "response" => $result->reply->response,
                "description" => $result->reply->description
            );
        }
    }
}
