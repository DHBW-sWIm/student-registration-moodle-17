<?php


// This file is part of the Local Analytics plugin for Moodle
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Information about the version of the plugin.
 *
 * @package   local_student_registration
 * @copyright 2020 "DHBW Mannheim" 
 * @license   https://moodle.dhbw-mannheim.de/ 
 */


 /* This document should contain the Seat reservation view for Company representatives (CR) 
  * Here the company representative should be able to add records (each record is a reserved seat)
  * to the database based on the available seats for a certain ST process 
  * CR must be able to adjust the records and enter student names and contract status 
  * idea: table for adding records and editing them (AJAX and JQuery) live editable tables
  * in case all seats were reserved, CR must be prevented from adding new records and should 
  * be redirected to waiting list view 
  */


  //if (!has_capability('moodle/site:config', context_system::instance())) {
    //header('HTTP/1.1 403 Forbidden');
    //die();}
    if ($_SERVER['REQUEST_METHOD']=='POST') {
      $input = filter_input_array(INPUT_POST);
    } else {
      $input = filter_input_array(INPUT_GET);
    };
    $DSRecordID = $input['DSRecordID']; // demand status record ID 

    $processID = $input['ProcessID'];

  global $DB, $PAGE, $OUTPUT, $CFG , $USER;
  require_once('../../../../config.php');
  $PAGE->requires->jquery();
  $notificationtype = \core\output\notification::NOTIFY_INFO;
  $PAGE->set_heading('DHBW Student Registration');
  $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
  $PAGE->navbar->add('Active Processes',new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_ST.php'));
  $PAGE->set_url(new moodle_url('/local/student_registration/views/ST_process/ST_reservation_list.php'));
  echo $OUTPUT->header();
  $user = $USER->id;

  $DSRow = $DB->get_records_select('sr_study_places_status', 'id = ?', array($DSRecordID));//Demand status row 
  $field_id =  $DSRow[$DSRecordID]->sr_study_fields_id;
  $SFRow = $DB->get_records_select('sr_study_fields', 'id = ?', array($field_id));// study field row
  
  echo $OUTPUT->heading('Reservation list for '. $SFRow[$field_id]->study_field_name .'');
  //echo $OUTPUT->notification('You are now in reservation list', $notificationtype);
  echo '<div id="notification"></div>';

  $total = (int)$DSRow[$DSRecordID]->study_places_available;
  ($DSRow[$DSRecordID]->current_demand) ? $reserved = $DSRow[$DSRecordID]->current_demand : $reserved = 0 ;

  if($total !== 0){
    echo '<nav class="navbar navbar-dark">
    <p>There is currently <b id="counterSP">'.($total - $reserved).'</b> study places available.</p>
  </nav>';

  if($DSRow[$DSRecordID]->current_demand !== $DSRow[$DSRecordID]->study_places_available){

  

  echo '<div class="row">
  <div class="col-md-12 m-b-20">
    <input type="button" value="Add row" id="addRow9" class="btn btn-danger pull-right">
  </div>
  </div>';


  }else {
    echo '<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading"><b>Dear User</b></h4>
    <p><b>The maximum capacify for the reservation of study places for '. $SFRow[$field_id]->study_field_name.' has been reached</b> You can register for study places on the <button type="button" id ="waitinglist" class="btn btn-outline-info"><b>waiting list</b></button>.</p>
    <hr>
  </div>';
  }




  echo '<input id="myInput" type="text" />';
  
  $table = new html_table();
  $table->id= 'my_table'; 
  $table->attributes['class'] = 'table table-sm ';

  $records = $DB->get_records_select("sr_reservation_list",'sr_company_representative_id= ? AND sr_process_id = ? AND sr_study_fields_id = ? ' ,array($user , $processID , $field_id) );

      $table->head = array('ID','First Name', 'Last Name','Date of Birth', 'Email' ,'Contract Status');
      $table->align = array('left', 'left', 'left','left','left');
      



      foreach($records as $record){

        $table->data[] = array($record->id , $record->first_name ,$record->last_name ,$record->date_of_birth,$record->private_email  , $record->contract_status);

      };


  echo html_writer::table($table);




  echo $OUTPUT->footer();

  echo '<div id="processID" type="hidden" hidden>'.$processID.'</div>';
  echo '<div id="DSRecordID" type="hidden" hidden>'.$DSRecordID.'</div>';


}else {
  echo '<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading"><b>Dear User</b></h4>
  <p><b>The DHBW administration has not set the capacity for '. $SFRow[$field_id]->study_field_name.' yet</b> This will be update soon!!!</p>
  <hr>
  <p class="mb-0">As soon as the DHBW administration sets the capacity, you will be able to see the available study places. <b>Thanks for your patience.</b></p>
</div>';
}


echo '<div id="" hidden></div><div id="" hidden></div>';

  ?>


<script src="../../assets/JavaScript/jquery.tabledit.js"></script> 

<script>

$('#my_table').Tabledit({
  url: "../../assets/ajax/edit_row.php",
        editButton: true,
        saveButton: true,
        restoreButton: false,
        
columns: {
  identifier: [0, 'id' ,'hidden'],                    
  editable: [[1, 'First Name', 'input'],  [2 ,'Last Name' ,'input'], [3, 'Date of Birth' , 'date'], [4, 'Email' , 'input'], [ 5, 'Contract Status' , 'select' , '{"Not Signed": "Not Signed", "Signed": "Signed", "Sent": "Sent" , "Withdrown": "Withdrown"}']]
}
});

function filterTable(event) {
    var filter = event.target.value.toUpperCase();
    var rows = document.querySelector("#my_table tbody").rows;

    for (var i = 0; i < rows.length; i++) {
        var secondCol = rows[i].cells[1].textContent.toUpperCase();
        var thirdCol = rows[i].cells[2].textContent.toUpperCase();
        if (secondCol.indexOf(filter) > -1 || thirdCol.indexOf(filter) > -1) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }      
    }
} 

document.querySelector('#myInput').addEventListener('keyup', filterTable, false);


    $("#addRow9").on('click', function () {
      // Getting value
      var processID = $('#processID').html();
      var DSRecordID = $('#DSRecordID').html();
      var counter = $('#counterSP').html();
      var counterMinus = counter--
      if (counter >= 0) {
        $('#counterSP').slideUp( 300 ).delay( 1000 ).fadeIn( 400 ).html(counter);
      }

      $.ajax({
        type: "POST",
        url: "../../assets/ajax/add_new_row.php",
        datatype: 'html',
        data: {
          processID: processID,
          DSRecordID : DSRecordID
        },
        success: function (data) {
     
          // Add 'html' data to table
          $('#my_table tbody').html(data);
          
          // Update Tabledit plugin
          $('#my_table').Tabledit('update');
     
        },
        error: function () {
          $( "#notification" ).append( '<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">Something went wrong!!!!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' );
        }
      })
    });

    $('#waitinglist').on('click' , function (){ 
      var ProcessID = $('#processID').html();
      var DSRecordID = $('#DSRecordID').html();
      redirectUrl = 'ST_waiting_list.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                '<input type="hidden" name="ProcessID" value="' + ProcessID + '"></input>' +
                '<input type="hidden" name="DSRecordID" value="' + DSRecordID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();
    });
  </script>