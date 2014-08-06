<?php
  require __DIR__ . "/../helper.php";

  class BulkTest extends PHPUnit_Framework_TestCase
  {
    public function testBasicBulkTokenEncoder()
    {
      $partner_id       = "1";
      $private_key      = "DW93ArFKshINPeZOCfYer3riymL+HoRlZj92BNjek+Y=";
      $public_key       = "qIcshFT1NEh2JWPEp7+wVV8ibUFHKNew5apbNLGVqgI=";
      $atpay_public_key = "DjnbXwK20VZpir+RLWsrLVwUinAkdeAmvla4M509GXQ=";

      $session = new \AtPay\Session($partner_id, $private_key, $public_key, $atpay_public_key);
      $session->noncer = new MockNoncer();
      $session->encrypter = new MockBox();

      $bulk_token = new \AtPay\Token\Bulk($session, 30, 'http://example.com/blender-30', 'sku-123');
      $bulk_token->expires = 0 + (60 * 60 * 24 * 7);

      $this->assertEquals($bulk_token->to_s(), '@MTIzAAAAAAAAAAF1cmw8Pi9B8AAAAAk6gC97ImN1c3RvbV9maWVsZHMiOltdfQ==@');
    }
  }
?>
