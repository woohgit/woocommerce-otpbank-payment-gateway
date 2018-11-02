<?php

/* OTPBank Payment Gateway Class */
class WC_Gateway_OTPBank extends WC_Payment_Gateway {


    var $operationLogNames = array(
        'fizetesiTranzakcio'            => 'fizetesiTranzakcio',
        'tranzakcioStatuszLekerdezes'   => 'tranzakcioStatuszLekerdezes',
        'kulcsLekerdezes'               => 'kulcsLekerdezes'
        );

    function __construct() {

        // hello
        $this->id                   = "wooh_otpbank";
        $this->method_title         = __( "OTPBank", 'woocommerce-otpbank' );
        $this->method_description   = __("OTPBank Payment Gateway Plug-in for WooCommerce",'woocommerce-otpbank');
        $this->title                = __( "OTPBank", 'woocommerce-otpbank' );
        $this->icon                 = null;
        $this->has_fields           = true;
        $this->init_form_fields();
        $this->init_settings();
        $this->log                  = new WC_Logger();
        $this->pos_id               = $this->get_option( 'shop_id' );
        $this->private_key          = $this->get_option( 'shop_key' );
        $this->shop_lang            = $this->get_option( 'shop_lang' );
        $this->shop_currency        = $this->get_option( 'shop_currency' );
        $this->payed_order_status   = $this->get_option( 'payed_order_status' ); // wp-hack: added new option

        foreach ( $this->settings as $setting_key => $value ) {
            $this->$setting_key = $value;
        }
        if (is_admin()) {
          add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        }

        add_action( 'woocommerce_api_wc_gateway_otpbank', array( $this, 'check_otp_response' ) );

    } // End __construct()

    public function check_otp_response() {
        require_once 'WebShopService.php';
        global $woocommerce;


        $tranzAzon      = $_REQUEST['tranzakcioAzonosito'];
        $order_id       = intval(substr($tranzAzon, 16));
        $customer_order = new WC_Order($order_id);

        $service    = new WebShopService();
        $response   = $service->tranzakcioStatuszLekerdezes($this->pos_id, $tranzAzon, 1, time() - 60*60*24, time() + 60*60*24, $this->private_key);

        if ($response) {

            $answer = $response->getAnswer();
            if ($response->isSuccessful() && $response->getAnswer() && count($answer->getWebShopFizetesAdatok()) > 0) {

                $fizetesAdatok = $answer->getWebShopFizetesAdatok();
                $tranzAdatok = current($fizetesAdatok);

                $this->log->add("otpbank", "Fizetes tranzakcio adat lekerdezes befejezve: " . $this->pos_id . " - " . $tranzAzon );

                $responseCode = $tranzAdatok->getPosValaszkod();

                if ($tranzAdatok->isSuccessful()) {
                    $this->log->add("otpbank", "Sikeres fizetes " . $this->pos_id . " - " . $tranzAzon );

                    $return_url = $this->get_return_url( $customer_order );
                    $customer_order->add_order_note( __( 'OTP payment completed', 'woocommerce-otpbank' ) ); // wp-hack: fordítási domain el volt írva
                    $woocommerce->cart->empty_cart();
                    $customer_order->payment_complete( $order_id );
                    // wp-hack: change state if needed
                    if ( 'completed' == $this->payed_order_status ) {
                        $res = (int) $customer_order->update_status( 'completed', 'Sikeres OTP-s fizetés után automatikusan átállítva.' );
                        $this->log->add("otpbank", "Allapot allitas 'completed'-re, eredmeny: $res ($this->pos_id - $tranzAzon)" );
                    }

                    wp_redirect( $return_url );
                } else if ("VISSZAUTASITOTTFIZETES" == $responseCode) {
                    $this->log->add("otpbank", "Rejected pament " . $this->pos_id . " - " . $tranzAzon );
                    $customer_order->add_order_note( __( 'Rejected payment', 'woocommerce-otpbank' ) ); // wp-hack: fordítási domain el volt írva
                    $customer_order->update_status( 'failed', "Rejected payment" );
                    $return_url = $this->get_return_url( $customer_order );
                    wp_redirect( $return_url );
                } else {
                    $this->log->add("otpbank", "Sikertelen fizetes " . $this->pos_id . " - " . $tranzAzon );
                    $customer_order->add_order_note( __( 'Unsuccessful payment', 'woocommerce-otpbank' ) ); // wp-hack: fordítási domain el volt írva
                    $customer_order->update_status( 'failed', "Unsuccessful payment" );
                    $return_url = $this->get_return_url( $customer_order );
                    wp_redirect( $return_url );
                }
            } else {
                $this->log->add("otpbank", "Nincs valasz " . $this->pos_id . " - " . $tranzAzon );
                $customer_order->add_order_note( __( 'No response from OTP server', 'woocommerce-otpbank' ) ); // wp-hack: fordítási kulcs és domain el volt írva (Nem jott valasz az OTP servertol)
                $customer_order->update_status( 'failed', "Unsuccessful payment - failed to communicate with the server" );
                $return_url = $this->get_return_url( $customer_order );
                wp_redirect( $return_url );
            }
        }

    }

    public function init_form_fields() {
        $this->form_fields = array(
            'enabled' => array(
                'title'     => __('Enable / Disable', 'woocommerce-otpbank'),
                'label'     => __('Enable this payment gateway','woocommerce-otpbank'),
                'type'      => 'checkbox',
                'default'   => 'no',
                ),
            'title' => array(
                'title'     => __('Title', 'woocommerce-otpbank'),
                'type'      => 'text',
                'desc_tip'  => __( 'Payment title the customer will see during the checkout process.', 'woocommerce-otpbank' ),
                'default'   => __( 'Pay with OTP', 'woocommerce-otpbank' ),
                ),
            'description' => array(
                'title'     => __( 'Description', 'woocommerce-otpbank' ),
                'type'      => 'textarea',
                'desc_tip'  => __( 'Payment description the customer will see during the checkout process.', 'woocommerce-otpbank' ),
                'default'   => __( 'Pay securely using OTP Bank', 'woocommerce-otpbank' ),
                'css'       => 'max-width:350px;'
                ),
            'shop_id' => array(
                'title'     => __( 'OTPBank ShopID', 'woocommerce-otpbank' ),
                'type'      => 'text',
                'desc_tip'  => __( 'This is the OTPbank ShopID.', 'woocommerce-otpbank' ),
                'default'   => '#02299991',
                ),
            'shop_key' => array(
                'title'     => __( 'OTPBank Key', 'woocommerce-otpbank' ),
                'type'      => 'textarea',
                'desc_tip'  => __( 'This is the key for the shop.', 'woocommerce-otpbank' ),
                ),
            'shop_lang' => array(
                'title'       => __('Shop language', 'woocommerce-otpbank'),
                'type'        => 'select',
                'description' => __('Please choose a language', 'woocommerce-otpbank'),
                'options'     => array(
                    'hu'    => __('Hungarian', 'woocommerce-otpbank'),
                    'en'    => __('English', 'woocommerce-otpbank')
                    ),
                'desc_tip'    => true,
                ),
            'shop_currency' => array(
                'title'       => __('Shop currency', 'woocommerce-otpbank'),
                'type'        => 'select',
                'description' => __('Please choose a currency', 'woocommerce-otpbank'),
                'options'     => array(
                    'HUF'   => __('HUF', 'woocommerce-otpbank'),
                    'EUR'   => __('EUR', 'woocommerce-otpbank'),
                    'USD'   => __('USD', 'woocommerce-otpbank')
                    ),
                'desc_tip'    => true,
                ),
            // wp-hack: added one more option
            'payed_order_status' => array(
                'title' => __('Status of payed orders', 'woocommerce-otpbank'),
                'type'        => 'select',
                'description' => __('When the payment was successfull, this will be the status of the order.', 'woocommerce-otpbank'),
                'options'   => array(
                    'processing' => __('processing', 'woocommerce-otpbank'), // Feldolgozás alatt
                    'completed'  => __('completed', 'woocommerce-otpbank'), // Teljesítve
                    ),
                'desc_tip'    => true,
                ),
            );
    }

    public function process_payment( $order_id ) {
        require_once 'RequestUtils.php';

        global $woocommerce;
        $customer_order = new WC_Order($order_id);

        $tranzakcioAzonosito = substr(md5(time()),0,16).$order_id;
        $soapClientProps = array(
            'location'                  => 'https://www.otpbankdirekt.hu/mwaccesspublic/mwaccess',
            'uri'                       => 'https://www.otpbankdirekt.hu/mwaccesspublic/mwaccess',
            'trace'                     => true,
            'exceptions'                => 1,
            'connection_timeout'        => 10,
            'default_socket_timeout'    => 660);

        $soapClient = new SoapClient(NULL, $soapClientProps);


        /*
         * A very uggly dirty hack because OTP and WooCommerce does not match...
         * Actually they hate each other and OTP can't send a normal post back,
         * only uggly GET parameters.. *sigh*...
         */
        $backURL = WC()->api_request_url( 'WC_Gateway_OTPBank' );

        // if it already contains a ? it means we have to continue with &
        if (preg_match('/\?/', $backURL)) {
            $additional_prefix = "&";
        // otherwise we have to start with a ?.. (usually with pretty permalinks settings)
        } else {
            $additional_prefix = "?";
        }

        // urlencode is required for the test posID which contains a #... another brilliant idea by OTP
        $backURL .= $additional_prefix."fizetesValasz=true&posId=".urlencode($this->pos_id)."&tranzakcioAzonosito=".$tranzakcioAzonosito;


        $_REQUEST['posId']      = $this->pos_id;
        // if HUF, it should be fixed int
        $order_total = $customer_order->get_total(); // wp-hack: use getter function to get total value
        if ($this->shop_currency != "HUF") {
            $_REQUEST['osszeg']     = number_format($order_total, 2, ',', '');
        } else {
            $_REQUEST['osszeg']     = intval($order_total);
        }
        $_REQUEST['devizanem']  = $this->shop_currency;
        $_REQUEST['nyelvkod']   = $this->shop_lang;
        $_REQUEST['backURL']    = $backURL;
        $_REQUEST['tranzakcioAzonosito'] = $tranzakcioAzonosito;

        require_once 'WebShopService.php';
        require_once 'WebShopXmlUtils.php';
        $service = new WebShopService();

        $custPageTemplate = ConfigUtils::substConfigValue(
            "https://www.otpbankdirekt.hu/webshop/do/webShopVasarlasInditas?posId={0}&azonosito={1}&nyelvkod={2}",
            array(
                "0" => urlencode($this->pos_id),
                "1" => urlencode($tranzakcioAzonosito),
                "2" => urlencode($_REQUEST['nyelvkod']))
            );

        $this->log->add( 'otpbank', 'Generating payment form for order ' . $tranzakcioAzonosito . '. Notify URL: ' . $backURL. ' OTP URL: ' . $custPageTemplate );
        $response = NULL;

        $response = $service->fizetesiTranzakcio(
            $this->pos_id,
            $tranzakcioAzonosito,
            RequestUtils::safeParam($_REQUEST, 'osszeg'),
            RequestUtils::safeParam($_REQUEST, 'devizanem'),
            RequestUtils::safeParam($_REQUEST, 'nyelvkod'),
            RequestUtils::safeParam($_REQUEST, 'nevKell'),
            RequestUtils::safeParam($_REQUEST, 'orszagKell'),
            RequestUtils::safeParam($_REQUEST, 'megyeKell'),
            RequestUtils::safeParam($_REQUEST, 'telepulesKell'),
            RequestUtils::safeParam($_REQUEST, 'iranyitoszamKell'),
            RequestUtils::safeParam($_REQUEST, 'utcaHazszamKell'),
            RequestUtils::safeParam($_REQUEST, 'mailCimKell'),
            RequestUtils::safeParam($_REQUEST, 'kozlemenyKell'),
            RequestUtils::safeParam($_REQUEST, 'vevoVisszaigazolasKell'),
            RequestUtils::safeParam($_REQUEST, 'ugyfelRegisztracioKell'),
            RequestUtils::safeParam($_REQUEST, 'regisztraltUgyfelId'),
            RequestUtils::safeParam($_REQUEST, 'shopMegjegyzes'),
            RequestUtils::safeParam($_REQUEST, 'backURL'),
            RequestUtils::safeParam($_REQUEST, 'zsebAzonosito'),
            RequestUtils::safeParam($_REQUEST, 'ketlepcsosFizetes'),
            $this->private_key);



                // a folyamat válaszának naplózása
        if ($response->isFinished()) {
            $azonosito = $response->getInstanceId();
            $this->log->add( 'otpbank', 'fizetesiTranzakcio->isFinished - ' . $azonosito);

            $responseDom = $response->getResponseDOM();
            $this->lastOutputXml = WebShopXmlUtils::xmlToString($responseDom);
            $this->log->add( 'otpbank', $this->operationLogNames["fizetesiTranzakcio"] . " valasz:\n" . trim($this->lastOutputXml));

            return array(
              'result' 	    => 'success',
              'redirect'	=> $custPageTemplate
              );

        }
    }


    public function validate_fields() {
        return true;
    }
} // End of OTPBank
