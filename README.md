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

#### Token Generation

Token Generation is simple and straight forward.  You simply need to instantiate the Tokenizer class with the appropriate keys and then pass all the necessary parameters to the site_token method.

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

The "private" key is provided to you by AtPay.
The "public" key is also provided to you by AtPay.
The "atpay" key is the AtPay public key specific to the environment you are talking to.

If you need to lookup or re-generate your keys you can do so in your merchant dashboard:

* [@Pay Dashboard](https://dashboard.atpay.com)


##### Site Tokens

To build a site token you need to provide information about the transaction as well as information about the client browser that is initiating the transaction.

###### Transaction Data

As far as transaction details go there are three required parameters:

* partner_id
* card
* amount

These are probably what you would expect for a financial transaction: a recipient, a payment source and an amount.  The partner_id is provided to you by @Pay.  The card is represented by a token you receive upon successful [registration](http://developer.atpay.com/v1/guides/registering-cards/).  The amount is the final sale amount as a floating point value, for example 12.37

###### Client Data

A Site Token also requires information about the Client that is making the transaction request. The token needs to contain four pieces of data that pretain to the the requesting Client:  

* User Agent String
* Accept Language
* Accept Characters
* Remote IP Address

The User Agent string, the Accept Language header value, the Accept Characters header value and the Remote IP Address are used to verify that the Browser which requested the Token is the same Browser presenting the Token to @Pay.  These values may be passed to the site_token method in the $params array or the will be pulled from $_SERVER if not specified.

###### Extras

There is also an optional expiration that can be set on the token.  By default the token expires 60 seconds after being created.  A valid expiration value is a number of seconds since Unix Epoch.

###### Examples

```php
  $tokenizer = new \AtPay\Tokenizer($keys);

  $card = "OTAzYzUzNWVjOVKhtOalUQA=";

  $params = [
    "partner_id" => 0,
    "amount" => 12.62,
    "expiration" => time() + 60,
    "headers" => [
      "user_agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36",
      "accept_lang" => "en-US,en;q=0.8",
      "accept_chars" => ""
    ],
    "ip" => "173.163.242.11"
  ];

  $token = $tokenizer->site_token($card, $params);
```

If you wish to use the default for any of the optional values omit the key/value pair completely from the $params array.


##### Email Tokens

To build an email token you need to provide information about the transaction.

###### Transaction Data

As far as transaction details go there are three required parameters:

* partner_id
* target
* amount

These are probably what you would expect for a financial transaction: a recipient, a payment source and an amount.  The partner_id is provided to you by @Pay.  The card is represented by a token you receive upon successful [registration](http://developer.atpay.com/v1/guides/registering-cards/).  The amount is the final sale amount as a floating point value, for example 12.37

###### Email Token Target

You can specify between email token types.

* url - a universal token that will look up card information by the "from" address. If none, will redirect to url provided.
* card (default) - card token
* member - UUID of @pay member
* email - member email

###### Extras

There is also an optional expiration that can be set on the token.  By default the token expires 60 seconds after being created.  A valid expiration value is a number of seconds since Unix Epoch.

###### User Data

User Data can be anything that you wish to get back in @Pay’s response on processing the token. It has a limit of 2500 characters.


###### Examples

```php
 $keys = [
    "private" => "KS8uFpXyBji3KbKeGwqIo4L5m6HaTJnf2SM40j2jTAY=",
    "public" => "rjkAFcjdiyZjsFNClu8dEKXMI3Mvar+iBuezjqYRqEs=",
    "atpay" => "x3iJge6NCMx9cYqxoJHmFgUryVyXqCwapGapFURYh18="
  ];

  $tokenizer = new \AtPay\Tokenizer($keys);

  $url = "http://example.com/signup";

  $params = [
    "type" => "url",
    "partner_id" => 8254,
    "amount" => 12.62
  ];

  $email_token = $tokenizer->email_token($url, $params);
```

If you wish to use the default for any of the optional values omit the key/value pair completely from the $params array.


## License

#### LGPL
