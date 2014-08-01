<?php
  namespace AtPay\Token;

  class Core
  {

    function __construct($session, $amount, $target = NULL)
    {
      $this->session = $session;
      $this->amount = $amount;
      $this->target = $target;
      $this->expires = time() + (60 * 60 * 24 * 7 * 2);
      $this->version = null;
      $this->user_data = array(
        'custom_fields' => array()
      );
    }

    public function auth_only()
    {
      $this->version = 2;
    }

    public function expires_in_seconds($seconds)
    {
      $this->expires = $seconds;
    }

    public function url($url)
    {
      $this->user_data["signup_url"] = $url;
    }

    public function name($name)
    {
      $this->user_data["item_name"] = $name;
    }

    public function estimated_fulfillment_days($days){
      $this->auth_only();
      $this->user_data["fulfillment"] = $days;
    }

    public function set_item_quantity($qty){
      $this->user_data["quantity"] = $qty;
    }

    public function set_item_details($string){
      $this->user_data["details"] = $string;
    }

    public function requires_shipping_address($v){
      $this->set_address();
      $this->set_address_type("shipping", $v);
    }

    public function requires_billing_address($v){
      $this->set_address();
      $this->set_address_type("billing", $v);
    }


    private function set_address()
    {
      if (!array_key_exists("address", $this->user_data)) {
        $this->user_data['address'] = [];
       }
    }

    private function set_address_type($type, $v)
    {
      if($v == true){
        if(!in_array($this->user_data['address'], array($type))){
          array_push($this->user_data['address'], $type);
        }
      }else{
        if(in_array($this->user_data['address'], array($type))){
          $this->user_data['address'] = array_diff($this->user_data['address'], array('$type'));
        }
      }
    }

    public function request_custom_data($name, $required = false)
    {
      $new_array = array(
        "name" => $name,
        "required" => $required
      );

      array_push($this->user_data['custom_fields'], $new_array);
    }

    public function custom_user_data($string)
    {
      $this->user_data["custom_user_data"] = $string;
    }

    public function register()
    {
      $register = new \AtPay\Token\Registration($this->session, $this->to_s());
      return $register;
    }

    public function to_s()
    {
      $token_string = new \AtPay\Token\Encoder($this->session, $this->version, $this->amount, $this->target, $this->expires, $this->set_url(), $this->user_data);
      return $token_string->email();
    }

  private function set_url()
  {
    if(array_key_exists("url", $this->user_data )){
      return $this->user_data["url"];
    }else{
      return null;
    }
  }

}
?>
