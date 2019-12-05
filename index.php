<!DOCTYPE html>
<html>
	<head>
		<title>Home</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	</head>
	<body>

		<nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #D4D7D7 !important;">

		  <div class="collapse navbar-collapse" id="navbarNav">
		    
		    <ul class="navbar-nav" style="margin: auto;">
		      <li class="nav-item">
		        <a class="nav-link" href="report.php"><b>Report</b></a>
		      </li>
		      <li class="nav-item">
		        <a class="nav-link" href="set_office_off_days.php"><b>Set Office Off Days</b></a>
		      </li>
		    </ul>

		  </div>

		</nav>

		<div class="row" style="margin-top: 65px;">

			<div class="col-md-4"></div>

			<div class="col-md-4">

				<div class="card" style="width: 25rem; background-color: #D4D7D7;">
				  <div class="card-body">
				    <h5 class="card-title">Upload File</h5>
					    <form method="post" enctype="multipart/form-data" action="readfile.php">
					    <div class="form-group">
					        <input type="file" name="file" class="form-control" id="exampleInputFile">
					    </div>
					    <button type="submit" class="btn btn-primary">Show data</button>
					</form>
				  </div>
				</div>

			</div>

			<div class="col-md-4"></div>

		</div>

	</body>
</html>
