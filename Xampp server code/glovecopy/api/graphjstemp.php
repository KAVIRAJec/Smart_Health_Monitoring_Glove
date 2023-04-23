<?php

 $link=mysqli_connect("localhost","root","");
 mysqli_select_db($link,"glove");
 $test=array();
 $count=0;
 $res=mysqli_query($link,"select * from parameter");
 while($row=mysqli_fetch_array($res))
 {
	$test[$count]["time"]=$row["time"];
	$test[$count]["y"]=$row["temp"];
	$count =$count+1;
 }

?>
<!DOCTYPE HTML>
<html>
<title>Temp Graph</title>
<meta http-equiv="refresh" content="15">
<head>
<script>
window.onload = function () {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title:{
		text: "Temperature with respect to time"
	},
	axisY: {
		title: "Temp Per Minute",
		minimum: 30,
        maximum: 40,
        interval: 5,
	},
	axisX: {
		title: "Time(in hrs)",
		labelAngle: -60,
		valueFormatString: 'DD MMM,YYYY HH:mm:ss',
		interval: 5,
	},
	data: [{
		type: "spline",
		markerSize: 5,
		xValueType: "dateTime",
		xValueFormatString: "DD-MM-YYYY HH:mm:ss",
		yValueFormatString: "#,##0#",
		dataPoints: <?php echo json_encode($test, JSON_NUMERIC_CHECK); ?>
	}]
});
 
chart.render();
 
}
</script>
</head>
<body>
<form>
 <input type="button" class="custom-btn btn-1" value="Go back!" onclick="history.back()">
</form>
<div id="chartContainer" style="height:800 px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>    

<style>
html {
    height: 100%;
  }
  </style>
  <style>
      .custom-btn {
  width: 130px;
  height: 40px;
  color: #fff;
  border-radius: 5px;
  padding: 10px 25px;
  font-family: 'Lato', sans-serif;
  font-weight: 500;
  background: transparent;
  cursor: pointer;
  transition: all 0.3s ease;
  position: relative;
  display: inline-block;
   box-shadow:inset 2px 2px 2px 0px rgba(255,255,255,.5),
   7px 7px 20px 0px rgba(0,0,0,.1),
   4px 4px 5px 0px rgba(0,0,0,.1);
  outline: none;
  }
  .btn-1 {
  position: relative;
  display: inline-block;
  padding: 5px 10px;
  color: #03e9f4;
  font-size: 13 px;
  text-decoration: none;
  text-transform: uppercase;
  overflow: hidden;
  transition: .5s;
  margin-top: 0px;
  margin-left: 0px;
  letter-spacing: 4px
}
</style>