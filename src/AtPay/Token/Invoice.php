<?php
  namespace AtPay\Token;

  class Invoice
  {

    function __construct($session, $amount, $email, $ref)
    {
      $this->packer = $session->packer;
      $this->encrypter = $session->encrypter;
      $this->email = $email;
      $this->ref = $ref;
      $this->amount = $amount;
      $this->nonce = $session->noncer->next();
      $this->partner_id = $session->packer->big_endian_long($session->partner_id);
      $this->version = null;
      $this->expiration = time() + (60 * 60 * 24 * 7);
      $this->user_data = "{'ref_id' : '".$ref."'}";
    }

    public function auth_only()
    {
      $this->version = "2~";
    }

    public function expires_in_seconds($seconds)
    {
      $this->expiration = $seconds;
    }

    public function user_data($string)
    {
      $this->user_data = $string;
    }

    public function to_s()
    {
      $body = $this->box($this->build_body($this->amount, $this->email), $this->nonce);
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

    private function build_body($amount, $email)
    {
      $body = "email<" . $email . ">";
      $body .= "/" . $this->packer->big_endian_float($amount);
      $body .= $this->packer->big_endian_signed_32bit($expiration);
      $body .= "/" . $this->user_data;

      return $body;
    }
  }
?>
