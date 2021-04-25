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
	<link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon" >

    <title>Purdue Calendar</title>

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
	<!-- ColorPicker CSS -->
	<link href="assets/css/bootstrap-colorpicker.css" rel="stylesheet">


    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

</head>



<body>
        // Nevigate to the eventcalendar
		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
			<div class="container topnav">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>

					</button>
					<a class="navbar-brand topnav" href="#eventcalendar"><i class="fa fa-calendar" aria-hidden="true"></i> Purdue Calendar</a>
				</div>
			</div>
			<!-- /.container -->
		</nav>




		<!-- Page Content -->
		<div id="eventcalendar"></div>
		<div class="content-section-a">

			
			<!--BEGIN PLUGIN -->
			<div class="container">
				<div class="row">
				   <div class="col-lg-12">
				<div class="panel panel-default dash">
					<h2 class="sub-header">
						<i class="fa fa-calendar" aria-hidden="true"></i> Purdue Calendar
					</h2>


					<div class="panel panel-default">
						<div class="panel-heading">

							<!-- Button trigger New Event modal -->
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#event">
							 <i class="fa fa-calendar" aria-hidden="true"></i> New Event
							</button>

							<!-- New Event Creation Modal -->
							<div class="modal fade" id="event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel"> <i class="fa fa-calendar" aria-hidden="true"></i>
												New Event
											</h4>
										</div>
										<div class="modal-body">
											 <!-- New Event Creation Form -->
											<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data" class="form-horizontal" name="novoevento">
												<fieldset>
													<!-- CSRF PROTECTION -->
													<?php $csrf->echoInputField(); ?>

													<!-- Course Selection input -->
													<div class="form-group">
														<label class="col-md-3 control-label" for="course">Course</label>
														<div class="col-md-6">
															<select name='course' class="form-control input-md">
																<?php
																$query = mysqli_query($conection, "select * from class ORDER BY class_no ASC");
																	echo "<option value='No course Selected' required>Select Course</option>";
																while ($row = mysqli_fetch_assoc($query)) {
																	echo "
																	<option value='".$row['class_no']."'>".$row['class_no']."</option>
																	";
																  }
																?>
															</select>
														</div>
													</div>

                                                    <!-- Title Text input-->
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="title">Title</label>
                                                        <div class="col-md-9">
                                                            <textarea class="form-control" rows="1" name="title" id="title"></textarea>
                                                        </div>
                                                    </div>

                                                    <!-- Grade Percentage Selection input-->
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label" for="gradePercent">Grade Percentage</label>
                                                        <div class="col-md-6">
                                                            <select name='gradePercent' class="form-control input-md">
                                                                <?php
                                                                $query = mysqli_query($conection, "select * from type ORDER BY importance ASC");
                                                                echo "<option value='No gradePercent Selected' required>Select Grade Percentage</option>";
                                                                while ($row = mysqli_fetch_assoc($query)) {
                                                                    echo "
                                                                    <option value='".$row['gradePercent']."'>".$row['gradePercent']."</option>
                                                                    ";
                                                                  }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
													
                                                    <!-- Start Date input-->
													<div class="form-group">
														<label class="col-md-3 control-label" for="start">Start Date</label>
														<div class="input-group date form_date col-md-6" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="start" data-link-format="yyyy-mm-dd hh:ii">
															<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" size="16" type="text" value="" readonly>
														</div>
														<input id="start" name="start" type="hidden" value="" required>
													</div>

                                                    <!-- End Date input-->
													<div class="form-group">
														<label class="col-md-3 control-label" for="end">End Date</label>
														<div class="input-group date form_date col-md-6" data-date="" data-date-format="yyyy-mm-dd hh:ii" data-link-field="end" data-link-format="yyyy-mm-dd hh:ii">
															<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" size="16" type="text" value="" readonly>
														</div>
														<input id="end" name="end" type="hidden" value="" required>
													</div>
													
													<!-- Note Text input-->
													<div class="form-group">
														<label class="col-md-3 control-label" for="note">Note</label>
														<div class="col-md-9">
															<textarea class="form-control" rows="5" name="note" id="note"></textarea>
														</div>
													</div>

													<!-- Submit Button -->
													<div class="form-group">
														<label class="col-md-12 control-label" for="singlebutton"></label>
														<div class="col-md-4">
															<input type="submit" name="novoevento" class="btn btn-success" value="New Event" />
														</div>
													</div>

												</fieldset>
											</form>  
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">close</button>
										</div>
									</div>
								</div>
							</div>




                            
                            <!-- Button trigger Edit Course Theme modal -->
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#editTheme">
                                <i class="fa fa-edit" aria-hidden="true"></i> Edit Course Theme
                            </button>

                             <!-- Edit Course Theme Modal -->
                            <div class="modal fade" id="editTheme" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel"> <i class="fa fa-calendar" aria-hidden="true"></i>
                                                Edit Course Theme
                                            </h4>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Modal featuring all courses saved on database -->
                                            <?php echo listAllCourseEdit(); ?>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                            



                        
							<!-- Button trigger Edit Event modal -->
							<button type="button" class="btn btn-info" data-toggle="modal" data-target="#editevent">
								<i class="fa fa-edit" aria-hidden="true"></i> Edit Events
							</button>

							<!-- Edit Event Modal -->
							<div class="modal fade" id="editevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel"> <i class="fa fa-calendar" aria-hidden="true"></i>
												Edit Events
											</h4>
										</div>
										<div class="modal-body">
											<!-- Modal featuring all events saved on database -->
											<?php echo listAllEventsEdit(); ?>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">close</button>
										</div>
									</div>
								</div>
							</div>
							




							<!-- Button trigger Delete Event modal -->
							<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#delevent">
								<i class="fa fa-close" aria-hidden="true"></i> Delete Events
							</button>

							<!-- Delete Event Modal -->
							<div class="modal fade" id="delevent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-label="close"><span aria-hidden="true">&times;</span></button>
											<h4 class="modal-title" id="myModalLabel"> <i class="fa fa-calendar" aria-hidden="true"></i>
												Delete Events
											</h4>
										</div>
										<div class="modal-body">
											<!-- Modal featuring all events saved on database -->
											<?php echo listAllEventsDelete(); ?>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-primary" data-dismiss="modal">close</button>
										</div>
									</div>
								</div>
							</div>



						</div>
						<!-- /.panel-heading -->
						<div class="panel-body">
							<div class="table-responsive">

								<div class="col-lg-12">
									<div id="events"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<?php
                // If user clicked on the new event submit button
                if (!empty($_POST['novoevento'])) {

                    // Fetch variables from form
                            $course = $_POST['course'];
                            $title = trim(preg_replace('/\s+/', ' ',nl2br(str_replace( "'", "´", $_POST['title']))));
                            $start = $_POST['start'];
                            $end = $_POST['end'];
                            $note = trim(preg_replace('/\s+/', ' ',nl2br(str_replace( "'", "´", $_POST['note']))));
                            // Get course theme color
                            $courseColor = mysqli_query($conection, "SELECT colorname FROM class NATURAL JOIN color WHERE class_no = '".$course."'");
                            $rowColor = mysqli_fetch_assoc($courseColor);
                            $courseColor2 = $rowColor['colorname'] ?? null;
                            // Get importance and new color adjust step
                            $gradePercent = $_POST['gradePercent'];
                            $adjustPercent = mysqli_query($conection, "SELECT adjustPercent, importance FROM type WHERE gradePercent = '".$gradePercent."'");
                            $rowPercent = mysqli_fetch_assoc($adjustPercent);
                            $adjustPercent2 = $rowPercent['adjustPercent'] ?? null;
                            $importance = $rowPercent['importance'] ?? null;
                            // Get new color shade
                            $color = alter_brightness($courseColor2, $adjustPercent2);
                    
                    // Check input
                    if (empty($start) || empty($end) || empty($course) || empty($importance)) {
                        echo "<script type='text/javascript'>swal('Ooops...!', 'You need to fill in the date options!', 'error');</script>";
                        echo '<meta http-equiv="refresh" content="1; index.php">';
                        return false;
                    }

                    // Insert informationon the database
                    $sql = mysqli_query($conection,  "INSERT INTO `events` (`class_no`, `title`, `importance`, `color`, `start`, `end`, `note`) VALUES ('".$course."', '".$title."', '".$importance."', '".$color."', '".$start."', '".$end."', '".$note."')");

                    // If information is correctly saved
                    if (!$sql) {
                    echo ("Can't insert into database: " . mysqli_error());
                    return false;
                    } else {
                            echo "<script type='text/javascript'>swal('Good job!', 'New Event Created!', 'success');</script>";
                            echo '<meta http-equiv="refresh" content="1; index.php">';
                            die();
                    }
                    return true;
                }
			?>

			<!-- Modal with events description -->
			<?php echo modalEvents(); ?>
				</div>

			</div>
			<!-- /.container -->

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
		<script src="assets/js/fullcalendar.js"></script>
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
		<!-- ColorPicker Initialization -->
        <?php $sql = mysqli_query($conection, "SELECT colorname FROM color");
        $allColor = [];
        while ($row = mysqli_fetch_array($sql)) {
            array_push($allColor, $row['colorname']);
        }
        ?>
        <script>
            $(function() {
                $('#cp1').colorpicker();
            });
        </script>
         
		<!-- JS array created from database -->
		<?php echo listEvents(); ?>
		
		
	</body>

</html>
