<?
$host = "localhost/ip";
$dbname = "DATABASENAME";
$user = "USERNAME";
$pass = "PASSWORD";

$conn = mysql_connect($host, $user, $pass) or die("MYSQL ERROR");
$select_db = mysql_select_db($dbname) or die("Could not seelct db!");

?>