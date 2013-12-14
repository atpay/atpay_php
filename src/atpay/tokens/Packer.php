<?php
  namespace AtPay\Tokens;

  /**
  * The Packer packs the strings for inclusion in the token
  */
  class Packer
  {
    public function big_endian_int($val)
    {
      return strrev(pack("I", $val));
    }

    public function big_endian_float($val)
    {
      return strrev(pack("f", $val));
    }

    public function big_endian_long($val)
    {
      $highMap = 0xffffffff00000000; 
      $lowMap = 0x00000000ffffffff; 
      $higher = ($val & $highMap) >>32; 
      $lower = $val & $lowMap;

      return pack('NN', $higher, $lower);
    }

    public function big_endian_signed_32bit($val)
    {
      return strrev(pack("l", $val));
    }
  }

?>