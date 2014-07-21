<?php
  namespace AtPay\Token;

  class Core
  {

    function __construct($session, $amount, $ref, $item_name)
    {
      $this->session = $session;
      $this->amount = $amount;
      $this->user_data = json_encode(array('ref_id' => $ref, 'item_name' => $item_name));

      $this->expires = time() + (60 * 60 * 24 * 7);
      $this->version = null;
      $this->url = null;

    }

    public function auth_only()
    {
      $this->version = base64_encode($this->packer->big_endian_long(2))."~";
    }

    public function expires_in_seconds($seconds)
    {
      $this->expires = $seconds;
    }

    public function user_data($string)
    {
      $this->user_data = $string;
    }

    public function to_s()
    {
      $token_string = new \AtPay\Token\Encoder($this->session, $this->version, $this->amount, $this->target, $this->expires, $this->url, $this->user_data);
      return $token_string->email();
    }

  }
?>
