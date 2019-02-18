<?php

session_start();

$dbhost = "localhost";
$dbname = "omat";
$dbuser = "user";
$dbpass = "salasanatänne";

mysql_connect($dbhost, $dbuser, $dbpass)
or die("OOPS ! I did it again :( Cannot connect the server!");

mysql_select_db($dbname)
or die("OH NO ! Database doesn't exit");

?>
