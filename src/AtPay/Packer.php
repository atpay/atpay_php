<?php
  namespace AtPay;

  /**
  * The Packer packs data for inclusion in the token
  */
  class Packer
  {
    public $big_endian;

    /**
    * Determine if we are on a big or little endian machine (hopefully it's one or the other).
    */
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

    /**
    * pack value as an unsigned big endian integer (32bit)
    */
    public function big_endian_int($val)
    {
      if($this->big_endian) {
        return pack("I", $val);
      } else {
        return strrev(pack("I", $val));
      }
    }

    /**
    * pack the value as a big endian float
    */
    public function big_endian_float($val)
    {
      if($this->big_endian) {
        return pack("f", $val);
      } else {
        return strrev(pack("f", $val));
      }
    }

    /**
    * pack the value as a 64bit big endian integer
    */
    public function big_endian_long($val)
    {
      $highMap = 0xffffffff00000000;
      $lowMap = 0x00000000ffffffff;
      $higher = ($val & $highMap) >>32;
      $lower = $val & $lowMap;

      return pack('NN', $higher, $lower);
    }

    /**
    * pack the value as a signed big endian integer (32bit)
    */
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
