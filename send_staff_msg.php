<?php
/**
 * Created by PhpStorm.
 * User: dzyol
 * Date: 4/2/2018
 * Time: 6:31 PM
 */

header('Access-Control-Allow-Origin:https://astore.kmud.net');

include_once("include/inc.php");

$openid = $_REQUEST['openid'];
$question = $_REQUEST['question'];




$sql = "update customer_service set customer_text = '".$question."' where openid = '".$openid."'";
$mysqli->query($sql);

echo $row['staff_text'];