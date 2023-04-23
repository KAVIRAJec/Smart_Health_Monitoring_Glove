
<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include_once '../database.php';
include_once '../employees1.php';
$database = new Database();

$db = $database->getConnection();
$items = new Employee1($db);
$records = $items->getEmployees1();
$itemCount = $records->num_rows;
echo json_encode($itemCount);
if($itemCount > 0){
$employee1Arr = array();
$employee1Arr["body"] = array();
$employee1Arr["itemCount"] = $itemCount;
while ($row = $records->fetch_assoc())
{
array_push($employee1Arr["body"], $row);
}
echo json_encode($employee1Arr);
}
else{
http_response_code(404);
echo json_encode(
array("message" => "No record found.")
);
}
?>