<?php

//autoload
require_once dirname(__FILE__) . "/../models/API/Autoloader.php";

//load config
include("../config/config.php");


$splitpayment = new splitpaymentController();
$splitpayment->startTransaction();

class splitpaymentController {

  private $api_key = '';
  private $api_url = '';

  function __construct() {
    $this->api_key = API_KEY;
    $this->api_url = API_URL;
  }

  function startTransaction() {
    $msp = new \MultiSafepayAPI\Client;
    $msp->setApiKey($this->api_key);
    $msp->setApiUrl($this->api_url);
    try {
      $order_id = time();

      $order = $msp->orders->post(array(
          "type" => "redirect",
          "order_id" => $order_id,
          "currency" => "EUR",
          "amount" => 1000,
          "description" => "Demo Transaction",
          "var3" => "affiliate;1001001;100;split payment;", // this contains the spit payment data settings. setup like: affiliate;accountid;amount/percentage;description;        
          "var2" => "2",
          "var1" => "1",
          "items" => "items list",
          "manual" => "false",
          "gateway" => "",
          "days_active" => "30",
          "payment_options" => array(
              "notification_url" => BASE_URL . "notificationController.php?type=initial",
              "redirect_url" => BASE_URL . "mainController.php",
              "cancel_url" => BASE_URL . 'cancelController.php',
              "close_window" => "true"
          ),
          "customer" => array(
              "locale" => "nl_NL",
              "ip_address" => "127.0.0.1",
              "forwarded_ip" => "127.0.0.1",
              "first_name" => "Jan",
              "last_name" => "Modaal",
              "address1" => "Kraanspoor",
              "address2" => "",
              "house_number" => "39",
              "zip_code" => "1032 SC",
              "city" => "Amsterdam",
              "state" => "",
              "country" => "NL",
              "phone" => "0208500500",
              "email" => "test@test.nl",
          ),
          "google_analytics" => array(
              "account" => "UA-XXXXXXXXX",
          ),
          "plugin" => array(
              "shop" => "MultiSafepay Toolkit",
              "shop_version" => TOOLKIT_VERSION,
              "plugin_version" => TOOLKIT_VERSION,
              "partner" => "MultiSafepay",
              "shop_root_url" => "http://www.demo.nl",
          ),
          "custom_info" => array(
              "custom_1" => "value1",
              "custom_2" => "value2",
          )
      ));

      header("Location: " . $msp->orders->getPaymentLink());
    } catch (Exception $e) {
      echo "Error " . htmlspecialchars($e->getMessage());
    }
  }

}

?>