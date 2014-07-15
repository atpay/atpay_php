<?php
  require __DIR__ . "/helper.php";

  class InvoiceTest extends PHPUnit_Framework_TestCase
  {
    public function testBasicInvoiceTokenEncoder()
    {
      $partner_id       = "1";
      $private_key      = "DW93ArFKshINPeZOCfYer3riymL+HoRlZj92BNjek+Y=";
      $public_key       = "qIcshFT1NEh2JWPEp7+wVV8ibUFHKNew5apbNLGVqgI=";
      $atpay_public_key = "DjnbXwK20VZpir+RLWsrLVwUinAkdeAmvla4M509GXQ=";

      $session = new \AtPay\Session($partner_id, $private_key, $public_key, $atpay_public_key);

      $invoice_token = new \AtPay\Token\Invoice($session, 20, 'test@example.com', 'sku-123');
      $invoice_token->nonce = new MockNonce("cba");
      $invoice_token->encrypter = new MockBox();
      $invoice_token->expiration = 0 + (60 * 60 * 24 * 7);

      $this->assertEquals($invoice_token->to_s(), '@Y2JhAAAAAAAAAAFlbWFpbDx0ZXN0QGV4YW1wbGUuY29tPi9BoAAAAAk6gC97InJlZl9pZCI6InNrdS0xMjMifQ==@');
    }
  }
?>
