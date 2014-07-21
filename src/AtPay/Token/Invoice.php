<?php
  namespace AtPay\Token;

  class Invoice extends Core
  {

    function __construct($session, $amount, $target, $ref, $item_name='')
    {
      $this->target = $target;
      parent::__construct($session, $amount, $ref, $item_name);
    }

  }
?>
