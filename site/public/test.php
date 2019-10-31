<?php
  //include('../vendor/autoload.php') //Incluir
  include('./frases.txt');
  require('../vendor/autoload.php');
  $name = 'Ayoze';
  $my_data = [
    'sub_name' => 'Manuel',
    'age' => 28
  ];
  //print_r($my_data); //Printear arrays de forma recursiva
  d($_SERVER); //Printear arrays de una librería incluida (solo para desarrollo)
  echo "<strong>Testing PHP</strong><br/>
        Hola $name, tienes ${my_data['age']}";

  // Show PHP info page
  //phpinfo();

  $array_cuenta = ['a', 'b', 'c'];
  $cuenta = count($array_cuenta);

  foreach($array_cuenta as $v){
    echo "<br/>Item: $v";
  }
  //foreach($array_cuenta as $k=>$v){ } //id y valor

  $str = '43';
  $num_parsed = (int)$str;

  

  echo "<br/>" . gettype($num_parsed) . "<br/>";

  //echo $_SERVER['QUERY_STRING']; //Variables de servidor: REMOTE_ADDR, REQUEST_TIME, HTTP_USER_AGENT, REQUEST_METHOD, SERVER_ADDR, QUERY_STRING

  $my_time = `uptime`; //Backticks = "ejecuta esto"
  echo $my_time;

?>






