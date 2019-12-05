<?php
    
    require 'vendor/autoload.php';
    require 'style.css';

    session_start();
     
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Reader\Csv;
    use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
    use PhpOffice\PhpSpreadsheet\Reader\Xls;
     
    $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
     
    if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
     
        $arr_file = explode('.', $_FILES['file']['name']);
        $extension = end($arr_file);
     
        if('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else if( $extension == 'xlsx' ) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        } else if( $extension == 'xls' ){
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        }
     
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
         
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
        $_SESSION['sheetData'] = $sheetData;


?>

        <!DOCTYPE html>
        <html>
            <head>
                <title>Show Data</title>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
            </head>
            <body>

                <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #D4D7D7 !important;">

                  <div class="collapse navbar-collapse" id="navbarNav">
                    
                    <ul class="navbar-nav" style="margin: auto;">

                      <li class="nav-item">
                        <a class="nav-link" href="index.php"><b>Back to Home</b> </a>
                      </li>

                      <li class="nav-item">
                        <a class="nav-link" href="insertToDb.php"><b>Insert to Database</b> </a>
                      </li>

                    </ul>

                  </div>

                </nav>

                <div style="text-align: center; margin-top: 50px;">
                    <h4>Check Attendance Report</h4>
                </div>

                <div class="row" style="margin-top: 28px;">

                    <div class="col-md-2"></div>

                    <div class="col-md-8">

                        <table>
                          <tr>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Datetime</th>
                            <th>If OK</th>
                          </tr>

<?php       
        
        $skipFirstOne = 0;
        $blankCount = 0;

        foreach ($sheetData as $row) {
            
            if( $skipFirstOne == 0 ) {

                $skipFirstOne = 1;
                continue;

            }

            if( $row[0] != '' || $row[1] != '' || $row[2] != '' || $row[3] != '' ) {

                echo '<tr>';
            
                    echo '<td>'. $row[0] .'</td>';
                    echo '<td>'. $row[1] .'</td>';
                    echo '<td>'. $row[2] .'</td>';
                    
                    if( $row[0] == '' || $row[1] == '' || $row[2] == '' ) {

                        echo '<td class="red">Not OK</td>';
                        $blankCount += 1;

                    } else {

                        echo '<td class="green">OK</td>';

                    }

                echo '</tr>';

            }

        }

        echo '</table>';

        echo "<h5 class='red'> Found ". $blankCount. " blank rows.</h5>";

    }
?>

                    </div>

                    <div class="col-md-2"></div>

                </div>

            </body>
        </html>