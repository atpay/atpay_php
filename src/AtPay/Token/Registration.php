<?php
  namespace AtPay\Token;

  class Registration
  {

    function __construct($session, $token)
    {
      $this->session = $session;
      $this->token = $token;
      $this->registration();
    }


    public function url()
    {
      return $this->registration()->url;
    }


    public function id()
    {
      return $this->registration()->id;
    }


    public function short()
    {
      return "atpay://".$this->id();
    }


    private function registration()
    {
      $url = $this->session->endpoint."/token/registrations";

      $data = "token=".$this->token;

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'application/json',
          'Content-Length: ' . strlen($data))
      );

      $result = curl_exec($ch);
      return json_decode($result);
    }

  }
?>
