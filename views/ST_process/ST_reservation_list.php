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
    $processID = $input['ID'];
    $program_name = $input['program_name'];
    

  global $DB, $PAGE, $OUTPUT, $CFG , $USER;
  require_once('../../../../config.php');
  $notificationtype = \core\output\notification::NOTIFY_INFO;
  $PAGE->set_heading('DHBW Student Registration');
  $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
  $PAGE->set_url(new moodle_url('/local/student_registration/views/ST_process/ST_reservation_list.php'));
  echo $OUTPUT->header();
  $user = $USER->id;

  echo $OUTPUT->heading('Reservation list for '. $program_name .'');
  //echo $OUTPUT->notification('You are now in reservation list', $notificationtype);


  echo '';

  echo '<div class="row">
    <div class="col-md-12 m-b-20">
      <input type="button" value="Add row" id="addRow9" class="btn btn-danger pull-right">
    </div>
  </div>';
  
  $table = new html_table();
  $table->id= 'my_table'; 
  $table->attributes['class'] = 'table table-sm ';
  $records = $DB->get_records_select("sr_reservation_list",'sr_company_representative_id= ? AND sr_process_id = ?' ,array($user , $processID) );

      $table->head = array('ID','first_name', 'last_name','date_of_birth');
      $table->align = array('left', 'left', 'left','left');
      



      foreach($records as $record){

        $table->data[] = array($record->id , $record->first_name ,$record->last_name ,$record->date_of_birth);

      };


  echo html_writer::table($table);




  echo $OUTPUT->footer();

  echo '<div id="process_id" type="hidden" hidden>'.$processID.'</div>';

  ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="../../assets/JavaScript/jquery.tabledit.js"></script> 

<script>

$('#my_table').Tabledit({
  url: "../../assets/ajax/edit_row.php",
        editButton: true,
        saveButton: true,
        restoreButton: false,
        
columns: {
  identifier: [0, 'id'],                    
  editable: [[1, 'first_name'], [2, 'last_name'], [3, 'date_of_birth']]
}
});


     
    $("#addRow9").on('click', function () {
      // Getting value
      var ID = $('#process_id').html();
     
      $.ajax({
        type: "POST",
        url: "../../assets/ajax/add_new_row.php",
        datatype: 'html',
        data: {
          ID: ID
        },
        success: function (data) {
     
          // Add 'html' data to table
          $('#my_table tbody').html(data);
     
          // Update Tabledit plugin
          $('#my_table').Tabledit('update');
     
        },
        error: function () {
     
        }
      })
    });

  </script>