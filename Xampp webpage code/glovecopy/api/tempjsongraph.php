<?php
 $count=0;
 //$res=mysqli_query($link,"select * from parameter");
// while($row=mysqli_fetch_array($res))
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "glove";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
$sql = "SELECT * FROM parameter";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) 
 {
	$test[$count]["x"]=$row["time"];
	$test[$count]["y"]=$row["temp"];
	$count =$count+1;
 }
$data = ['foo' => 'bar'] /** whatever is being serialized **/; 
header('Content-Type: application/json'); 
echo json_encode($test);

?>
