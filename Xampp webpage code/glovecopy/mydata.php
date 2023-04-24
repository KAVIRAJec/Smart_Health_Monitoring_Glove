<?php
class Parameter{
// dbection
private $db;
// Table
private $db_table = "parameter";
// Columns
public $id;
public $bpm;
public $oxy;
public $temp;
public $flex;
public $time;
public $result;


// Db dbection
public function __construct($db){
$this->db = $db;
}

// GET ALL
public function getParameters(){
$sqlQuery = "SELECT id, bpm, oxy, temp, flex ,time FROM " . $this->db_table . "";
$this->result = $this->db->query($sqlQuery);
return $this->result;
}

// CREATE
public function createParameter(){
// sanitize
$this->bpm=htmlspecialchars(strip_tags($this->bpm));
$this->oxy=htmlspecialchars(strip_tags($this->oxy));
$this->temp=htmlspecialchars(strip_tags($this->temp));
$this->flex=htmlspecialchars(strip_tags($this->flex));
$this->time=htmlspecialchars(strip_tags($this->time));
$sqlQuery = "INSERT INTO
". $this->db_table ." SET bpm = '".$this->bpm."',
oxy = '".$this->oxy."',
temp = '".$this->temp."',flex = '".$this->flex."',
time = '".$this->time."'";
$this->db->query($sqlQuery);
if($this->db->affected_rows > 0){
return true;
}
return false;
}

// UPDATE
public function getSingleMydata(){
$sqlQuery = "SELECT id, bpm, oxy, temp, flex, time FROM
". $this->db_table ." WHERE id = ".$this->id;
$record = $this->db->query($sqlQuery);
$dataRow=$record->fetch_assoc();
$this->bpm = $dataRow['bpm'];
$this->oxy = $dataRow['oxy'];
$this->temp = $dataRow['temp'];
$this->flex = $dataRow['flex'];
$this->time = $dataRow['time'];
}

// UPDATE
public function updateMydata(){
$this->bpm=htmlspecialchars(strip_tags($this->bpm));
$this->oxy=htmlspecialchars(strip_tags($this->oxy));
$this->temp=htmlspecialchars(strip_tags($this->temp));
$this->flex=htmlspecialchars(strip_tags($this->flex));
$this->time =htmlspecialchars(strip_tags($this->time));
$this->id=htmlspecialchars(strip_tags($this->id));

$sqlQuery = "UPDATE ". $this->db_table ." SET bpm = '".$this->bpm."',
oxy = '".$this->oxy."',
temp = '".$this->temp."',flex = '".$this->flex."',
time = '".$this->time."'
WHERE id = ".$this->id;

$this->db->query($sqlQuery);
if($this->db->affected_rows > 0){
return true;
}
return false;
}

// DELETE
function deleteMydata(){
$sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id = ".$this->id;
$this->db->query($sqlQuery);
if($this->db->affected_rows > 0){
return true;
}
return false;
}
}
?>

