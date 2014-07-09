<?php
    require_once 'atpay.phar'; # include php archive. Require "atpay/tokens": "1.0" if using Composer to manage packages.

    $keys = [
      "private" => "xxxxx", # find at your @Pay Merchant dashboard under "API Settings"
      "public" => "xxxxx", # find at your @Pay Merchant dashboard under "API Settings"
      "atpay" => "xxxxx" # find at your @Pay Merchant dashboard under "API Settings"
    ];

    $tokenizer = new \AtPay\Tokenizer($keys); # instantiate tokenizer with keys

    $target = "me@example.com"; # e-mail address for invoice token, URL for bulk token. If left nil, a bulk token will use an @Pay hosted payment form.

    $params = [
      "type" => "invoice", # change to 'bulk' for a one-to-many token
      "partner_id" => 00000, # find at your @Pay Merchant dashboard under "API Settings"
      "amount" =>  12.34, # any integer amount
      "expiration" => 86400, # life span of token in seconds. Optional. Default: 86400 (24 hours)
      "user_data" => "{'sku' => 'abc-123'}" # any string you wish to get back in @Pay's response. Optional. Limit: 2500 Characters
    ];

    $email_token = $tokenizer->invoice_token($target, $params); # builds and returns invoice token with target and params passed

    if ($email_token) {
      $from = "test@example.com";
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      $headers .= 'From: '.$from."\r\n";
      $subject = "Email Offer";
      $message = '<a href="mailto:transactions@.atpay.com?subject=PHP Token&body='.$email_token.'">Click to Buy</a>'; # creates a mailto with generated invoice token that will send to @Pay to process
      mail($target,$subject,$message,$headers); # send email to target. Adjust if invoice token
      echo "Tokenized link emailed.";
    }
?>
