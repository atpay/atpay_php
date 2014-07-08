<?php
  require __DIR__ . "/helper.php";

  /**
  *
  */
  class TokenizerTest extends PHPUnit_Framework_TestCase
  {
    private $token;
    private $packer;

    public function testSiteTokenDefaults()
    {
      $keys = [
        "private" => "EpBic6szxPJVbwlW3VAfEzMZSdWdA04t2Nm6yRQFpf0=",
        "public" => "jZutz9bU6FWIIcRn/12zneT74yWCCuvN5/Su5LvP+3o=",
        "atpay" => "x3iJge6NCMx9cYqxoJHmFgUryVyXqCwapGapFURYh18="
      ];

      $this->packer = new \AtPay\Tokens\Packer();

      $card = "OTAzYzUzNWVjOVKhtOalUQA=";
      $tokenizer = new \AtPay\Tokenizer($keys);
      $val = ltrim($tokenizer->site_token($card, $this->params()), "@");
      $this->token = base64_decode(strtr($val, '-_,', '+/='));

      $this->has_ip($this->params()["ip"]);
      $this->has_partner_id($this->params()["partner_id"]);
    }

    private function params()
    {
      return [
        "ip" => "173.163.242.213",
        "headers" => [
          "user_agent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/30.0.1599.101 Safari/537.36",
          "accept_lang" => "en-US,en;q=0.8",
          "accept_chars" => ""
        ],
        "partner_id" => 19,
        "card" => "OTAzYzUzNWVjOVKhtOalUQA="
      ];
    }

    private function has_ip($ip)
    {
      $this->assertTrue(strpos($this->token, $ip) !== FALSE);
    }

    private function has_partner_id($id)
    {
      $this->assertTrue(strpos($this->token, $this->packer->big_endian_signed_32bit(19)) !== FALSE);
    }
  }
?>
