<?php
  namespace AtPay\Token;

  class Invoice extends Core
  {

    function __construct($session, $amount, $target)
    {
      $this->target = $target;
      parent::__construct($session, $amount);
    }

  }
?>
