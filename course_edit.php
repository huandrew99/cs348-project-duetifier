<?php
require("assets/functions/functions.php");

// CSRF Protection
require 'assets/functions/CSRF_Protect.php';
$csrf = new CSRF_Protect();

// Error Reporting Active
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Purdue Calendar">
    <meta name="author" content="TEAM47">

    <title>Event Calendar</title>

    <!-- Bootstrap Core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/styles.css" rel="stylesheet">	
	<!-- DateTimePicker CSS -->
	<link href="assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">	
	<!-- DataTables CSS -->
    <link href="assets/css/dataTables.bootstrap.css" rel="stylesheet">	
	<!-- FullCalendar CSS -->
	<link href="assets/css/fullcalendar.css" rel="stylesheet" />
	<link href="assets/css/fullcalendar.print.css" rel="stylesheet" media="print" />	
	<!-- jQuery -->
    <script src="assets/js/jquery.js"></script>	
	<!-- SweetAlert CSS -->
	<script src="assets/js/sweetalert.min.js"></script> 
	<link rel="stylesheet" type="text/css" href="assets/css/sweetalert.css">
    <!-- Custom Fonts -->
    <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">


</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
        <div class="container topnav">
            <div class="navbar-header">
                <a class="navbar-brand topnav" href="#purduecalendar">
                    <i class="fa fa-calendar" aria-hidden="true"></i> Purdue Calendar
                </a>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div id="purduecalendar"></div>
    <div class="content-section-a">
			
        <!--BEGIN PLUGIN -->
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default dash">
                    <h2 class="sub-header">EDIT COURSE THEME</h2>

                    <!-- Course Theme Edit Form -->
                    <form id="editTheme" method="post" enctype="multipart/form-data" class="form-horizontal" name="editTheme">

                        <!-- 1. Course Selection input -->
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="title">
                                Course Theme
                                <?php
                                    // Show the color of current selection by onchange function
                                    $sql = mysqli_query($connection, "select colorname from class natural join color WHERE class_no='".$_GET['id']."'");
                                    $row = mysqli_fetch_assoc($sql);
                                    echo "<span id='show' style='width: 15px; height: 15px; margin:auto; display: inline-block; vertical-align: middle; border-radius: 2px; background: ";
                                    echo $row['colorname'];
                                    echo "'></span> ";
                                ?>
                            </label>
                            <div class="col-md-6">
                                <!-- onchange function -->
                                <script>
                                    setColor = function(val) {
                                            var color = document.getElementById("color").value;
                                            $("#show").css("background", color);
                                        }
                                </script>
                                    
                                <select name='color' id='color' class="form-control input-md" onchange="setColor()">
                                    <?php
                                        echo getColor(antiSQLInjection($_GET['id']));
                                        /* Prepared statements */
                                        $Colors->execute();
                                        $query = $Colors->get_result();
                                        echo "<option value='No course Selected' required>---------</option>";
                                        while ($row = mysqli_fetch_assoc($query)) {
                                            echo "<option value='".$row['colorname']."'>".$row['colorname']."</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <?php $csrf->echoInputField(); ?>
							
                        <!-- Submit Button -->
                        <div class='form-group'>
                            <label class='col-md-8 control-label' for='singlebutton'></label>
                            <div class='col-md-4'>
                                <input type='submit' name='editTheme' class='btn btn-success' value='Edit Course Theme' />
                            </div>
                        </div>

                        </fieldset>
                    </form>
					</div>
				  </div>


            <?php
            // Update course theme information in database
			if(isset($_POST['editTheme'])) {
                /* Set transaction level */
                $connection->query("SET TRANSACTION ISOLATION LEVEL READ COMMITTED");
                /* Start transaction */
                mysqli_begin_transaction($connection);
                try {
                    // Get course theme color id
                    /* Prepared procedure */
                    $colorIDQuery = $connection->prepare("SELECT color_id FROM color WHERE colorname = '".$_POST['color']."'");
                    $colorIDQuery->execute();
                    $colorIDRow = mysqli_fetch_assoc($colorIDQuery->get_result());
                    $colorID = $colorIDRow['color_id'] ?? null;
                    
                    // Update course theme color id
                    /* stored procedures */
                    $courseTheme = $connection->query("call upClassColor('" .$_GET['id']. "', '" . $colorID . "')");
                    
                    // Get new color shade
                    $color0 = alter_brightness($_POST['color'], 60.0);
                    $color1 = $_POST['color'];
                    $color2 = alter_brightness($_POST['color'], -60.0);
                        
                    // Update events color
                    /* stored procedures */
                    $updateCol0 = $connection->query("call upEventColor('".$_GET['id']."', '".$color0."',0)");
                    $updateCol1 = $connection->query("call upEventColor('".$_GET['id']."', '".$color1."',1)");
                    $updateCol2 = $connection->query("call upEventColor('".$_GET['id']."', '".$color2."',2)");
                    
                    // Check input
                    if (!$courseTheme OR !$updateCol0 OR !$updateCol1 OR !$updateCol2) {
                        echo ("No data was inserted!: " . mysqli_error());
                        return false;
                    } else {
                        /* If code reaches this point without errors then commit the data in the database */
                        mysqli_commit($connection);
                        // Prompot notification and refresh index.php
                        echo "<script type='text/javascript'>swal('Good job!', 'Course Theme Updated!', 'success');</script>";
                        echo '<meta http-equiv="refresh" content="1; index.php">';
                        die();
                    }
                } catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($connection);
                    throw $exception;
                }
                return true;
            }
			?>

			<!-- Modal with events description -->
			<?php echo modalEvents(); ?>
            </div>
        </div>
    </div>



    <!-- Bootstrap Core JavaScript -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- DataTables JavaScript -->
    <script src="assets/js/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables.bootstrap.js"></script>
    <!-- Listings JavaScript delete options-->
    <script src="assets/js/listings.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="assets/js/metisMenu.min.js"></script>
    <!-- Moment JavaScript -->
    <script src="assets/js/moment.min.js"></script>
    <!-- FullCalendar JavaScript -->
    <script src="assets/js/fullcalendar.min.js"></script>
    <!-- FullCalendar Language JavaScript Selector -->
    <script src='assets/lang/en-gb.js'></script>
    <!-- DateTimePicker JavaScript -->
    <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
    <!-- Datetime picker initialization -->
    <script type="text/javascript">
        $('.form_date').datetimepicker({
            language:  'en',
            weekStart: 1,
            todayBtn:  0,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0
        });
    </script>
    <!-- Plugin Script Initialization for DataTables -->
    <script>
        $(document).ready(function() {
            $('.dataTables-example').dataTable();
        });
    </script>
    <!-- JS array created from database -->
    <?php echo listEvents(); ?>
		
		
</body>

</html>
