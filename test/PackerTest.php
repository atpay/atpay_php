<?php
  require __DIR__ . "/helper.php";

  /**
  * 
  */
  class PackerTest extends PHPUnit_Framework_TestCase
  {
    public function testBigEndianInteger()
    {
      $packer = new \AtPay\Packer();
      $packed = $packer->big_endian_int(8);

      $this->assertEquals($packed, base64_decode("AAAACA=="));
    }

    public function testBigEndianFloat()
    {
      $packer = new \AtPay\Packer();
      $packed = $packer->big_endian_float(5.52);

      $this->assertEquals($packed, base64_decode("QLCj1w=="));
    }

    public function testBigEndianLong()
    {
      $packer = new \AtPay\Packer();
      $packed = $packer->big_endian_long(25);

      $this->assertEquals($packed, base64_decode("AAAAAAAAABk="));
    }

    public function testBigEndianSigned32bit()
    {
      $packer = new \AtPay\Packer();
      $packed = $packer->big_endian_signed_32bit(12);

      $this->assertEquals($packed, base64_decode("AAAADA=="));
    }
  }
?>
