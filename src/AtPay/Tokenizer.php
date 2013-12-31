<?php
  namespace AtPay;

  /**
  * The Tokenizer is a convenience class for creating an actual Site Token.  In the future this will also handle email token generation.
  * A Tokenizer instance requires an array containing your "private" key, your "public" key and the "atpay" public key.
  */
  class Tokenizer
  {
    private $encrypter;
    private $packer;
    private $noncer;
    
    /*
    * 
    */

    function __construct($keys)
    {
      $this->encrypter = new Tokens\Encrypter($keys["private"], $keys["public"], $keys["atpay"]);
      $this->packer = new Tokens\Packer();
      $this->noncer = new \sodium\nonce();
    }

    public function site_token($target, $params)
    {
      if(empty($params["ip"])) {
        $ip = $_SERVER["REMOTE_ADDR"];
      } else {
        $ip = $params["ip"];
      }

      $nonce = $this->noncer->next();
      $partner_id = $this->packer->big_endian_long($params["partner_id"]);
      $header_box = $this->box($this->header_hash($params), $nonce);
      $header_length = $this->payload_length($header_box);
      $ip_length = $this->payload_length($ip);

      $body = $this->box($this->build_body($params, $target), $nonce);

      $contents = $nonce->nbin . $partner_id . $header_length . $header_box . $ip_length . $ip . $body;

      return "@" . $this->encode64($contents); 
    }


    public function email_token($target, $params)
    {
      if(empty($params["ip"])) {
        $ip = $_SERVER["REMOTE_ADDR"];
      } else {
        $ip = $params["ip"];
      }

      $nonce = $this->noncer->next();
      $partner_id = $this->packer->big_endian_long($params["partner_id"]);

      $body = $this->box($this->build_body($params, $target), $nonce);

      $contents = $nonce->nbin . $partner_id . $body;

      return "@" . $this->encode64($contents); 
    }


    private function box($payload, $nonce)
    {
      return $this->encrypter->encrypt($payload, $nonce);
    }

    private function sha1($data)
    {
      return sha1($data);
    }

    private function encode64($data)
    {
      return base64_encode($data);
    }

    private function header_hash($params)
    {
      if(empty($params["ip"])) {
        $ip = $_SERVER["REMOTE_ADDR"];
      } else {
        $ip = $params["ip"];
      }

      if($params["headers"] == NULL) {
        return $this->sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . $_SERVER['HTTP_ACCEPT_CHARSET'] . $ip);
      } else {
        return $this->sha1($params["headers"]["user_agent"] . $params["headers"]["accept_lang"] . $params["headers"]["accept_chars"] . $ip);
      }
    }

    private function payload_length($str)
    {
      return $this->packer->big_endian_signed_32bit(strlen($str));
    }

    private function build_body($params, $target)
    {

      if(empty($params["expiration"])) {
        $expiration = time() + 60;
      } else {
        $expiration = $params["expiration"];
      }

      if(empty($params["amount"])) {
        $amount = 5.0;
      } else {
        $amount = $params["amount"];
      }
      
      if(array_key_exists("group", $params)) {
        if($params["type"] == "url") {
          $body = "url<" . $target . ">";
        }elseif($params["type"] == "email"){
          $body = "email<" . $target . ">";
        }elseif($params["type"] == "member"){
          $body = "member<" . $target . ">";
        }else{
          $body = "card<" . $target . ">";        
        }      
      }else{
        $body = "card<" . $target . ">";        
      }


      if(array_key_exists("group", $params)) {
        $body .= ":" . $params["group"];
      }

      $body .= "/" . $this->packer->big_endian_float($amount);
      $body .= $this->packer->big_endian_signed_32bit($expiration);

      if(array_key_exists("user_data", $params)) {
        $body .= "/" . $params["user_data"];
      }

      return $body;
    }
  }
?>