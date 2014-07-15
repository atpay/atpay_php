<?php
  require_once 'atpay.phar'; # include php archive. Require "atpay/tokens": "1.0" if using Composer to manage packages.

  $session = new \AtPay\Session(partner_id, public_key, private_key);

  $invoice_token = new \AtPay\Token\Invoice($session, 20, 'test@example.com', 'sku-123');
  $token = $invoice_token->to_s();

  if ($token) {
    $from = "test@example.com";
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: '.$from."\r\n";
    $subject = "Email Offer";
    $message = '<a href="mailto:transactions@.atpay.com?subject=PHP Token&body='.$token.'">Click to Buy</a>'; # creates a mailto with generated invoice token that will send to @Pay to process
    mail($target,$subject,$message,$headers); # send email to target. Adjust if invoice token
    echo "Tokenized link emailed.";
  }
?>
