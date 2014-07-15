<?php
  namespace AtPay\Token;

  class Bulk
  {

    function __construct($session, $amount, $url=null, $ref)
    {
      $this->packer = $session->packer;
      $this->encrypter = $session->encrypter;
      $this->url = $url;
      $this->ref = $ref;
      $this->amount = $amount;
      $this->nonce = $session->noncer->next();
      $this->partner_id = $session->packer->big_endian_long($session->partner_id);
      $this->version = null;
      $this->expiration = time() + (60 * 60 * 24 * 7);
      $this->user_data = $ref;
    }

    public function auth_only()
    {
      $this->version = base64_encode($this->packer->big_endian_long(2))."~";
    }

    public function expires_in_seconds($seconds)
    {
      $this->expiration = $seconds;
    }

    public function user_data($string)
    {
      $this->user_data = $string;
    }

    public function to_s(){
      $body = $this->box($this->build_body($this->amount, $this->url), $this->nonce);
      $contents = $this->nonce->nbin . $this->partner_id . $body;
      return "@".$this->version.$this->encode64($contents)."@";
    }

    private function box($payload, $nonce)
    {
      return $this->encrypter->encrypt($payload, $nonce);
    }

    private function encode64($data)
    {
      return strtr(base64_encode($data), '+/', '-_');
    }

    private function build_body($amount, $url)
    {
      $body = "url<" . $url . ">";
      $body .= "/" . $this->packer->big_endian_float($amount);
      $body .= $this->packer->big_endian_signed_32bit($this->expiration);
      $body .= "/" . $this->user_data;

      return $body;
    }
  }
?>
