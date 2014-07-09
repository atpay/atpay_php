# AtPay Token Generator

This is the native PHP implementation of the AtPay Token Generator.

## Requirements

* This library requires that the [PHP Sodium](https://github.com/alethia7/php-sodium) Extension be installed.

## Usage

#### Installation

##### PHP Archive

Simply checkout this repository and copy the atpay.phar file out of the build directory.

##### Composer

Add atpay/tokens as a requirement in your composer file

```json
  {
    "require": {
      "atpay/tokens": "1.0"
    }
  }
```

#### Email Token Generation

Token Generation is simple and straight forward.  You simply need to instantiate the Tokenizer class with the appropriate keys and then pass all the necessary arguments to the email_token method.

##### Initialization

The Tokenizer expects an array of keys on initialization:

```php
  $keys = [
    "private" => "EpBic6szxPJVbwlW3VAfEzMZSdWdA04t2Nm6yRQFpf0=",
    "public" => "jZutz9bU6FWIIcRn/12zneT74yWCCuvN5/Su5LvP+3o=",
    "atpay" => "x3iJge6NCMx9cYqxoJHmFgUryVyXqCwapGapFURYh18="
  ]

  $tokenizer = new \AtPay\Tokenizer($keys);
```

You can find all three keys on the API Settings section when logged into your @Pay Merchant Dashboard.

* [@Pay Merchant Dashboard](https://dashboard.atpay.com)


##### The Tokenizer

  After the Tokenizer in instantiated, you can call on the email_token method. There are two arguments that can be passed.

  ```php
      $tokenizer->invoice_token(TARGET, PARAMETERS);
  ```



##### Required Parameters

* type
* partner_id
* target
* amount

The **type** specifies the type of email token - either 'bulk' or 'invoice'.

The **partner_id** is provided to you by @Pay.  

The **target** is either an e-mail address (for invoice tokens), or a URL (for bulk tokens)

The **amount** is the final sale amount as a floating point value, for example 12.37


###### More On Token Types

You can specify between email token types.

***bulk*** - a universal token will look up a customers card information. If none, will redirect to url provided.

***invoice*** - a single-user token will only work for a specified target.




##### Optional Parameters

* user_data
* expiration

The **user_data** parameter can be anything that you wish to get back in @Pay’s response on processing the token. It has a limit of 2500 characters.

The **expiration** is the lifetime of the token. By default the token expires 1 day (86400 seconds) after being created.  A valid expiration value is a number of seconds since Unix Epoch.




##### Target

  The **target** is either an e-mail address (for invoice tokens), or a URL (for bulk tokens)
  If a target is left blank for a ***bulk*** token, an @Pay hosted payment form will be generated and used.  




##### Example

```php
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
```



## License

#### LGPL
