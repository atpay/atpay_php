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

All Token generation functions require a Session object. Grab your API credentials from https://dashboard.atpay.com/ (API Settings):

```php
    $session = new \AtPay\Session(partner_id, public_key, private_key);
```

## Invoice Tokens

An **Invoice** token is ideal for sending invoices or for transactions that are
only applicable to a single recipient (shopping cart abandonment, specialized
offers, etc).

The following creates a token for a 20 dollar transaction specifically for the
credit card @Pay has associated with 'test@example.com'. The item has a reference id of 'sku-123':

```php
  $invoice_token = new \AtPay\Token\Invoice($session, 20, 'test@example.com', 'sku-123', 'Crispy iPhone Gadget');
  echo $invoice_token->to_s();
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
  $bulk_token = new \AtPay\Token\Bulk($session, 30, 'http://example.com/blender-30', 'sku-123', 'Best Blender');
  echo $bulk_token->to_s();
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
  $invoice_token = new \AtPay\Token\Invoice($session, 20, 'test@example.com', 'sku-123');
  $invoice_token->auth_only();
  echo $invoice_token->to_s();
```

### Expiration

A **Token** expires in 2 weeks unless otherwise specified. Trying to use the **Token**
after the expiration results in a polite error message being sent to the sender.
To adjust the expiration:

```php
  $invoice_token = new \AtPay\Token\Invoice($session, 20, 'test@example.com', 'sku-123');
  $invoice_token->expires_in_seconds(60 * 60 * 24 * 7); // one week
  echo $invoice_token->to_s();
 ```

### User Data

**User Data** is a token attribute that contains any string that you wish to get back in @Pay’s
response on processing the token. It has a limit of 2500 characters.

```php
  $invoice_token = new \AtPay\Token\Invoice($session, 20, 'test@example.com', 'sku-123');
  $invoice_token->user_data("{foo => bar}");
  echo $invoice_token->to_s();
```


## Button Generation

The PHP client does not currently support button generation.

## Full Example

```php
<?php
  // Include @Pay's PHP SDK
  require_once 'atpay.phar'; # include php archive. Require "atpay/tokens": "1.0" if using Composer to manage packages.

  // Configure with your keys:
  $partner_id       = '';
  $public_key       = '';
  $private_key      = '';
  $atpay_public_key = 'QZuSjGhUz2DKEvjule1uRuW+N6vCOoMuR2PgCl57vB0=';

  $session = new \AtPay\Session($partner_id, $public_key, $private_key, $atpay_public_key);

  // Generate a new Invoice Token for $150
  $total_price    = 150;
  $customer_email = "customer@example.com";
  $my_invoice_id  = "invoice-123";
  $invoice_name   = "Your Cart";

  $invoice_token = new \AtPay\Token\Invoice($session, $total_price, $customer_email, $my_invoice_id, $invoice_name);
  $token         = $invoice_token->to_s();

  // Send an Email to the Customer
  $subject = "You Abandoned Your Cart!";
  $from    = "merchant@example.com";

  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'From: '.$from."\r\n";
  $message  = '<a href="mailto:transaction@processor.atpay.com?subject=PHP Token&body='.$token.'">Click to Buy</a>'; # creates a mailto with generated invoice token that will send to @Pay to process

  // Send the email
  mail($customer_email, $subject, $message, $headers);

  // Done
  echo "Invoice Sent!";
?>
```
