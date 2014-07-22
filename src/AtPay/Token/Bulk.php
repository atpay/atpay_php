<?php
  namespace AtPay\Token;

  class Bulk extends Core
  {

    function __construct($session, $amount, $target = NULL)
    {
      parent::__construct($session, $amount, $target);
    }

  }
?>
