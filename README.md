# @Pay PHP Bindings

PHP implementation of @Pay's [**Token Protocol**](http://developer.atpay.com/v3/tokens/protocol/). See the [@Pay Developer Site](http://developer.atpay.com/)
for additional information.

A **Token** is a value that contains information about a financial transaction (an invoice
or a product sales offer, for instance). When a **Token** is sent to
`transaction@processor.atpay.com` from an address associated with a **Payment Method**,
it will create a **Transaction**.

There are two classes of **Token** @Pay processes - the **Invoice Token**, which should
be used for sending invoices or transactions applicable to a single
recipient, and the **Bulk Token**, which is suitable for email marketing lists.

An **Email Button** is a link embedded in an email message. When activated, this link
opens a new outgoing email with a recipient, subject, and message body
prefilled. By default this email contains one of the two token types. Clicking
'Send' delivers the email to @Pay and triggers **Transaction** processing. The sender will
receive a receipt or further instructions.

## Installation

*This library requires that the [PHP Sodium](https://github.com/alethia7/php-sodium) Extension be installed.*

Simply checkout this repository and copy the atpay.phar file out of the build directory.

If you're using Composer, you can add the following to your composer.json file:

```json
  {
    "require": {
      "atpay/tokens": "1.0"
    }
  }
```


## Configuration

```php
  $keys = [
    "private" => "EpBic6szxPJVbwlW3VAfEzMZSdWdA04t2Nm6yRQFpf0=",
    "public" => "jZutz9bU6FWIIcRn/12zneT74yWCCuvN5/Su5LvP+3o=",
    "atpay" => "x3iJge6NCMx9cYqxoJHmFgUryVyXqCwapGapFURYh18="
  ]

  $AtPay_Token = new \AtPay\Token($keys);
```

You can find all three keys on the API Settings section when logged into your @Pay Merchant Dashboard.

* [@Pay Merchant Dashboard](https://dashboard.atpay.com)

## Invoice Tokens

An **Invoice** token is ideal for sending invoices or for transactions that are
only applicable to a single recipient (shopping cart abandonment, specialized
offers, etc).

The following creates a token for a 20 dollar transaction specifically for the
credit card @Pay has associated with 'test@example.com'. The item has a reference id of 'sku-123':

```php
  $token = $AtPay_Token->Invoice(20.00, 'text@example.com', 'sku-123');
```

## Bulk Tokens

Most merchants will be fine generating **Bulk Email Buttons** manually on the [@Pay Merchant
Dashboard](https://dashboard.atpay.com), but for cases where you need to
automate the generation of these messages, you can create **Bulk Tokens** without
communicating directly with @Pay's servers.

A **Bulk Token** is designed for large mailing lists. You can send the same token
to any number of recipients. It's ideal for 'deal of the day' type offers, or
general marketing.

To create a **Bulk Token** for a 30 dollar blender:

```php
  $token = $AtPay_Token->Invoice(30.00, 'http://example.com/blender-30', 'blender-30');
```

If a recipient of this token attempts to purchase the product via email but
hasn't configured a credit card, they'll receive a message asking them to
complete their transaction at http://example.com/blender-30. You should
integrate the @Pay JS SDK on that page if you want to allow them to create
a two-click email transaction in the future. If a null value is passed for
the registration url argument, an @Pay hosted registration form will be used.

## General Token Attributes

### Auth Only

A **Token** will trigger a funds authorization and a funds capture
simultaneously. If you're shipping a physical good, or for some other reason
want to delay the capture, use the `auth_only!` method to adjust this behavior:

```php
  $token = $AtPay_Token->Invoice(20.00, 'text@example.com', 'sku-123');
  $token = $token->auth_only();
```

### Expiration

A **Token** expires in 2 weeks unless otherwise specified. Trying to use the **Token**
after the expiration results in a polite error message being sent to the sender.
To adjust the expiration:

```php
  $token = $AtPay_Token->Invoice(20.00, 'text@example.com', 'sku-123');
  $token = $token->expires_in_seconds(60 * 60 * 24 * 7); // one week
 ```

### User Data

**User Data** is a token attribute that contains any string that you wish to get back in @Pay’s
response on processing the token. It has a limit of 2500 characters.

```php
  $token = $AtPay_Token->Invoice(20.00, 'text@example.com', 'sku-123');
  $token = $token->user_data("{foo => bar}");
```


## Button Generation

The PHP client does not currently support button generation. For more information,


## Example

```php
<?php
  require_once 'atpay.phar'; # include php archive. Require "atpay/tokens": "1.0" if using Composer to manage packages.

  $keys = [
    "private" => "xxxxx", # find at your @Pay Merchant dashboard under "API Settings"
    "public" => "xxxxx", # find at your @Pay Merchant dashboard under "API Settings"
    "atpay" => "xxxxx" # find at your @Pay Merchant dashboard under "API Settings"
  ];

  $AtPay_Token = new \AtPay\Token($keys); # instantiate with keys

  $token = $AtPay_Token->Invoice(20.00, 'text@example.com', 'sku-123');

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
