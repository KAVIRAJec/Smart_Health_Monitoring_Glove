<?php
$conn = mysqli_connect("localhost", "root", "", "glove");
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT id, readings FROM parameter";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["id"]. "</td>
    <td>" . $row["oxy"] . "</td></tr>";
}
echo "</table>";
} else { echo "0 results"; }
$conn->close();
?>