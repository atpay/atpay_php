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

    private function registration()
    {
      return "registered yo!";
    }

  }
?>
