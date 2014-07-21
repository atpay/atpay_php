<?php
  namespace AtPay\Token;

  class Encoder
  {

  function __construct($session, $version, $amount, $target, $expires, $url, $user_data)
    {
        $this->session     =   $session;
        $this->version     =   $version;
        $this->amount      =   $amount;
        $this->target      =   $target;
        $this->expires     =   $expires;
        $this->url         =   $url;
        $this->user_data   =   $user_data;

        $this->packer = $session->packer;
        $this->encrypter = $session->encrypter;
        $this->nonce = $session->noncer->next();
        $this->partner_id = $session->packer->big_endian_long($session->partner_id);
    }

    public function email()
    {
      $body = $this->box($this->build_body(), $this->nonce);
      $contents = $this->nonce->nbin . $this->partner_id . $body;
      return "@".$this->version.$this->encode64($contents)."@";
    }

    public function site()
    {
      return "Site tokens are not yet supported with the SDK";
    }


    public function box($payload, $nonce)
    {
      return $this->encrypter->encrypt($payload, $nonce);
    }

    public function build_body()
    {
      $body = "";

      if( !is_null($this->url)){
        $body .= "url<" . $this->url . ">";
      }

      if( !is_null($this->target)){
        $body .= "email<" . $this->target . ">";
      }

      $body .= "/" . $this->packer->big_endian_float($this->amount);
      $body .= $this->packer->big_endian_signed_32bit($this->expires);
      $body .= "/" . $this->user_data;

      return $body;
    }

    private function encode64($data)
    {
      return strtr(base64_encode($data), '+/', '-_');
    }
    
  }
?>