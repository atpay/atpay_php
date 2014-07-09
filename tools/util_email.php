<!DOCTYPE html>
<html>
  <head>
    <title>PHP @Pay Email Token Generator</title>

    <?php
      if(isset($_POST['submit'])){

        require_once __DIR__.'/atpay.phar';

        $keys = [
          "private" => $_POST['private_key'],
          "public" => $_POST['public_key'],
          "atpay" => $_POST['atpay_key']
        ];

        $tokenizer = new \AtPay\Tokenizer($keys);

        $target = $_POST['target'];

        $params = [
          "type" => $_POST['type'],
          "partner_id" => $_POST['partner_id'],
          "amount" => $_POST['amount']
        ];

        $email_token = $tokenizer->email_token($target, $params);

      //  echo $email_token;
     }
    ?>

  <style>
    *{
      font-family: Arial;
      margin:0;
      padding:0;
      }

    label, input{
      clear:both;
      display:block;
      }

    input, select{
      margin-top:4px;
      margin-bottom:20px;
    }

    input[type="text"]{
      width:300px;
      padding:5px;
    }

    h1{
      margin:20px;
    }

    form{
      margin:30px 0 10px 30px;
      background: #f0f0f0;
      padding:20px;
      width:320px;
    }

    select{
      margin-top:5px;
      width:100%;
      font-size:24px;
      padding:20px;
    }

    div.token{
      float:left;
      margin: 30px;
    }

  </style>


  </head>

  <body>

    <h1>PHP @Pay Email Token Generator</h1>

    <form method="post" action="<?=$_SERVER['PHP_SELF'];?>">

      <label for="target">Partner ID:</label>
      <input type="text" name="partner_id" value="4000" />

      <label for="target">Public Key:</label>
      <input type="text" name="public_key" value="rWe8TBMoAGm/NLfg9ylq4qIwetme+7dHMIcpcnD8kCU=" />

      <label for="target">Private Key:</label>
      <input type="text" name="private_key" value="VVXPPXxZOd4NMGc+h5Sy3wINWQJ+IInpYWscRka/QUk=" />

      <label for="target">@Pay Key:</label>
      <input type="text" name="atpay_key" value="x3iJge6NCMx9cYqxoJHmFgUryVyXqCwapGapFURYh18=" />

      <label for="target">Target:</label>
      <input type="text" name="target" value="http://example.com"/>

      <label for="target">Amount:</label>
      <input type="text" name="amount" value="5.00" />

      <label for="target">Expiration (seconds):</label>
      <input type="text" name="amount" value="86400" />

      <label for="target">User Data:</label>
      <input type="text" name="user_data" value="{'sku' => '12345'}" />


      <label for="target">Type:</label>
      <select name="type" >
        <option value="url">URL</option>
        <option value="email">E-Mail</option>
      </select>

      <input type="submit" name="submit" value="Submit"/>
    </form>

    <?php
    if(isset($email_token)){ ?>
      <div class="token">
        <strong>Token:  </strong>
        <?php echo $email_token; ?>
      </div>
    <?php
    }
    ?>


  </body>

</html>
