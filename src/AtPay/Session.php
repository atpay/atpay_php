<?php
  namespace AtPay;

  /**
  * The Tokenizer is a convenience class for creating an actual Site Token.  In the future this will also handle email token generation.
  * A Tokenizer instance requires an array containing your "private" key, your "public" key and the "atpay" public key.
  */

  class Session{

    function __construct($partner_id, $public, $private, $atpay=null, $endpoint=null)
    {
      $atpay = isset($atpay) ? $atpay : "QZuSjGhUz2DKEvjule1uRuW+N6vCOoMuR2PgCl57vB0=";
      $this->encrypter = new Encrypter($private, $public, $atpay);
      $this->packer = new Packer();
      $this->noncer = new \sodium\nonce();
      $this->partner_id = $partner_id;
      $this->endpoint =  $endpoint ?: 'https://dashboard.atpay.com';
    }

  }

?>
