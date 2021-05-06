<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['detsuid']==0)) {
  header('location:logout.php');
  } else{
    ?>
<!doctype html public "-//w3c//dtd html 3.2//en">
<html>
<head>
<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
    <title> Statistics on expenses of various categories</title>
</head>
<body >
<div background-color:red>
<h2 align="center" >Expenses Statistics</h2>
<?Php

include('includes/dbconnection.php');
$userid=$_SESSION['detsuid'];
if($stmt = $con->query("SELECT ExpenseItem, SUM(ExpenseCost)  FROM tblexpense where (UserId='$userid') group by ExpenseItem")){



$php_data_array = Array(); 
  echo "<table>
<tr> <th>ExpenseItem</th><th>ExpenseCost</th></tr>";
while ($row = $stmt->fetch_row()) {
   echo "<tr><td>$row[0]</td><td>$row[1]</td></tr>";
   $php_data_array[] = $row; 
   }
echo "</table>";
echo "<br>";
}else{
echo $con->error;
}




echo "<script>
        var my_2d = ".json_encode($php_data_array)."
</script>";
?>


<div id="chart_div"></div>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
 google.charts.load('current', {'packages':['corechart']});
     
      google.charts.setOnLoadCallback(draw_my_chart);
     
      function draw_my_chart() {
     
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'ExpenseItem');
        data.addColumn('number', 'ExpenseCost');
		for(i = 0; i < my_2d.length; i++)
    data.addRow([my_2d[i][0], parseInt(my_2d[i][1])]);

    var options = {  title:' Statistics on expenses of various categories',
                       width:1300,
                       height:600};

        
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
</script>
</div>
</body>
</html>

<?php } ?>





