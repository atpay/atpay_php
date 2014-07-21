<?php
  namespace AtPay\Token;

  class Invoice extends Core
  {

    function __construct($session, $amount, $ref, $item_name='')
    {
      parent::__construct($session, $amount, $ref, $item_name);
    }


  }
?>
