<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 4/3/2018
 * Time: 8:59 PM
 */

header('Access-Control-Allow-Origin:https://astore.kmud.net');

include_once("include/inc.php");

$openid = $_REQUEST['openid'];





$sql = "select * from customer_service where openid = '".$openid."'";
$result = $mysqli->query($sql);
$row = $result->fetch_array();

echo $row['customer_text'];