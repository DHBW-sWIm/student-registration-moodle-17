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


 /* This document should contain the waiting list view for CR 
  * This view should be only accessable when all seats for a ST process were reserved 
  * records should be either automatically transfered to a reservation list or 
  * manually. This configuration should be included in the Settings tile in the main menu 
  * for manual adjustment of waiting list, the manager or secretary should see 
  * students at the waiting list and choose which records to transfer to reservation 
  * list (just in case a CR deleted a reserved Seat)
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

      $ProcessID = $input['ProcessID'];

  global $DB, $PAGE, $OUTPUT, $CFG , $USER;
  require_once('../../../../config.php');
  $notificationtype = \core\output\notification::NOTIFY_INFO;
  $PAGE->set_heading('DHBW Student Registration');
  $PAGE->requires->jquery();

  $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
  $PAGE->navbar->add('Active Processes',new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_ST.php'));
  $PAGE->set_url(new moodle_url('/local/student_registration/views/ST_process/ST_waiting_list.php'));
  echo $OUTPUT->header();
  echo $OUTPUT->notification('You are now in waiting list', $notificationtype);

  $user = $USER->id;
  $DSRow = $DB->get_records_select('sr_study_places_status', 'id = ?', array($DSRecordID));//Demand status row 
  $field_id =  $DSRow[$DSRecordID]->sr_study_fields_id;
  $SFRow = $DB->get_records_select('sr_study_fields', 'id = ?', array($field_id));// study field row
  
  echo $OUTPUT->heading('Waiting list for '. $SFRow[$field_id]->study_field_name .'');
  
  echo '<div id="notification"></div>';

  echo '<div class="row">
  <div class="col-md-12 m-b-20">
    <input type="button" value="Add row" id="addRow9" class="btn btn-danger pull-right">
  </div>
  </div>';

  echo '<input id="myInput" type="text" />';
  $table = new html_table();
  $table->id= 'my_table'; 
  $table->attributes['class'] = 'table table-sm ';

  $records = $DB->get_records_select("sr_waiting_list",'sr_company_representative_id= ? AND sr_process_id = ? AND sr_study_fields_id = ? AND moved_to_rl = 0 ' ,array($user , $ProcessID , $field_id) );

      $table->head = array('ID','First Name', 'Last Name','Date of Birth', 'Email');
      $table->align = array('left', 'left', 'left','left');
      



      foreach($records as $record){

        $table->data[] = array($record->id , $record->first_name ,$record->last_name ,$record->date_of_birth,$record->private_email );

      };


  echo html_writer::table($table);




  echo $OUTPUT->footer();


  echo '<div id="ProcessID" type="hidden" hidden>'.$ProcessID.'</div>';
  echo '<div id="DSRecordID" type="hidden" hidden>'.$DSRecordID.'</div>';



  ?>
  
<script src="../../assets/JavaScript/jquery.tabledit.js"></script> 
<script>
$('#my_table').Tabledit({
  url: "../../assets/ajax/edit_row_wl.php",
        editButton: true,
        saveButton: true,
        restoreButton: false,
        
columns: {
  identifier: [0, 'id' ,'hidden'],                    
  editable: [[1, 'First Name', 'input'],  [2 ,'Last Name' ,'input'], [3, 'Date of Birth' , 'date'], [4, 'Email' , 'input']]
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
      var ProcessID = $('#ProcessID').html();
      var DSRecordID = $('#DSRecordID').html();
      var counter = $('#counterSP').html();
      var counterMinus = counter--
      if (counter >= 0) {
        $('#counterSP').slideUp( 300 ).delay( 1000 ).fadeIn( 400 ).html(counter);
      }

      $.ajax({
        type: "POST",
        url: "../../assets/ajax/add_new_row_wl.php",
        datatype: 'html',
        data: {
          ProcessID: ProcessID,
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


</script> 