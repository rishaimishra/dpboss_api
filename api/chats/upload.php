<?php

define("SERVER","localhost");
define("USER","root");
define("PASSWORD","");
define("DB","dpboss");


//define database connection
$mysql= new mysqli(SERVER,USER,PASSWORD,DB);

$response= array();

if ($mysql->connect_error) {
    $response["MESSAGE"]="ERROR IN SERVER";
    $response["STATUS"]=500;
}else {
    echo "well done";
}

?>