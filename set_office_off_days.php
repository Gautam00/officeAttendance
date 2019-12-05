<?php
	
	$months = array("0","January", "February", "March", "April", "May", "June", "July", "Auguest", "September", "October", "November", "December");

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Set Office Off Days</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
		<link href="style.css" rel="stylesheet" />
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
				    <h5 class="card-title">Set Holiday</h5>

				    	<div class="form-group">

					   		<select name="holiday_month" id="holiday_month" onchange="getThisMonthDates('holiday');">
					   			
								<?php 

								  	for( $i = 1; $i < count($months);$i++ ) {

								  		echo '<option value="'.$i.'">'.$months[$i].'</option>';

								  	}

								?>

					   		</select>

					   		<select name="holiday_year" id="holiday_year" onchange="getThisMonthDates('holiday');">
					   			
								<?php 

								  	for( $i = 2019; $i <= 2023;$i++ ) {

								  		echo '<option value="'.$i.'">'.$i.'</option>';

								  	}

								?>

					   		</select>
					   		
					    </div>
					    <form method="post" action="">
					    	
					    	<select class="date-select" name="holiday_dates[]" id="holiday_dates" multiple="multiple">
							  <?php 

							  	for( $i = 1; $i <= 31; $i++ ) {

							  		echo '<option value="'.$i.'">'. $i .'</option>';

							  	}

							  ?>
							</select>

							<input type="hidden" name="hidden_holiday_month" id="hidden_holiday_month" value="">
							<input type="hidden" name="hidden_holiday_year" id="hidden_holiday_year" value="">
							<input type="hidden" name="reason" value="holiday">

							<input type="submit" value="Submit" name="submit">

					    </form>
				  </div>
				</div>

			</div>

			<div class="col-md-4"></div>

		</div>

		<div class="row" style="margin-top: 65px;">

			<div class="col-md-4"></div>

			<div class="col-md-4">

				<div class="card" style="width: 25rem; background-color: #D4D7D7;">
				  <div class="card-body">
				    <h5 class="card-title">Set Monthly Off days</h5>

				    	<div class="form-group">

					   		<select name="monthly_off_month" id="monthly_off_month" onchange="getThisMonthDates('monthly_off');">
					   			
								<?php 

								  	for( $i = 1; $i < count($months);$i++ ) {

								  		echo '<option value="'.$i.'">'.$months[$i].'</option>';

								  	}

								?>

					   		</select>

					   		<select name="monthly_off_year" id="monthly_off_year" onchange="getThisMonthDates('monthly_off');">
					   			
								<?php 

								  	for( $i = 2019; $i <= 2023;$i++ ) {

								  		echo '<option value="'.$i.'">'.$i.'</option>';

								  	}

								?>

					   		</select>
					   		
					    </div>
					    <form method="post" action="">
					    	
					    	<select class="date-select" name="monthly_off_dates[]" id="monthly_off_dates" multiple="multiple">
							  <?php 

							  	for( $i = 1; $i <= 31; $i++ ) {

							  		echo '<option value="'.$i.'">'. $i .'</option>';

							  	}

							  ?>
							</select>

							<input type="hidden" name="hidden_monthly_off_month" id="hidden_monthly_off_month" value="">
							<input type="hidden" name="hidden_monthly_off_year" id="hidden_monthly_off_year" value="">
							<input type="hidden" name="reason" value="monthly_off">

							<input type="submit" value="Submit" name="submit">

					    </form>
				  </div>
				</div>

			</div>

			<div class="col-md-4"></div>

		</div>

	</body>
</html>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>
	
	$(document).ready(function() {

	    $('.date-select').select2();

	    getThisMonthDates('holiday');

	    getThisMonthDates('monthly_off');

	});


	function getThisMonthDates( reason ) {

    	var month = $('#'+ reason +'_month').val();
    	var year = $('#' + reason + '_year').val();


    	// setting hidden month and year
    	$('#hidden_' + reason + '_month').val( month );
    	$('#hidden_' + reason + '_year').val( year );

    	// Clear select2 value
    	$('#' + reason + '_dates').val('');
		$('#' + reason + '_dates').select2();

    	$.ajax({

		  url: 'getDates.php',
		  data: { month: month, year: year, reason: reason },
		  method: 'POST',
		  cache: false,
		  success: function(result) {
		    
		    result = JSON.parse( result );

		    if( result.length > 0 ) {

		    	var selectedValues = new Array();

		    	for( i = 0;i < result.length;i++ ) {

		    		selectedValues[i] = result[i]; 

		    	}

		    	$('#' + reason + '_dates').val(selectedValues);
		    	$('#' + reason + '_dates').select2();

		    }

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


<?php 

	if( isset($_POST['submit']) ) {

		require 'db_config.php';

		$reason = $_POST['reason'];
		$year = $_POST[ 'hidden_'. $reason . '_year'];
		$month = $_POST[ 'hidden_'. $reason . '_month'];
		
		$dates = [];
		if( isset($_POST[ $reason . '_dates'] ) ) {

			$dates = $_POST[ $reason . '_dates']; 

		}
		

		if ( $reason == 'holiday' ) {
			
			$value = 0;

		} else {

			$value = 1; // 1 means monthly off

		}

		// delete all dates from selected month and year
		$delectedSql = "DELETE FROM office_off_days WHERE year = $year AND month_no = $month AND reason = $value";
		$deleteDates = $conn->query($delectedSql); 

		if( $deleteDates && count($dates) > 0) {

			foreach ($dates as $date) {
				
				$insertSql = "INSERT INTO office_off_days ( year, month_no, dates, reason ) values ( $year, $month,  $date, $value)";
				$insertRes = $conn->query( $insertSql );

			}

		}


	}

?>