
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../database.php';
include_once '../mydata.php';
$database = new Database();
$db = $database->getConnection();
$item = new Parameter($db);

$item->bpm = $_GET['bpm'];
$item->oxy = $_GET['oxy'];
$item->temp = $_GET['temp'];
$item->flex = $_GET['flex'];
date_default_timezone_set("Asia/Calcutta");
$item->time = date('Y-m-d H:i:s');
if($item->createParameter()){
echo 'data created successfully.';
} else{
echo 'data could not be created.';
}
?>