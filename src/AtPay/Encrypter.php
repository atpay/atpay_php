<?php
  namespace AtPay;

  /**
  * Encrypter encrypts the data given to it.
  */
  class Encrypter
  {
    public $atpay;
    private $secret;
    private $boxer;

    function __construct($secret_key, $pub_key, $atpay_key)
    {
      $this->secret = new \sodium\secret_key();
      $this->secret->load(base64_decode($pub_key), base64_decode($secret_key), false);

      $this->atpay = new \sodium\public_key();
      $this->atpay->load(base64_decode($atpay_key), false);

      $this->boxer = new \sodium\crypto();
    }

    /*
    * Encrypt takes the message and an instance of \sodium\nonce.  Encrypt returns the encrypted message.
    */
    public function encrypt($data, $nonce)
    {
      return $this->boxer->box($data, $nonce, $this->atpay, $this->secret);
    }
  }
?>
