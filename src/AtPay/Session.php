<?php
  namespace AtPay;

  /**
  * The Tokenizer is a convenience class for creating an actual Site Token.  In the future this will also handle email token generation.
  * A Tokenizer instance requires an array containing your "private" key, your "public" key and the "atpay" public key.
  */

  class Session{

    function __construct($partner_id, $public, $private, $atpay=null)
    {

      $atpay = isset($atpay) ? $atpay : "x3iJge6NCMx9cYqxoJHmFgUryVyXqCwapGapFURYh18=";
      $this->encrypter = new Encrypter($private, $public, $atpay);
      $this->packer = new Packer();
      $this->noncer = new \sodium\nonce();
      $this->partner_id = $partner_id;
    }

  }

?>
