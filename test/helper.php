<?php
  if(!function_exists("find_loader")) {
    function find_loader()
    {
      $parts = explode("/", __DIR__);
      $target = array_search("atpay_php", $parts);
      $i = 1;
      $path = array();

      while($i <= $target) {
        $path[$i - 1] = $parts[$i];
        $i++;
      }

      require "/" . join("/", $path) . "/src/index.php";
    }
  }

  if(!function_exists("atpay_loader")) {
    find_loader();
  }
?>