<?php
	
	require 'db_config.php';

	require 'style.css';

	ini_set('max_execution_time', '300'); //300 seconds = 5 minutes

	session_start(); 
?>

	<!DOCTYPE html>
	<html>
		<head>
			<title>Insert to DB</title>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		</head>
		<body>

			<nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #D4D7D7 !important;">

			  <div class="collapse navbar-collapse" id="navbarNav">
			    
			    <ul class="navbar-nav" style="margin: auto;">
			      
			      <li class="nav-item">
			        <a class="nav-link" href="index.php"><b>Home</b></a>
			      </li>

			      <li class="nav-item">
			        <a class="nav-link" href="report.php"><b>Report</b></a>
			      </li>

			    </ul>

			  </div>

			</nav>

			<div class="row" style="margin-top: 65px;">

				<div class="col-md-4"></div>

				<div class="col-md-4">

					<div class="card" style="width: 25rem; background-color: #D4D7D7;">
					  <div class="card-body">
						    
					  
<?php

	$sheetData = $_SESSION['sheetData'];

	$skipFirstOne = 0;
	$selectFLag = 0;
	$dataExistFlag = 0;

	foreach ($sheetData as $row) {
		
		if( $skipFirstOne == 0 ) {

			$skipFirstOne = 1;
			continue;

		}

		$dateTime = makeDatetime( $row[2] );

		if( $row[1] != '' || $dateTime != '') {

			// Checking if this month already exist
			if( $selectFLag == 0 ) {

				$selectSql = "SELECT * FROM attend WHERE date_time = '$dateTime' ";
				$selectResult = $conn->query($selectSql);
				if ( $selectResult->num_rows > 0 ) {

					$dataExistFlag = 1;
					break;

				}
				$selectFLag = 1;
			}

			// Insert code
			$sql = "INSERT INTO attend (user_device_id, date_time)
		 			VALUES ($row[1], '$dateTime')";

		 	if ($conn->query($sql) === TRUE) {
			    
			    echo "<p class='green'>New record created successfully.</p>";
			
			} else {

			    echo "<p class='red'>Error in: " . $sql . "<br>" . $conn->error."</p>";
			
			}

		}
		// die();


	}
	if( $dataExistFlag ) {
?>


<?php
		echo '<p class="red">Data Already Exist.</p>';

	}
	// echo 'End';
	
	$conn->close();


	function makeDatetime( $datetime ) {

		$datetimeArr = explode("|",$datetime);

		return date('Y-m-d', strtotime($datetimeArr[0])).' '.date('H:i:s', strtotime($datetimeArr[1]));
	}

	unset($_SESSION['sheetData']);

?>

					  </div>

					</div>

				</div>

				<div class="col-md-4"></div>

			</div>

		</body>
	</html>
