<?php

session_start();
$servername="localhost";
$db_username="root";
$db_password="";
$db_name="zadruga";

$conn=mysqli_connect($servername, $db_username, $db_password, $db_name);

if(!$conn){
    die("Neuspesna konekcija");
}