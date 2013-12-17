<?php
  $src_root = __DIR__ . "/src";
  $build_root = __DIR__ . "/build";

  $phar = new Phar($build_root . "/atpay.phar", 0, "atpay.phar");
  $phar->buildFromDirectory($src_root);
  // $phar = new Phar($build_root . "/atpay.phar", FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, "atpay.phar");
  // $phar["index.php"] = file_get_contents($src_root . "/index.php");
  // $phar["Tokenizer.php"] = file_get_contents($src_root . "/atpay/Tokenizer.php");
  // $phar["Encrypter.php"] = file_get_contents($src_root . "/atpay/tokens/Encrypter.php");
  // $phar["Packer.php"] = file_get_contents($src_root . "/atpay/tokens/Packer.php");
?>