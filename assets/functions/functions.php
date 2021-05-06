<?php
require("config.php");

/* Anti SQL injection*/
function antiSQLInjection($text){
    // Words for search
    $check[1] = chr(34); // simbol "
    $check[2] = chr(39); // simbol '
    $check[3] = chr(92); // simbol /
    $check[4] = chr(96); // simbol `
    $check[5] = "drop table";
    $check[6] = "update";
    $check[7] = "alter table";
    $check[8] = "drop database";
    $check[9] = "drop";
    $check[10] = "select";
    $check[11] = "delete";
    $check[12] = "insert";
    $check[13] = "alter";
    $check[14] = "destroy";
    $check[15] = "table";
    $check[16] = "database";
    $check[17] = "union";
    $check[18] = "TABLE_NAME";
    $check[19] = "1=1";
    $check[20] = 'or 1';
    $check[21] = 'exec';
    $check[22] = 'INFORMATION_SCHEMA';
    $check[23] = 'like';
    $check[24] = 'COLUMNS';
    $check[25] = 'into';
    $check[26] = 'VALUES';

    $y = 1;
    $x = sizeof($check);
    while($y <= $x){
        $target = strpos($text,$check[$y]);
        if($target !== false){
            $text = str_replace($check[$y], "", $text);
        }
    $y++;
    }
    return $text;
}


// List the events on the calendar
function listEvents()
{
    global $connection;
    /* Prepared procedure */
    global $Events;
    $Events->execute();
    $sql = $Events->get_result();
    $row = mysqli_num_rows($sql);
    
    if($row == ''){
        echo "<script>
        $(document).ready(function() {
            $('#events').fullCalendar({
            });
        });
        </script>";
    }
    if($row != '') {
        echo "<script>
        $(document).ready(function() {
            $('#events').fullCalendar({
                lang: 'en',
                defaultDate: '".date("Y-m-d")."',
                editable: true,
                eventLimit: true,
                displayEventTime: false,
                firstDay:0,
                
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listMonth'
                },
                
                //    Modal Box View
                eventClick:  function(event, jsEvent, view) {
                    $('#modalCourse').html(event.course);
                    $('#modalTitle').html(event.title);
                    $('#modalBody').html(event.note);
                    $('#startTime').html(moment(event.start).format('DD-MMM HH:mm'));
                    $('#endTime').html(moment(event.end).format('DD-MMM HH:mm'));
                    $('#fullCalModal').modal();
                     return false;
                },
                
                //    Drop the event
                eventDrop: function(event, delta) {
                   var start = $.fullCalendar.moment(event.start).format();
                   var end = $.fullCalendar.moment(event.end).format();

                   $.ajax({
                   url: 'events_update.php',
                   data: '&start='+ start +'&end='+ end +'&id='+ event.id ,
                   type: 'POST',
                   success: function(json) {
                    swal('Good job!', 'Event Updated!', 'success');
                         setTimeout(function () {
                            location.reload()
                        }, 1000);
                    }
                   });
                },
                
                //    Popover View
                eventRender: function(eventObj, element) {
                    element.find('.fc-day-grid-event .fc-content').append(event.course);
                    element.on('click', e => e.preventDefault());
                    
                    var start = moment(eventObj.start).format('HH:mm');
                    var end = moment(eventObj.end).format('HH:mm');
                      element.popover({
                        html: true,
                        title: eventObj.title + ' ' + start + ' - ' + end,
                        content: eventObj.note,
                        trigger: 'hover',
                        placement: 'bottom',
                        container: 'body',
                      });
                },

                events: [";
                    while ($row = mysqli_fetch_array($sql)) {
                    echo "
                        {
                            id: '".$row['event_id']."',
                            course: '".$row['class_no']."',
                            title: '".$row['class_no']." ".$row['title']."',
                            note: '".$row['note']."',
                            start: '".$row['start']."',
                            end: '".$row['end']."',
                            color: '".$row['color']."',
                            allDay: false
                        },";
                    } ;
            echo "],
            });
        });
        </script>";
    }
}

    
    
    
// Display events information inside a modal box
function modalEvents()
{

    echo "
    <div id='fullCalModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'><span aria-hidden='true'>&times;</span> <span class='sr-only'>close</span></button>
                    <h4><i class='fa fa-calendar' aria-hidden='true'></i> EVENT DETAILS</h4>
                </div>
                
                <div class='modal-body'>
                    <div class='table-responsive'>
                        <div class='col-md-12'>
                            <a id='eventUrl' target='_blank'><h4><i class='fa fa-calendar' aria-hidden='true'></i> <span id='modalCourse'></span></h4></a>
                            <p><i class='fa fa-clock-o' aria-hidden='true'></i> <span id='startTime'></span> to <span id='endTime'></span></p>
                        </div>
                        <div class='col-md-12'>
                            <div id='imageDiv'> </div>
                            <br/>
                            <h4><i class='fa fa-globe'></i> TITLE:</h4>
                             <p id='modalTitle'></p>
                        </div>
                        <div class='col-md-12'>
                            <div id='imageDiv'> </div>
                            <br/>
                            <h4><i class='fa fa-globe'></i> NOTE:</h4>
                             <p id='modalBody'></p>
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-primary' data-dismiss='modal'>CLOSE</button>
                </div>
            </div>
        </div>
    </div>
";
}

// Display all events in delete modal
function listAllEventsDelete()
{
    global $connection;
    /* Prepared procedure */
    global $Events;
    $Events->execute();
    $sql = $Events->get_result();
    $row = mysqli_fetch_assoc($sql);
        
    echo "<table class='table table-striped table-bordered table-hover dataTables-example' id='dataTables-example'>";
    echo "  <thead>
            <tr>
                <th>COURSE</th>
                <th>TITLE</th>
                <th>START DATE</th>
                <th>END DATE</th>
                <th></th>
            </tr>
            </thead>";
    echo "<tr><td>";
    echo $row['class_no'];
    echo "</td><td>";
    echo $row['title'];
    echo "</td><td>";
    echo $row['start'];
    echo "</td><td>";
    echo $row['end'];
    // delete buttom for each record
    echo "</td><td class='r'>
    <a href='javascript:EliminaEvento(". $row['event_id'] . ")'class='btn btn-danger btn-sm' role='button'><i class='fa fa-fw fa-trash'></i> DELETE</a></td>";
    while ($row = mysqli_fetch_array($sql)) {
        // Print out the contents of each row into a table
        echo "<tr><td>";
        echo $row['class_no'];
        echo "</td><td>";
        echo $row['title'];
        echo "</td><td>";
        echo $row['start'];
        echo "</td><td>";
        echo $row['end'];
        // delete buttom for each record
        echo "</td><td class='r'>
        <a href='javascript:EliminaEvento(". $row['event_id'] . ")'class='btn btn-danger btn-sm' role='button'><i class='fa fa-fw fa-trash'></i> DELETE</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}


// Display all course in edit theme modal
function listAllCourseEdit()
{
    global $connection;
    /* Prepared procedure */
    global $Classes;
    $Classes->execute();
    $sql = $Classes->get_result();
    $row = mysqli_fetch_assoc($sql);

    /* Prepared procedure */
    $sqlColor = $connection->prepare("select colorname from color where color_id = '".$row['color_id']."'");
    $sqlColor->execute();
    $rowColor = mysqli_fetch_assoc($sqlColor->get_result());
    
    
    
    echo "<table class='table table-striped table-bordered table-hover dataTables-example' id='dataTables-example'>";
    echo "  <thead>
            <tr>
                <th>COURSE</th>
                <th>COLOR</th>
                <th></th>
            </tr>
            </thead>";
    echo "<tr><td>";
    echo $row['class_no'];
    echo "</td><td>";
    // print theme color for each record
    echo "<span style='width: 18px; height: 18px; margin:auto; display: inline-block; vertical-align: middle; border-radius: 1px; background: ";
    echo $rowColor['colorname'];
    echo "'></span> ";
    echo str_repeat("&nbsp;", 2);
    echo $rowColor['colorname'];
    // edit buttom for each record
    echo "</td><td class='r'>
    <a href='course_edit.php?id=". $row['class_no'] . "' class='btn btn-primary btn-sm' role='button'><i class='fa fa-fw fa-edit'></i> EDIT</a></td>";
    echo "</tr>";
    while ($row = mysqli_fetch_array($sql)) {
        // Print out the contents of each row into a table
        /* Prepared procedure */
        $sqlColor = $connection->prepare("select colorname from color where color_id = '".$row['color_id']."'");
        $sqlColor->execute();
        $rowColor = mysqli_fetch_assoc($sqlColor->get_result());
        echo "<tr><td>";
        echo $row['class_no'];
        echo "</td><td>";
        // print theme color for each record
        echo "<span style='width: 18px; height: 18px; margin:auto; display: inline-block; vertical-align: middle; border-radius: 1px; background: ";
        echo $rowColor['colorname'];
        echo "'></span> ";
        echo str_repeat("&nbsp;", 2);
        echo $rowColor['colorname'];
        // edit buttom for each record
        echo "</td><td class='r'>
        <a href='course_edit.php?id=". $row['class_no'] . "' class='btn btn-primary btn-sm' role='button'><i class='fa fa-fw fa-edit'></i> EDIT</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
    
    
    
    
    
// Display all events in event edit modal
function listAllEventsEdit()
{
    global $connection;
    /* Prepared procedure */
    global $Events;
    $Events->execute();
    $sql = $Events->get_result();
    $row = mysqli_fetch_assoc($sql);
    
    echo "<table class='table table-striped table-bordered table-hover dataTables-example' id='dataTables-example'>";
    echo "  <thead>
            <tr>
                <th>COURSE</th>
                <th>TITLE</th>
                <th>START DATE</th>
                <th>END DATE</th>
                <th></th>
            </tr>
            </thead>";
    echo "<tr><td>";
    echo $row['class_no'];
    echo "</td><td>";
    echo $row['title'];
    echo "</td><td>";
    echo $row['start'];
    echo "</td><td>";
    echo $row['end'];
    // edit buttom for each record
    echo "</td><td class='r'>
    <a href='events_edit.php?id=". $row['event_id'] . "' class='btn btn-primary btn-sm' role='button'><i class='fa fa-fw fa-edit'></i> EDIT</a></td>";
    while ($row = mysqli_fetch_array($sql)) {
        // Print out the contents of each row into a table
        echo "<tr><td>";
        echo $row['class_no'];
        echo "</td><td>";
        echo $row['title'];
        echo "</td><td>";
        echo $row['start'];
        echo "</td><td>";
        echo $row['end'];
        // edit buttom for each record
        echo "</td><td class='r'>
        <a href='events_edit.php?id=". $row['event_id'] . "' class='btn btn-primary btn-sm' role='button'><i class='fa fa-fw fa-edit'></i> EDIT</a></td>";
        echo "</tr>";
    }
    echo "</table>";
}
    
    


function getCourse($id)
{
    global $connection;
    /* Prepared procedure */
    $query = $connection->prepare("select class_no from events WHERE event_id='".$id."'");
    $query->execute();
    $row = mysqli_fetch_assoc($query->get_result());
    
    echo "<option value='".$row['class_no']."' required>".$row['class_no']."</option>";
}

    
function getColor($id)
{
    global $connection;
    /* Prepared procedure */
    $query = $connection->prepare("select colorname from class natural join color WHERE class_no='".$id."'");
    $query->execute();
    $row = mysqli_fetch_assoc($query->get_result());
        
    echo "<option value='".$row['colorname']."' required>".$row['colorname']."</option>";
    
}
    

function getGradePercent($id)
{
    global $connection;
    /* Prepared procedure */
    $query = $connection->prepare("select gradePercent from events NATURAL JOIN type WHERE event_id='".$id."'");
    $query->execute();
    $row = mysqli_fetch_assoc($query->get_result());
        
    echo "<option value='".$row['gradePercent']."' required>".$row['gradePercent']."</option>";
}

    

    
// Edit event Information (used in events_edit.php)
function editEvent($event_id)
{
    global $connection;
    /* Prepared procedure */
    $query = $connection->prepare("select * from events WHERE event_id='".$event_id."'");
    $query->execute();
    $row = mysqli_fetch_assoc($query->get_result());

    echo "
                <fieldset>

                    <!-- Start Date display -->
                    <div class='form-group'>
                        <label class='col-md-3 control-label' for='start'>Start Date</label>
                        <div class='input-group date form_date col-md-3' data-date='' data-date-format='yyyy-mm-dd hh:ii' data-link-field='start' data-link-format='yyyy-mm-dd hh:ii'>
                            <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span><input class='form-control' size='16' type='text' value='".$row['start']."' readonly>
                        </div>
                        <input id='start' name='start' type='hidden' value='".$row['start']."' required>

                    </div>
                            
                    <!-- End Date display -->
                    <div class='form-group'>
                        <label class='col-md-3 control-label' for='end'>End Date</label>
                        <div class='input-group date form_date col-md-3' data-date='' data-date-format='yyyy-mm-dd hh:ii' data-link-field='end' data-link-format='yyyy-mm-dd hh:ii'>
                            <span class='input-group-addon'><span class='glyphicon glyphicon-calendar'></span></span><input class='form-control' size='16' type='text' value='".$row['end']."' readonly>
                        </div>
                        <input id='end' name='end' type='hidden' value='".$row['end']."' required>

                    </div>
                    

                    <!-- Note Text Display -->
                    <div class='form-group'>
                        <label class='col-md-3 control-label' for='note'>Note</label>
                        <div class='col-md-6'>
                            <textarea class='form-control' rows='5' name='note' id='note'>".$row['note']."</textarea>
                        </div>
                    </div>
    
                ";

}



    


// Generate new color shade based on importance
function alter_brightness($colourstr, $steps) {
  $colourstr = str_replace('#','',$colourstr);
  $rhex = substr($colourstr,0,2);
  $ghex = substr($colourstr,2,2);
  $bhex = substr($colourstr,4,2);

  $r = hexdec($rhex);
  $g = hexdec($ghex);
  $b = hexdec($bhex);

  $r = max(0,min(255,$r + $steps));
  $g = max(0,min(255,$g + $steps));
  $b = max(0,min(255,$b + $steps));

  return '#'.dechex($r).dechex($g).dechex($b);
}
    

