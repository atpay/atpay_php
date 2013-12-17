<?php
  require __DIR__ . "/helper.php";

  /**
  * This actually feels a lot like testing code I didn't write.  Probably a waste of time.
  */
  class EncrypterTest extends PHPUnit_Framework_TestCase
  {
    public function testEncryption()
    {
      $sec_key = "EpBic6szxPJVbwlW3VAfE6MZSdWdA04t2Nm6yRQFpf0=";
      $pub_key = "jZutz9bU6FWIIIRn/12zneT74yWCCuvN5/Su5LvP+3o=";
      $atpay_key = "x3iJge6NCMx9cYqxoJHmFgUryVyXqCwapGapFURYh18=";

      $noncer = new \sodium\nonce();
      $nonce = $noncer->next();
      $encrypter = new \AtPay\Tokens\Encrypter($sec_key, $pub_key, $atpay_key);

      $this->assertEquals($encrypter->encrypt("Skyler", $nonce), $this->direct_sodium($sec_key, $pub_key, $atpay_key, $nonce, "Skyler"));
    }

    private function direct_sodium($pri, $pub, $atpay_pub, $nonce, $message)
    {
      $secret = new \sodium\secret_key();
      $secret->load(base64_decode($pub), base64_decode($pri), false);

      $atpay = new \sodium\public_key();
      $atpay->load(base64_decode($atpay_pub), false);

      $boxer = new \sodium\crypto();

      return $boxer->box($message, $nonce, $atpay, $secret);
    }
  }
?>