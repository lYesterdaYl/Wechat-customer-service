<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 4/3/2018
 * Time: 8:41 PM
 */

header('Access-Control-Allow-Origin:https://astore.kmud.net');

include_once("include/inc.php");

$openid = $_REQUEST['openid'];
$question = $_REQUEST['question'];




$sql = "update customer_service set staff_text = '".$question."' where openid = '".$openid."'";
$mysqli->query($sql);

echo $row['customer_text'];