<?php 

	require 'db_config.php';

	require 'style.css';

	$sql = "SELECT * FROM users";
	$result = $conn->query($sql);

	$months = array("January", "February", "March", "April", "May", "June", "July", "Auguest", "September", "October", "November", "December");

?>

	<!DOCTYPE html>
	<html>
		<head>
			<title>Report</title>
			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		</head>
		<body>

			<nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #D4D7D7 !important;">

			  <div class="collapse navbar-collapse" id="navbarNav">
			    
			    <ul class="navbar-nav" style="margin: auto;">
			      <li class="nav-item">
			        <a class="nav-link" href="index.php"><b>Home</b></a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link" href="set_office_off_days.php"><b>Set Office Off Days</b></a>
			      </li>
			    </ul>

			  </div>

			</nav>

			<div class="row" style="margin-top: 10px;">

				<div class="col-md-4"></div>

				<div class="col-md-4">

					<div class="card" style="width: 25rem; background-color: #D4D7D7;">
					  <div class="card-body">
					    <h5 class="card-title">Report</h5>
						    <form method="post" action="">
						    <div class="form-group">

<?php

		// User select list
		if ($result->num_rows > 0) {
			
			echo '<select name="user_device_id" id="user_device_id">';

		    while($row = $result->fetch_assoc()) {

				echo '<option value="'. $row["user_device_id"] .'"> '. $row['name'] .' </option>';

		    }
		    	
		    	echo '<option value="overview"> Overview </option>';

		    echo '</select>';

		} else {

		    echo "No user found.";

		}


		// Months select list
		echo '<select name="month" id="month">';

		for( $i = 0; $i < count( $months ); $i++ ) {

			$count = $i + 1; 

			echo '<option value="'.$count.'"> '. $months[$i] .' </option>';

		}

		echo '</select>';

		// Months select list
		echo '<select name="year" id="year">';

		for( $i = 2019; $i < 2022; $i++ ) {

			echo '<option value="'.$i.'"> '. $i .' </option>';

		}

		echo '</select>';

		echo '<input type="submit" value="Submit" name="submit">';

?>
	
							</div>
						</form>
					  </div>
					</div>
				</div>

				<div class="col-md-4"></div>

			</div>
		</body>
	</html>

<?php


	$conn->close();

	if( isset($_POST['submit']) ) { //check if form was submitted

		require 'db_config.php';

		require 'style.css';

		$datetime = $_POST['year'].'-'.$_POST['month'].'-01';
		$lastDayOfMonth  = date( 't',strtotime( $datetime ) );
		$FromDateTime = $datetime;
		$ToDateTime = $_POST['year'].'-'.$_POST['month'].'-'.$lastDayOfMonth;

		$user_device_id = $_POST['user_device_id'];

		echo '<script> document.getElementById("month").value = '.$_POST["month"].';
				document.getElementById("year").value = '.$_POST["year"].'; </script>';
		
		if( $user_device_id == 'overview' ) {

			echo '<h2> Overview of '. date('d-m-Y', strtotime($FromDateTime)) .' to '. date('d-m-Y', strtotime($ToDateTime)) .'</h2>';

			echo '<script> document.getElementById("user_device_id").value = "overview";</script>';

			echo '<table>
	          <tr>
	            <th>Name</th>
	            <th>Entry</th>
	            <th>Exit</th>
	            <th>OTD</th>
	            <th>Total Leave</th>
	            <th>Total Hours</th>
	            <th>Work Hours</th>
	            <th>Extra Hours</th>
	            <th>Work Days</th>
	            <th>DaMA</th>
	            <th>DaMA*</th>
	            <th>Early Entry</th>
	            <th>Good Entry</th>
	            <th>15 Min Relax</th>
	            <th>5 Min Late</th>
	            <th>So Bad Late</th>
	            <th>Late / Deduct</th>
	            <th>Good Exit</th>
	            <th>Relax Exit</th>
	            <th>Over Exit</th>
	            <th>Early Exit</th>
	          </tr>';

	        $year = $_POST['year'];
	        $month = $_POST['month'];

	        $allOverviewSql = "SELECT * FROM overview WHERE year = $year AND month_no = $month ";

	        $overviewResult = $conn->query($allOverviewSql);

	        $totalUser = 0;
	        $totalEntry = 0;
	        $totalOTD = 0;
	        $totalExit = 0;
	        $totalWorkDays = 0;
	        $totalDama = 0;
	        $totalDamaStar = 0;
	        $totalGoodEntry = 0;
	        $totalGoodExit = 0;
	        $totalOverExit = 0;
	        $totalEarlyExit = 0;

	        while($row = $overviewResult->fetch_assoc()) {

	        	$totalUser += 1;
	        	$totalEntry += $row['entry_days'];
		        $totalExit += $row['exit_days'];
		        $totalOTD += $row['otd'];
		        $totalWorkDays += $row['work_days'];
		        $totalDama += $row['dama'];
		        $totalDamaStar += $row['dama_star'];
		        $totalGoodEntry += $row['good_entry'];
		        $totalGoodExit += $row['good_exit'];
		        $totalOverExit += $row['over_exit'];
		        $totalEarlyExit += $row['early_exit'];

	        	echo '<tr>
		            <td>'. $row["name"] .'</td>
		            <td>'. $row["entry_days"] .'</td>
		            <td>'. $row["exit_days"] .'</td>
		            <td>'. $row["otd"] .'</td>
		            <td>'. $row["total_leave"] .'(' .$row["total_adjusted"]. ')</td>
		            <td>'. $row["total_hour"] .'~' .$row["total_hour_min"]. '</td>
		            <td>'. $row["work_hour"] .'~' .$row["work_hour_min"]. '</td>
		            <td>'. $row["extra_hour"] .'~' .$row["extra_min"]. '</td>
		            <td>'. $row["work_days"] .'</td>
		            <td>'. $row["dama"] .'</td>
		            <td>'. $row["dama_star"] .'</td>
		            <td>'. $row["early_entry"] .'</td>
		            <td>'. $row["good_entry"] .'</td>
		            <td>'. $row["15_min_relax"] .'</td>
		            <td>'. $row["5_min_late"] .'</td>
		            <td>'. $row["so_bad_late"] .'</td>
		            <td>'. $row["late"] .'(' .$row["deduct"]. ')</td>
		            <td>'. $row["good_exit"] .'</td>
		            <td>'. $row["relax_exit"] .'</td>
		            <td>'. $row["over_exit"] .'</td>
		            <td>'. $row["early_exit"] .'</td>
		          </tr>';

	        }

	        echo '<tr>
	            <th>Total AVG</th>
	            <th>'. number_format( $totalEntry / $totalUser, 2 ) .'</th>
	            <th>'. number_format( $totalExit / $totalUser, 2 ) .'</th>
	            <th>'. $totalOTD .'</th>
	            <th>-</th>
	            <th>-</th>
	            <th>-</th>
	            <th>-</th>
	            <th>'. number_format( $totalWorkDays / $totalUser, 2 ) .'</th>
	            <th>'. $totalDama .'</th>
	            <th>'. $totalDamaStar .'</th>
	            <th>-</th>
	            <th>'. number_format( $totalGoodEntry / $totalUser, 2 ) .'</th>
	            <th>-</th>
	            <th>-</th>
	            <th>-</th>
	            <th>-</th>
	            <th>'. number_format( $totalGoodExit / $totalUser, 2 ) .'</th>
	            <th>-</th>
	            <th>'. number_format( $totalOverExit / $totalUser, 2 ) .'</th>
	            <th>'. number_format( $totalEarlyExit / $totalUser, 2 ) .'</th>
	          </tr>';

	       echo '</table>';

		} else { //For individual report

			$getUserSql = "SELECT name FROM users WHERE user_device_id = $user_device_id ";
			$userResult = $conn->query($getUserSql);
			$userRow = $userResult->fetch_assoc();
			$userName = $userRow['name'];

			echo '<h2>Attendance Report For - '. $userName. '. From :'. date('d-m-Y', strtotime($FromDateTime)) .' To: '. date('d-m-Y', strtotime($ToDateTime)) .'  </h2>';

			echo '<script> document.getElementById("user_device_id").value = '.$user_device_id.'; </script>';

			echo '<table class="width100">
	          <tr>
	            <th>Date</th>
	            <th>Entry</th>
	            <th>Exit</th>
	            <th>OTD</th>
	            <th>Notes</th>
	            <th>Total Hours</th>
	            <th>Work Hours</th>
	            <th>Extra Hours</th>
	            <th>Days</th>
	            <th>DaMA</th>
	            <th>Early Entry</th>
	            <th>Good Entry</th>
	            <th>15 min Relax Late</th>
	            <th>5 min Bad Late</th>
	            <th>So Bad Late</th>
	            <th>Good Exit</th>
	            <th>Relax Exit</th>
	            <th>Over Exit</th>
	            <th>Early Exit</th>
	            <th>DaMA*</th>
	          </tr>';


	        $totalHour = $totalMin = 0;
	        $totalEntry = $totalExit = 0;
	        $totalOTD = 0;
	        $totalWorkHour = $totalWorkMin = 0;
	        $totalExtraHour = $totalExtraMin = 0;
	        $totalDays = 0;
	        $totalDaMA = 0;
	        $totalEarlyEntry = 0;
	        $totalGoodEntry = 0;
	        $totalRelaxLate = 0;
	        $totalBadLate = 0;
	        $totalSoBadLate = 0;
	        $totalGoodExit = 0;
	        $totalRelaxExit = 0;
	        $totalOverExit = 0;
	        $totalEarlyExit = 0;
	        $totalDamaStar = 0;
	        $totalOneEntry = 0;
	        $totalLeave = 0;

	        for( $i = 1; $i <= $lastDayOfMonth; $i++ ) {
	        	
	        	$colorRedRow = 0;

	        	$date = $_POST['year'].'-'.$_POST['month'].'-'.$i;

	        	$sql = "SELECT * FROM attend WHERE user_device_id = $user_device_id AND date(date_time) = '$date' ";
				$result = $conn->query($sql);

				// if there is any data found on that data
				if ( $result->num_rows > 0 ) {

					$row = mysqli_fetch_all( $result, MYSQLI_ASSOC );

					// This lines are for if there is only one entry
					if( count($row) == 1 ) {

						$time = calOneEntry( $row[0]['date_time'] );

						$mainEntryTime = $time['entry_time'];
						$startTime = new DateTime( $mainEntryTime );
						
						$mainExitTime = $time['exit_time'];
						$endTime   = new DateTime( $mainExitTime );

						if( $time['entry_time_flag'] ) {

							$entryTime = date( 'h:i A', strtotime( $time['entry_time'] ) );
							
							$exitTime = date( 'h:i A', strtotime( $time['exit_time'] ) );

							$totalEntry += 1;

						} else {

							$entryTime = date( 'h:i A', strtotime( $time['entry_time'] ) );
							
							$exitTime = date( 'h:i A', strtotime( $time['exit_time'] ) );

							$totalExit += 1;

						}

						$totalOneEntry += 1;
						$colorRedRow = 1;

					} else { // both entry(entry and exit)

						// Entry time
						$entryTime = current( $row );
						$mainEntryTime = $entryTime['date_time'];
						$entryTime = date( 'h:i A', strtotime( $entryTime['date_time'] ) );
						$startTime = new DateTime( $mainEntryTime );
						$totalEntry += 1;
		  
						// Exit time
						$exitTime = end( $row );
						$mainExitTime = $exitTime['date_time'];
						$exitTime = date( 'h:i A', strtotime( $exitTime['date_time'] ) );
						$endTime   = new DateTime( $mainExitTime );
						$totalExit += 1;

					}

					// Calculating OTD
					$otd = '';
					$notes = '';
					// if($i != 10){

					$checkotdRes = checkOffDate( $date, $user_device_id, 1 );
				
					if( $checkotdRes ) { $otd = 1; $totalOTD += 1; $notes = $checkotdRes; }

					// }


					// Time difference 
					$timeDiff  = $startTime->diff( $endTime );

					// Calculate Total Hours
					$totalHour += $timeDiff->format("%H");
					$totalMin += $timeDiff->format("%I");


					// Calculate Work hours for each
					if( $timeDiff->format("%I") >= 45 || $timeDiff->format("%H") >= 1) {
						
						$workHour = calWorkHour( $timeDiff->format("%H"), $timeDiff->format("%I") );

					} else {

						$workHour = [0,0];

					}

					$totalWorkHour += $workHour[0];
					$totalWorkMin += $workHour[1];

					// Calculate Extra hours for each
					if( ( $workHour[0] > 7 ) ||  ( $workHour[0] == 7 && $workHour[1] >= 15 ) ) {

						$workHourRes = calExtraHour( $workHour[0], $workHour[1] );

						$extraHour = $workHourRes[0];
						$extraMin = $workHourRes[1];

						$totalExtraHour += $extraHour; 
						$totalExtraMin += $extraMin; 

						$extraMin = $extraMin < 10 ? '0'.$extraMin : $extraMin;

					} else {

						$extraHour = '-';
						$extraMin = '-';

					}

					// Calculate Days(Let 5 hours ->ask boss)
					if( $workHour[0] >= 5 ) {

						$days = 1;

					} else if( $workHour[0] < 3 ) {

						$days = 0;

					} else {

						$days = 0.5;

					}

					$totalDays += $days;

					// Calculate DaMA
					$dama = '-';
					$damaRes = calDaMa( $entryTime, $exitTime );

					if( $damaRes ) {

						$dama = 1;
						$totalDaMA += 1;

					}

					// Calculate Early Entry
					$earlyEntry = '-';
					$earlyEntryRes = calEarlyEntry( $entryTime );

					if( $earlyEntryRes ) {

						$earlyEntry = 1;
						$totalEarlyEntry += 1;

					}

					// Calculate Good Entry
					$goodEntry = '-';
					$goodEntryRes = calGoodEntry( $entryTime );

					if( $goodEntryRes ) {

						$goodEntry = 1;
						$totalGoodEntry += 1;

					}

					// Calculate 15 min relax late
					$relaxLate = '-';
					$relaxLateRes = calRelaxLate( $entryTime );

					if( $relaxLateRes ) {

						$relaxLate = 1;
						$totalRelaxLate += 1;

					}

					// Calculate 5 min Bad Late
					$badLate = '-';
					$badLateRes = calBadLate( $entryTime );

					if( $badLateRes ) {

						$badLate = 1;
						$totalBadLate += 1;

					}

					// Calculate So Bad Late
					$soBadLate = '-';
					$soBadLateRes = calSoBadLate( $entryTime );

					if( $soBadLateRes ) {

						$soBadLate = 1;
						$totalSoBadLate += 1;

					}

					// Calculate Good exit
					$goodExit = '-';
					$goodExitRes = calGoodExit( $exitTime );

					if( $goodExitRes ) {

						$goodExit = 1;
						$totalGoodExit += 1;

					}

					// Calculate Relax Exit
					$relaxExit = '-';
					$relaxExitRes = calRelaxExit( $exitTime );

					if( $relaxExitRes ) {

						$relaxExit = 1;
						$totalRelaxExit += 1;

					}

					// Calculate Over Exit
					$overExit = '-';
					$overExitRes = calOverExit( $exitTime );

					if( $overExitRes ) {

						$overExit = 1;
						$totalOverExit += 1;

					}

					// Calculate Early Exit
					$earlyExit = '-';
					$earlyExitRes = calEarlyExit( $exitTime );

					if( $earlyExitRes ) {

						$earlyExit = 1;
						$totalEarlyExit += 1;

					}

					// Calculate DaMA*
					$damaStar = '-';
					if(  $timeDiff->format("%H") >= 8  ) {

						$damaStar = 1;
						$totalDamaStar += 1;

					}

					// Give star before missing time
					if( count($row) == 1 ) {

						if( $time['entry_time_flag'] ) {

							$exitTime = '* '.$exitTime; 

						} else {

							$entryTime = '* '.$entryTime; 

						}

					}

					// Table rows for each day
					echo '<tr>';
	        		
						echo '<td>'.$i.'</td>';
						echo '<td>'.$entryTime.'</td>';
						echo $colorRedRow ? '<td class="red">'.$exitTime.'</td>' : '<td>'.$exitTime.'</td>';
						echo '<td>'.$otd.'</td>';
						echo '<td>'.$notes.'</td>';
						echo '<td>'.$timeDiff->format("%H:%I").'</td>';
						echo '<td>'.$workHour[0].':'.$workHour[1].'</td>';
						echo '<td>'.$extraHour.':'.$extraMin.'</td>';
						
						if($days == 0) {echo '<td class="red">* '.$days.'</td>';}
						else {echo '<td>'.$days.'</td>';}
						
						echo '<td>'.$dama.'</td>';
						echo '<td>'.$earlyEntry.'</td>';
						echo '<td>'.$goodEntry.'</td>';
						echo '<td>'.$relaxLate.'</td>';
						echo '<td>'.$badLate.'</td>';
						echo '<td>'.$soBadLate.'</td>';
						echo '<td>'.$goodExit.'</td>';
						echo '<td>'.$relaxExit.'</td>';
						echo '<td>'.$overExit.'</td>';
						echo '<td>'.$earlyExit.'</td>';
						echo '<td>'.$damaStar.'</td>';

	                echo '</tr>';



				} else {

					// For counting total leaves
					$offDayRes = checkOffDate( $date, $user_device_id );
					if( is_array($offDayRes) ) {

						$totalLeave += 1;
						$offDayRes = $offDayRes[0];

					}

					echo '<tr>';
	        		
						echo '<td>'.$i.'</td>
							  <td>-</td>
							  <td>-</td>
							  <td></td>
							  '. $offDayRes .'
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>
							  <td>-</td>';

	                echo '</tr>';

				}

	        }

	        $totalHours = calTotalHours( $totalHour, $totalMin ); 
	        $workHours = calTotalHours( $totalWorkHour, $totalWorkMin ); 
	        $extraHours = calTotalHours( $totalExtraHour, $totalExtraMin ); 

	        echo '<tr>';
	        		
				echo '<th>Total</th>';
				echo '<th>'.$totalEntry.'</th>';
				echo '<th>'.$totalExit.'</th>';
				echo '<th>'.$totalOTD.'</th>';
				echo '<th></th>';
				echo '<th>'.$totalHours[0].':'.$totalHours[1].'</th>';
				echo '<th>'.$workHours[0].':'.$workHours[1].'</th>';
				echo '<th>'.$extraHours[0].':'.$extraHours[1].'</th>';
				echo '<th>'.$totalDays.'</th>';
				echo '<th>'.$totalDaMA.'</th>';
				echo '<th>'.$totalEarlyEntry.'</th>';
				echo '<th>'.$totalGoodEntry.'</th>';
				echo '<th>'.$totalRelaxLate.'</th>';
				echo '<th>'.$totalBadLate.'</th>';
				echo '<th>'.$totalSoBadLate.'</th>';
				echo '<th>'.$totalGoodExit.'</th>';
				echo '<th>'.$totalRelaxExit.'</th>';
				echo '<th>'.$totalOverExit.'</th>';
				echo '<th>'.$totalEarlyExit.'</th>';
				echo '<th>'.$totalDamaStar.'</th>';

		    echo '</tr>';

		echo '</table>';


		if( $totalOneEntry > 0 ) {

			echo '<p class="red">* = Only one entry found. entryTime + 1 hour</p>';
			echo '<p class="red">There are total '.$totalOneEntry.' one entry.</p>';

		}

		    // This codes for storing each overview data
		    $month_no = $_POST['month'];
		    $year = $_POST['year'];

		    $userOverviewSql = "SELECT * FROM overview WHERE year = $year AND month_no = $month_no AND user_device_id = $user_device_id";
		    $overviewResult = $conn->query($userOverviewSql);

		    // Calculate late days
		    $late = $totalBadLate + $totalSoBadLate;
		    	
	    	// Calculate deduct days ($late / 3)
	    	$lateDiv = $late / 3;
	    	$deduct = floor( $lateDiv );
	    	$subsLate = $lateDiv - $deduct;
	    	if( $subsLate > 0.5 ) {

	    		$deduct += 0.5;

	    	}

	    	// updating overview values if data already exist
		    if( $overviewResult->num_rows > 0 ) {

		    	// $deleteUserOverviewSql = "DELETE FROM overview WHERE year = $year AND month_no = $month_no AND user_device_id = $user_device_id";
		    	$updateUserOverviewSql = "UPDATE overview SET year = $year, month_no = $month_no, user_device_id = $user_device_id, name = '$userName', entry_days = $totalEntry, exit_days = $totalExit, otd = $totalOTD, total_leave = $totalLeave, total_hour = $totalHours[0], total_hour_min = $totalHours[1], work_hour = $workHours[0], work_hour_min = $workHours[1], extra_hour = $extraHours[0], extra_min = $extraHours[1], work_days = $totalDays, dama = $totalDaMA, dama_star = $totalDamaStar, early_entry = $totalEarlyEntry, good_entry = $totalGoodEntry, 15_min_relax = $totalRelaxLate, 5_min_late = $totalBadLate, so_bad_late = $totalSoBadLate, late = $late, deduct = $deduct, good_exit = $totalGoodExit, relax_exit = $totalRelaxExit, over_exit = $totalOverExit, early_exit = $totalEarlyExit  WHERE year = $year AND month_no = $month_no AND user_device_id = $user_device_id";
		    	$updateUserOverviewRes = $conn->query($updateUserOverviewSql);

		    }

		    // Check if this user and month overview exist
		    if( $overviewResult->num_rows == 0 && $totalEntry > 0 && $totalExit > 0 ) {

			    $overviewSql = "INSERT INTO overview ( year, month_no, user_device_id, name, entry_days, exit_days, otd, total_leave, total_hour, total_hour_min, work_hour, work_hour_min, extra_hour, extra_min, work_days, dama, dama_star, early_entry, good_entry, 15_min_relax, 5_min_late, so_bad_late, late, deduct, good_exit, relax_exit, over_exit, early_exit ) VALUES ( $year, $month_no, $user_device_id, '$userName', $totalEntry, $totalExit, $totalOTD, $totalLeave, $totalHours[0], $totalHours[1], $workHours[0], $workHours[1], $extraHours[0], $extraHours[1], $totalDays, $totalDaMA, $totalDamaStar, $totalEarlyEntry, $totalGoodEntry, $totalRelaxLate, $totalBadLate, $totalSoBadLate, $late, $deduct, $totalGoodExit, $totalRelaxExit, $totalOverExit, $totalEarlyExit )";

			 	if ($conn->query($overviewSql) === TRUE) {
				    
				    // echo "<p class='green'>New record created successfully.</p>";
				
				} else {

				    echo "<p class='red'>Error in: " . $overviewSql . "<br>" . $conn->error."</p>";
				
				}

		    }

		}

	}


	function calTotalHours( $hour = 0, $min = 0 ) {

		$time = array();
		$minToHour = 0;

		if( $min >= 60 ) {

			$minToHour = intdiv($min, 60);
			$hour += $minToHour;
			$remainMin = fmod($min, 60);

			array_push($time, $hour);
			array_push($time, $remainMin);

		} else {

			array_push($time, $hour);
			array_push($time, $min);

		}

		return $time;

	}

	// Calculate $workhour = $totalHour - 45
	function calWorkHour( $hour = 0, $min = 0 ) {

		$time = array();

		if( $min >= 45 ) {

			$min -= 45;

		} else {

			$hour -= 1;
			$remainMin = 45 - $min;
			$min = 60 - $remainMin;

			if( $hour < 10 ) {
				
				$hour = '0'.$hour;

			}

		}

		if( $min < 10 ) {
			
			$min = '0'.$min;

		}

		array_push($time, $hour);
		array_push($time, $min);

		return $time;

	}

	// Calculate $extraHour = $workHour - 7:15
	function calExtraHour( $hour = 0, $min = 0 ) {

		$time = array();

		if( $min >= 15 ) {

			$hour -= 7;
			$min -= 15;

		} else {

			$hour -= 8;
			$remainMin = 15 - $min;
			$min = 60 - $remainMin;

		}

		$hour = $hour < 10 ? '0' . $hour : $hour;

		array_push($time, $hour);
		array_push($time, $min);

		return $time;

	}

	// Calculate based on when ( Entry hour < 10 AM ) Or ( Entry hour = 10 AM and Entry minute < 15 )
	// And Exit time must be at least 05:45
	function calDaMa( $entryTime, $exitTime ) {

		$entryTime = explode(" ", $entryTime);

		$time = explode(":", $entryTime[0]);

		if( ( ( $time[0] < 10 ) || ( $time[0] == 10 && $time[1] < 15 ) ) && ( $entryTime[1] == 'AM' ) ) {

			$exitTime = explode(" ", $exitTime);

			$time = explode(":", $exitTime[0]);

			if( ( ( $time[0] > 5 ) || ( $time[0] == 5  && $time[1] >= 45 ) ) && ( $exitTime[1] == 'PM' )  ) {

				return 1;

			}

		} 

		return 0;

	}

	// Calculate based on when ( Entry hour <= 9 AM and Entry minute <= 45 )
	function calEarlyEntry( $entryTime ) {

		$entryTime = explode(" ", $entryTime);

		$time = explode(":", $entryTime[0]);

		if( ( ( $time[0] < 9 ) || ( $time[0] <= 9 && $time[1] <= 45 ) ) && ( $entryTime[1] == 'AM' ) ) {

			return 1;

		}

		return 0;

	}

	// Calculate based on when ( Entry hour < 10 AM )
	function calGoodEntry( $entryTime ) {

		$entryTime = explode(" ", $entryTime);

		$time = explode(":", $entryTime[0]);

		if( $time[0] < 10 && $entryTime[1] == 'AM') {

			return 1;

		}

		return 0;

	}

	// Calculate based on when ( Entry hour = 10 AM and Entry minute < 15 )
	function calRelaxLate( $entryTime ) {

		$entryTime = explode(" ", $entryTime);

		$time = explode(":", $entryTime[0]);

		if( $time[0] == 10 && $time[1] < 15 && $entryTime[1] == 'AM' ) {

			return 1;

		}

		return 0;

	}

	// Calculate based on when ( Entry hour = 10 AM and Entry minute is between 15 to 20 )
	function calBadLate( $entryTime ) {

		$entryTime = explode(" ", $entryTime);

		$time = explode(":", $entryTime[0]);

		if( $time[0] == 10 && $time[1] >= 15 && $time[1] <= 20 && $entryTime[1] == 'AM' ) {

			return 1;

		}

		return 0;

	}

	// Calculate based on when ( Entry hour >= 10 AM and Entry minute > 45 )
	function calSoBadLate( $entryTime ) {

		$entryTime = explode(" ", $entryTime);

		$time = explode(":", $entryTime[0]);

		if( ( ( $time[0] > 10 ) || ( $time[0] == 10 && $time[1] > 45 ) )  && ( $entryTime[1] == 'AM' ) ) {

			return 1;

		} if ( $entryTime[1] == 'PM' ) {

			return 1;

		}

		return 0;

	}

	// Calculate based on when ( Exit hour > 5 PM ) Or ( Exit hour = 5 PM and Exit minute >= 45 )
	function calGoodExit( $exitTime ) {

		$exitTime = explode(" ", $exitTime);

		$time = explode(":", $exitTime[0]);

		if( ( ( $time[0] > 5 ) || ( $time[0] == 5 && $time[1] >= 45 ) )  && ( $exitTime[1] == 'PM' ) ) {

			return 1;

		} 

		return 0;

	}

	// Calculate based on when ( Exit hour == 6 PM and Exit minute is between 0 to 9 )
	function calRelaxExit( $exitTime ) {

		$exitTime = explode(" ", $exitTime);

		$time = explode(":", $exitTime[0]);

		if( $time[0] == 6 && $time[1] >= 0 && $time[1] < 10  && $exitTime[1] == 'PM' ) {

			return 1;

		}

		return 0;

	}

	// Calculate based on when ( Exit hour >= 6 PM and Exit minute >= 41 )
	function calOverExit( $exitTime ) {

		$exitTime = explode(" ", $exitTime);

		$time = explode(":", $exitTime[0]);

		if( ( ( $time[0] > 6 ) || ( $time[0] == 6 && $time[1] >= 41 ) ) && ( $exitTime[1] == 'PM' ) ) {

			return 1;

		}

		return 0;

	}

	// Calculate based on when ( Exit hour >= 4 PM) Or ( Exit hour = 5 PM and Exit minute < 45 )
	function calEarlyExit( $exitTime ) {

		$exitTime = explode(" ", $exitTime);

		$time = explode(":", $exitTime[0]);

		if( ( ( $time[0]  == 4 ) || ( $time[0] == 5 && $time[1] < 45 ) ) && ( $exitTime[1] == 'PM' ) ) {

			return 1;

		} 

		return 0;

	}


	// Calculating for one entry datas
	function calOneEntry( $entryTime ) {

		$times = array();

		// exploding date time like 2019-10-15 14:10:52
		$firstEntry = explode(" ", $entryTime);

		// exploding time like 14:10:52
		$tmpEntryTime = explode(":", $firstEntry[1]);

		// if time is greater than 2 pm than it is exit time
		// if( $tmpEntryTime[0] > 14 ) {

		// 	$times['exit_time_flag'] = true; 
		// 	$times['entry_time_flag'] = false; 
		// 	$times['exit_time'] = $entryTime; 
		// 	$enTime = strtotime( $firstEntry[1] ) - 3600; 
		// 	$times['entry_time'] = $firstEntry[0]." ".date('H:i:s',$enTime);
			
		// } else {

			$times['entry_time_flag'] = true; 
			$times['exit_time_flag'] = false; 
			$times['entry_time'] = $entryTime; 
			$enTime = strtotime( $firstEntry[1] ) + 3600; 
			$times['exit_time'] = $firstEntry[0]." ".date('H:i:s',$enTime);

		// }

		return $times;

	}

	// Check if there is weekend or public holiday or leave
	function checkOffDate( $date, $user_device_id, $otd = 0 ) {

		require 'db_config.php';

		$timestamp = strtotime( $date );
		$day = date('D', $timestamp);
		$otherOffDayFlag = 0;

		// Checking weekend days
		if( $day == "Fri" || $day == "Sun") {

			if( $day == "Fri" ) { $weekend = 0; }  
		    if( $day == "Sun" ) { $weekend = 1; }

		    $checkUserSql = "SELECT * FROM users WHERE user_device_id = $user_device_id AND weekend = $weekend";
		    $checkUserRes = $conn->query( $checkUserSql );

		    if($checkUserRes->num_rows > 0) {

		    	return $otd ? 'Week End' : '<td>Week End</td>';

		    } else {

		    	$otherOffDayFlag = 1;

		    }

		} else {

			$otherOffDayFlag = 1;

		}

		// Checking holiday and Monthly off days
		if( $otherOffDayFlag ) {

			$tmpDate = explode("-", $date);

			$checkOfficeOffSql = "SELECT * FROM office_off_days WHERE year = $tmpDate[0] AND month_no = $tmpDate[1] AND dates = $tmpDate[2]";
		    $checkOfficeOffRes = $conn->query( $checkOfficeOffSql );

		    if( $checkOfficeOffRes->num_rows > 0 ) {

		    	$row = mysqli_fetch_all( $checkOfficeOffRes, MYSQLI_ASSOC );
		    	if ( count( $row ) == 1) {
		    		
		    		foreach ($row as $data) {
		    			
		    			if( $data['reason'] == 0 ) {

		    				return $otd ? 'Public Holiday' : '<td>Public Holiday</td>';

		    			} if ( $data['reason'] == 1 ) {
		    				
		    				return $otd ? 'Monthly Off' : '<td>Monthly Off</td>'; 

		    			}

		    		}

		    	} if ( count( $row ) > 1) {
		    		
		    		return $otd ? 'Public Holiday' : '<td>Public Holiday</td>';

		    	}

		    }
		}


		// Checking if it's already adjusted or not
		$checkAdjustedSql = "SELECT * FROM adjusted_days WHERE user_device_id = $user_device_id AND dates = '$date' ";
		$checkAdjustedRes = $conn->query( $checkAdjustedSql );

		if( $checkAdjustedRes->num_rows > 0 ) {

			$row = mysqli_fetch_all( $checkAdjustedRes, MYSQLI_ASSOC );
	    	if ( count( $row ) == 1) {
	    		
	    		foreach ($row as $data) {
	    			
	    			if( $data['adjusted'] == 0 ) { return array( '<td class="red">Absent</td>' ); } 
	    			if ( $data['adjusted'] == 1 ) { return array( '<td>CL</td>' ); }
	    			if ( $data['adjusted'] == 2 ) { return array( '<td>SPL</td>' ); }

	    		}

	    	} 

		}

		// set select option in row if user takes a leave
		$enDate = json_encode($date);
		$html = "<td><select id='".$date."-".$user_device_id."-adjusted' onchange='saveAdjustedDates(".$enDate.",".$user_device_id.")'>
					  <option value = ''> </option>
					  <option value = '1'> CL </option>
					  <option value = '2'> SPL </option>
					  <option value = '0'> Not Adjusted </option>
					</select></td>";

		return $otd ? 0 : array( $html );

	}

?>

<script>
	
	function saveAdjustedDates( date, userDeviceId ) {

		var value = $('#'+date+'-'+userDeviceId+'-adjusted').val();

		$.ajax({

		  url: 'save_adjusted_dates.php',
		  data: { date: date, userDeviceId: userDeviceId, value: value },
		  method: 'POST',
		  cache: false,
		  success: function(result) {
		    
		    console.log(result);

		  },
		  error: function(jqXHR, textStatus, errorThrown) {

		    if(jqXHR.status == '500') {
		      alert('Internal server error: 500');
		    } else {
		      alert('Unexpected error');
		    }

		  }

		})

	}

</script>