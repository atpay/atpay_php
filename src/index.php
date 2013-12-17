<?php
  spl_autoload_register('atpay_loader');

  function atpay_loader($class_name)
  {
    if(strripos($class_name, "atpay") === FALSE)
    {
      return;
    } else {
      $parts = explode("\\", $class_name);
      $rel = to_path(array_slice($parts, 0, -1));

      include __DIR__ . "/" . $rel . end($parts) . ".php";
    }
  }

  function to_path($parts)
  {
    $rel_part = "";

    foreach($parts as $dir) {
      $rel_part .= strtolower($dir) . "/";
    }

    return $rel_part;
  }
?>