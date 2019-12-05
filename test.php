<?php 

	$a = 9;
	$b = '09';

	echo intval($b);

	// var_dump( testFunc( '10:17 AM' ) );

	// function testFunc( $entryTime ) {

	// 	$time = explode(" ", $entryTime);

	// 	$time = explode(":", $time[0]);

	// 	if( $time[0] <= 10  && $time[1] < 15) {

	// 		return 1;

	// 	}

	// 	return 0;

	// }

	// function testFunc( $entryTime ) {

	// 	$time = explode(" ", $entryTime);

	// 	$time = explode(":", $time[0]);

	// 	if( $time[0] <= 10  && $time[1] < 15) {

	// 		return 1;

	// 	}

	// 	return 0;

	// }

	// function testFunc($hour = 0, $min = 0 ) {

	// 	$time = array();

	// 	$hour -= 7;

	// 	$min -= 15;

	// 	array_push($time, $hour);
	// 	array_push($time, $min);

	// 	return $time;

	// }


	// Function testFunc($hour = 0, $min = 0 ) {

	// 	$time = array();

	// 	if( $min >= 45 ) {

	// 		$min -= 45;

	// 	} else {

	// 		$hour -= 1;
	// 		$remainMin = 45 - $min;
	// 		$min = 60 - $remainMin;

	// 	}

	// 	array_push($time, $hour);
	// 	array_push($time, $min);

	// 	return $time;

	// }


	// function testFunc($hour = 0, $min = 0) {

	// 	$time = array();
	// 	$minToHour = 0;

	// 	if( $min >= 60 ) {

	// 		$minToHour = intdiv($min, 60);
	// 		$hour += $minToHour;
	// 		$remainMin = fmod($min, 60);

	// 		array_push($time, $hour);
	// 		array_push($time, $remainMin);

	// 	} else {

	// 		array_push($time, $hour);
	// 		array_push($time, $min);

	// 	}

	// 	return $time;
	// }





?>


<!-- Rules I maintain for this attendance -->


# Work Hour = Total Hour - 45;

# Extra Hour = Work Hour - 7:15

# Days = Work hour >= 5 then 1
	   
	   Work hour >= 3 and < 5

	   Work hour < 3 then 0

# DaMA 1 if = Entry time is < 10:15 AM && Exit time >= 5:45 PM

# Early Entry 1 if = Entry time <= 9:45 AM

# Good Entry 1 if = Entry time < 10 AM

# 15 min Relax late 1 if =  Entry time >= 10:00 AM to <= 10:14 AM

# 5 min Bad late 1 if = Entry time >= 10:15 AM <= 10:20 AM

# So Bad late 1 if = Entry time > 10:45 AM || Entry time = PM

# Good Exit 1 if = Exit time >= 05:45 PM

# Relax Exit 1 if = Exit time >= 06:00 PM to <= 06:09 PM

# Over Exit 1 if = Exit time >= 06:41 PM

# Early Exit 1 if = Exit time >= 04:00 PM to < 05:45 PM

# DaMA* 1 if = Total Hour >= 08:00 

