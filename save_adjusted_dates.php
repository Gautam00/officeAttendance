<?php 

	require 'db_config.php';

	$date = $_REQUEST['date']; 
	$userDeviceId = $_REQUEST['userDeviceId'];
	$value = $_REQUEST['value'];

	// Checking if data already exist or not
	$ifDataExistSql = "SELECT * FROM adjusted_days WHERE user_device_id = $userDeviceId AND dates = '$date' ";
	$dataExistRes = $conn->query( $ifDataExistSql );


	// Getting overview->total_adjusted
	$tmpDate = explode("-", $date);

	$getTotalAdjuestSql = "SELECT total_adjusted FROM overview WHERE user_device_id = $userDeviceId AND year = $tmpDate[0] AND month_no = $tmpDate[1]";
	$getTotalAdjuest = $conn->query( $getTotalAdjuestSql );
	
	if( $getTotalAdjuest ) {

		$res = mysqli_fetch_all( $getTotalAdjuest, MYSQLI_ASSOC ); 

		$totalAdjustedDays= $res[0]['total_adjusted'];
		
	} else {

		echo 'Something went wrong.'; die();

	}

	
	// if data already exist
	if( $dataExistRes->num_rows > 0 ) {

		// If adjusted then change overview -> total_adjusted
		if( $value == 1 || $value == 2 ) {

			// This check is for Select CL or SPL will add extra days
			$res2 = mysqli_fetch_all( $dataExistRes, MYSQLI_ASSOC );

			if( $res2[0]['adjusted'] == 0 ) {
			
				$totalAdjustedDays += 1;

			} 


	 
		} if( $value == 0  && $totalAdjustedDays != 0 ) { 
			echo 'acche';
			// if CL OR SPL select then select Not adjust then
			// substract -1 form overview -> total_adjusted
			$totalAdjustedDays -= 1;

		}


		$ifExistSql = "DELETE FROM adjusted_days WHERE user_device_id = $userDeviceId AND dates = '$date'";
		$ifExistRes = $conn->query( $ifExistSql );

	} else {

		$totalAdjustedDays += 1;

	}

	$updateTotalAdjustedDaysSql = "UPDATE overview SET total_adjusted = $totalAdjustedDays WHERE user_device_id = $userDeviceId AND year = $tmpDate[0] AND month_no = $tmpDate[1] ";
		$updateTotalAdjustedDaysRes = $conn->query($updateTotalAdjustedDaysSql);

	if( !updateTotalAdjustedDaysRes ){

		echo 'Something went wrong.'; die();

	}


	$datesSql = "INSERT INTO adjusted_days ( user_device_id, dates, adjusted ) VALUES( $userDeviceId, '$date', $value ) ";

	$datesRes = $conn->query($datesSql);

	if( $datesRes ) { echo 'OK'; }
	else { echo 'Error'; }

?>