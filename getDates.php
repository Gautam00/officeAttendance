<?php 

	require 'db_config.php';

	$reason = $_REQUEST['reason']; 
	$month = $_REQUEST['month'];
	$year = $_REQUEST['year'];

	if ( $reason == 'holiday' ) {
			
		$value = 0;

	} else {

		$value = 1; // 1 means monthly off

	}


	$datesSql = "SELECT dates FROM office_off_days WHERE year = '$year' AND month_no = '$month' AND reason = $value ";
	$datesRes = $conn->query($datesSql);

	$row = mysqli_fetch_all( $datesRes, MYSQLI_ASSOC );

	if( count( $row ) > 0 ) {

		$res = array();

		foreach ($row as $data) {
			
			array_push($res, $data['dates']);
		
		}

		$res = json_encode($res);
		echo $res;

	} else {

		echo "[]";

	}

?>