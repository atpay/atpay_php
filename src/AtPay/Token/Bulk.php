<?php
  namespace AtPay\Token;

  class Invoice extends Core
  {

    function __construct($session, $amount)
    {
      parent::__construct($session, $amount);
    }

  }
?>
