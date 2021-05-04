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
    <meta name="description" content="Event Calendar">
    <meta name="author" content="TEAM47">
	<link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon" >

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
	<!-- ColorPicker CSS -->
	<link href="assets/css/bootstrap-colorpicker.css" rel="stylesheet">


</head>

	<body>

		<!-- Navigation -->
		<nav class="navbar navbar-default navbar-fixed-top topnav" role="navigation">
			<div class="container topnav">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand topnav" href="#home"><i class="fa fa-calendar" aria-hidden="true"></i> Purdue Calendar</a>
				</div>
			</div>
			<!-- /.container -->
		</nav>

		<!-- Page Content -->
		<div class="content-section-a">
			
			<!--BEGIN PLUGIN -->
			<div class="container">
				<div class="row">
				    <div class="col-lg-12">
						<div class="panel panel-default dash">
					  <h2 class="sub-header">EDIT COURSE THEME</h2>
						 <form id="editTheme" method="post" enctype="multipart/form-data" class="form-horizontal" name="editTheme">

							<!-- Course Selection input -->
							<div class="form-group">
								<label class="col-md-3 control-label" for="title">
                                    <?php
                                       echo $_GET['id'];
                                    ?>
                                    Course Theme
                                    <?php
                                        $sql = mysqli_query($conection, "select colorname from class natural join color WHERE class_no='".$_GET['id']."'");
                                        $row = mysqli_fetch_assoc($sql);
                                        echo "<span id='show' style='width: 15px; height: 15px; margin:auto; display: inline-block; vertical-align: middle; border-radius: 2px; background: ";
                                        echo $row['colorname'];
                                        echo "'></span> ";
                                    ?>
                                </label>
								<div class="col-md-6">
                                    <script>
                                        setColor = function(val) {
                                            var color = document.getElementById("color").value;
                                            $("#show").css("background", color);
                                        }
                                    </script>
                                    

									<select name='color' id='color' class="form-control input-md" onchange="setColor()">
										<?php
										echo getColor(antiSQLInjection($_GET['id']));
										//prepared statements
										$Colors->execute();
										$query = $Colors->get_result();
										// $query = mysqli_query($conection, "select * from color ORDER BY color_id ASC");
                                        echo "<option value='No course Selected' required>---------</option>";
										while ($row = mysqli_fetch_assoc($query)) {
											echo "
											<option value='".$row['colorname']."'>".$row['colorname']."</option>
											";
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
			if(isset($_POST['editTheme']))
				{
                    // Get course theme color
				mysqli_query($connection, "set session transaction isolation level read committed");//JBZ
                    $colorQuery = mysqli_query($conection, "SELECT color_id FROM color WHERE colorname = '".$_POST['color']."'");
                    $row = mysqli_fetch_assoc($colorQuery);
                    $color = $row['color_id'] ?? null;
					// stored procedures
					$conection->query("call upClassColor('" .$_GET['id']. "', '" . $color . "')");
                    if (!$query) {
                        echo ("No data was inserted!: " . mysqli_error());
                        return false;
                    } else {
                            echo "<script type='text/javascript'>swal('Good job!', 'Event Updated!', 'success');</script>";
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
		<!-- ColorPicker Initialization -->
		<script>
			$(function() {
				$('#cp1').colorpicker();
			});
		
		</script>
		<!-- JS array created from database -->
		<?php echo listEvents(); ?>
		
		
	</body>

</html>
