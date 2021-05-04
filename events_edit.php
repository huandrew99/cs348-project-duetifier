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
                    <h2 class="sub-header">EDIT EVENT</h2>

                    <!-- Event Edit Form -->
                    <form id="editEvent" method="post" enctype="multipart/form-data" class="form-horizontal" name="editEvent">

                        <!-- 1. Course Selection input -->
                        <div class="form-group">
                        <label class="col-md-3 control-label" for="title">Course</label>
                            <div class="col-md-6">
                            <select name='course' class="form-control input-md">
                                <?php
                                    echo getCourse(antiSQLInjection($_GET['id']));
                                    /* Prepared statements */
                                    $Classes->execute();
                                    $query = $Classes->get_result();
                                    echo "<option value='No course Selected' required>---------</option>";
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        echo "<option value='".$row['class_no']."'>".$row['class_no']."</option>";
                                    }
                                ?>
                            </select>
                            </div>
                        </div>

                                                              
                        <!-- 2. Title Text input-->
                        <div class='form-group'>
                        <label class='col-md-3 control-label' for='title'>Title</label>
                            <div class='col-md-6'>
                                <?php
                                    /* Prepared procedure */
                                    $query = $connection->prepare("select * from events WHERE event_id='".$_GET['id']."'");
                                    $query->execute();
                                    $row = mysqli_fetch_assoc($query->get_result());
                                    echo "<textarea class='form-control' rows='1' name='title' id='title'>".$row['title']."</textarea>";
                                ?>
                            </div>
                        </div>


                        <!-- 3. Grade Percentage Selection input-->
                        <div class="form-group">
                        <label class="col-md-3 control-label" for="gradePercent">Grade Percentage</label>
                            <div class="col-md-6">
                            <select name='gradePercent' class="form-control input-md">
                                <?php
                                    echo getGradePercent(antiSQLInjection($_GET['id']));
                                    /* Prepared statements */
                                    $Types->execute();
                                    $query = $Types->get_result();
                                    echo "<option value='No gradePercent Selected' required>---------</option>";
                                    while ($row = mysqli_fetch_assoc($query)) {
                                        echo "<option value='".$row['gradePercent']."'>".$row['gradePercent']."</option>";
                                    }
                                ?>
                            </select>
                            </div>
                        </div>
                                            

                        <!-- 4. Get other inputs and display -->
                        <?php echo editEvent(antiSQLInjection($_GET['id'])); ?>
                        <?php $csrf->echoInputField(); ?>
							
                        <!-- Submit Button -->
                        <div class='form-group'>
                            <label class='col-md-8 control-label' for='singlebutton'></label>
                            <div class='col-md-4'>
                                <input type='submit' name='editEvent' class='btn btn-success' value='Edit Event' />
                            </div>
                        </div>

                    </fieldset>
                    </form>
                    </div>
                </div>


			<?php
            // Update event information in database
			if(isset($_POST['editEvent'])) {
                /* Set transaction level */
                $connection->query("SET TRANSACTION ISOLATION LEVEL READ COMMITTED");
                /* Start transaction */
                mysqli_begin_transaction($connection);
                try {
                    // Get course theme color
                    /* Prepared procedure */
                    $themeQuery = $connection->prepare("SELECT colorname FROM class NATURAL JOIN color WHERE class_no = '".$_POST['course']."'");
                    $themeQuery->execute();
                    $themeRow = mysqli_fetch_assoc($themeQuery->get_result());
                    $themeColor = $themeRow['colorname'] ?? null;
                    // Get importance and new color adjust step
                    /* Prepared procedure */
                    $adjustQuery = $connection->prepare("select adjustPercent, importance from type where gradePercent = '".$_POST['gradePercent']."'");
                    $adjustQuery->execute();
                    $row = mysqli_fetch_assoc($adjustQuery->get_result());
                    $adjustPercent = $row['adjustPercent'] ?? null;
                    
                    // Get new color shade
                    $color = alter_brightness($themeColor, $adjustPercent);
                    // Update event information in database
                    /* Stored procedure */
                    $update = $connection->query("call upEvent('".$_GET['id']."', '".$_POST['course']."', '".$_POST['title']."', '".$row['importance']."', '".$color."', '".$_POST['start']."', '".$_POST['end']."', '" . trim(preg_replace('/\s+/', ' ',nl2br(str_replace( "'", "´", $_POST['note'])))) . "')");
                    
                    // Check input
                    if (!($update)) {
                        echo ("No data was inserted!: " . mysqli_error());
                        return false;
                    } else {
                        /* If code reaches this point without errors then commit the data in the database */
                        // Prompot notification and refresh index.php
                        mysqli_commit($connection);
                        echo "<script type='text/javascript'>swal('Good job!', 'Event Updated!', 'success');</script>";
                        echo '<meta http-equiv="refresh" content="1; index.php">';
                        die();
                    }
                return true;
                } catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($connection);
                    throw $exception;
                }
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
    <!-- ColorPicker JavaScript -->
    <script src="assets/js/bootstrap-colorpicker.js"></script>
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
