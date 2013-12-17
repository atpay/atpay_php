<?php
  namespace AtPay\Tokens;

  /**
  * The Packer packs the strings for inclusion in the token
  */
  class Packer
  {
    public $big_endian;

    function __construct()
    {
      $test_int = 0x00FF;
      $p = pack("S", $test_int);

      if($test_int === unpack("n", $p)) {
        $this->big_endian = TRUE;
      } else {
        $this->big_endian = FALSE;
      }
    }

    public function big_endian_int($val)
    {
      if($this->big_endian) {
        return pack("I", $val);
      } else {
        return strrev(pack("I", $val));
      }
    }

    public function big_endian_float($val)
    {
      if($this->big_endian) {
        return pack("f", $val);
      } else {
        return strrev(pack("f", $val));
      }
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
      if($this->big_endian) {
        return pack("l", $val);
      } else {
        return strrev(pack("l", $val));
      }
    }
  }
?>