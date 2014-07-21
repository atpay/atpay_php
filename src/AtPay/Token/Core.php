<?php
  namespace AtPay\Token;

  class Core
  {

    function __construct($session, $amount)
    {
      $this->session = $session;
      $this->amount = $amount;

      $this->expires = time() + (60 * 60 * 24 * 7);
      $this->version = null;
      $this->url = null;
      $this->user_data = array(
        "custom_fields" => array();
      );

    }

    public function auth_only()
    {
      $this->version = base64_encode($this->packer->big_endian_long(2))."~";
    }

    public function expires_in_seconds($seconds)
    {
      $this->expires = $seconds;
    }

    public function url($url)
    {
      $this->url = $url;
    }

    public function custom_field($name, $required = true)
    {
      $this->user_data[custom_fields] << array(
        "name" => $name,
        "required" => $required
      );
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
