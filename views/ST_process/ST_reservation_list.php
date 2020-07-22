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

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.php');
global $DB, $PAGE, $OUTPUT, $CFG, $USER;
$PAGE->requires->css('/local/student_registration/assets/CSS/jquery.dataTables.min.css', true);
$PAGE->requires->jquery();
$PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.tabledit.js', true);
$PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.dataTables.min.js', true);
$PAGE->set_title('DHBW Student Registration');
$PAGE->set_heading('DHBW Student Registration');
$PAGE->set_url(new moodle_url('/local/student_registration/views/ST_process/ST_reservation_list.php'));
$PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
$PAGE->navbar->add('Active Processes', new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_ST.php'));
echo $OUTPUT->header();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};
$DSRecordID = $input['DSRecordID']; // demand status record ID 
$processID = $input['ProcessID'];
$user = $USER->id;
$DSRow = $DB->get_records_select('sr_study_places_status', 'id = ?', array($DSRecordID)); //Demand status row 
$field_id =  $DSRow[$DSRecordID]->sr_study_fields_id;
$SFRow = $DB->get_records_select('sr_study_fields', 'id = ?', array($field_id)); // study field row
echo $OUTPUT->heading('Reservation list for ' . $SFRow[$field_id]->study_field_name . '');

echo '<div id="notification"></div>';

$deadline = $DB->get_record_select('sr_process', 'id = ?', array($processID));
$deadline = $deadline->end_date;

$total = (int) $DSRow[$DSRecordID]->study_places_available;
($DSRow[$DSRecordID]->current_demand) ? $reserved = $DSRow[$DSRecordID]->current_demand : $reserved = 0;

if ($total !== 0) {
  echo '<button  class="btn ">
    <p>There is currently <b id="counterSP">' . ($total - $reserved) . '</b> study places available and <b id="timer"></b> left.</p>
  </button >';

  echo '</br>';
  if ($DSRow[$DSRecordID]->current_demand !== $DSRow[$DSRecordID]->study_places_available) {
    echo '
    <form>
      <div class="form-row">
        <div class="col">
          <input type="button" value="Add row" id="addRow9" class="btn btn-danger mb-2 pull-right"/>
        </div>
      </div>
    </form>
    <br/>
    ';
    //echo '<div class="row justify-content-between">';
  } else {
    echo '<div class="alert alert-warning" role="alert">
    <h4 class="alert-heading"><b>Dear User</b></h4>
    <p><b>The maximum capacify for the reservation of study places for ' . $SFRow[$field_id]->study_field_name . ' has been reached</b> You can register for study places on the <button type="button" id ="waitinglist" class="btn btn-outline-info"><b>waiting list</b></button>.</p>
    <hr>
  </div>';
  }



  $table = new html_table();
  $table->id = 'my_table';
  $table->attributes['class'] = 'table table-sm';
  $table->attributes['style'] = "width:100%;";
  $records = $DB->get_records_select("sr_reservation_list", 'sr_company_representative_id= ? AND sr_process_id = ? AND sr_study_fields_id = ? ', array($user, $processID, $field_id));
  $table->head = array('ID', 'First Name', 'Last Name', 'Date of Birth', 'Email', 'Time Created', 'Study Course', 'Contract Status');
  $table->align = array('center', 'center', 'center', 'center', 'center', 'center', 'center', 'center');




  foreach ($records as $record) {

    // $table->data[] = array($record->id, $record->first_name, $record->last_name, $record->date_of_birth, $record->private_email, $record->timecreated, $record->contract_status);
    $row = new html_table_row();

    $row->attributes['RecordID'] = $record->id;
    $cell0 = new html_table_cell();
    $cell0->text = $record->id;

    $cell1 = new html_table_cell();
    $cell1->text = $record->first_name;

    $cell2 = new html_table_cell();
    $cell2->text = $record->last_name;

    $cell3 = new html_table_cell();
    $cell3->text = $record->date_of_birth;


    $cell4 = new html_table_cell();
    $cell4->text = $record->private_email;


    $cell5 = new html_table_cell();
    $cell5->text = $record->timecreated;

    $cell7 = new html_table_cell();

    $STCA = $DB->get_record_select('sr_active_study_course', 'id = ?', array($record->sr_active_study_course_id));
    if ($STCA) {
      $cell7->text = $STCA->study_course_abbreviation;
    } else {
      $cell7->text = '';
    }

    $cell6 = new html_table_cell();
    $cell6->text = $record->contract_status;

    $row->cells  = array($cell0, $cell1, $cell2, $cell3, $cell4, $cell5, $cell7, $cell6);

    $table->data[]  = $row;
  };


  echo html_writer::table($table);






  echo '<div id="processID" type="hidden" hidden>' . $processID . '</div>';
  echo '<div id="DSRecordID" type="hidden" hidden>' . $DSRecordID . '</div>';
} else {
  echo '<div class="alert alert-warning" role="alert">
  <h4 class="alert-heading"><b>Dear User</b></h4>
  <p><b>The DHBW administration has not set the capacity for ' . $SFRow[$field_id]->study_field_name . ' yet</b> This will be update soon!!!</p>
  <hr>
  <p class="mb-0">As soon as the DHBW administration sets the capacity, you will be able to see the available study places. <b>Thanks for your patience.</b></p>
</div>';
}

echo '<div id="" hidden></div><div id="" hidden></div>';

?>


<script>
  (function(jQuery) {
    jQuery.noConflict();

    jQuery('#my_table').Tabledit({
      url: "../../assets/ajax/edit_row.php",
      editButton: true,
      saveButton: true,
      restoreButton: false,

      columns: {
        identifier: [0, 'id', 'hidden'],
        editable: [
          [1, 'First Name', 'input'],
          [2, 'Last Name', 'input'],
          [3, 'Date of Birth', 'date'],
          [4, 'Email', 'input'],
          [6, 'Study Course', 'select', <?PHP
                                        $courses = $DB->get_records_select('sr_active_study_course', 'sr_process_id = ? AND sr_study_fields_id = ? AND closed = 0', array($processID, $field_id));
                                        echo '\'{';
                                        $count = count($courses);
                                        $i = 1;
                                        $coma = ',';
                                        foreach ($courses as $course) {
                                          $count2 = $DB->count_records('sr_reservation_list', array('sr_process_id' => $processID, 'sr_study_fields_id' => $field_id, 'sr_active_study_course_id' => $course->id));
                                          ($i == $count) ? $coma  = '' : $i++;
                                          if ($course->course_capacity > $count2) {
                                            echo '"' . $course->id . '": "' . $course->study_course_abbreviation . ' Capacity: ' .  $course->course_capacity . ' / ' . $count2 . '"' . $coma;
                                          } else {
                                            echo '"' . $course->id . '": "' . $course->study_course_abbreviation . ' Full' . '"' . $coma;
                                          }
                                        }

                                        echo '}\''; ?>],
          [7, 'Contract Status', 'select', '{"Not Signed": "Not Signed", "Signed": "Signed", "Sent": "Sent" , "Withdrown": "Withdrown"}']
        ]
      }
    });


    jQuery("#addRow9").on('click', function() {
      // Getting value
      var processID = jQuery('#processID').html();
      var DSRecordID = jQuery('#DSRecordID').html();
      var counter = jQuery('#counterSP').html();
      var counterMinus = counter--
      if (counter >= 0) {
        jQuery('#counterSP').slideUp(300).delay(1000).fadeIn(400).html(counter);
      } else {
        location.reload();
      }

      jQuery.ajax({
        type: "POST",
        url: "../../assets/ajax/add_new_row.php",
        datatype: 'html',
        data: {
          processID: processID,
          DSRecordID: DSRecordID
        },
        success: function(data) {

          // in case some placeholders were registered this msg appears many times !!!!
          //jQuery("#notification").append('<div id"statusnotification" class="alert alert-success alert-dismissible fade show" role="alert">A new Placeholder was added successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
          jQuery(".alert").delay(4000).slideUp(200, function() {
            jQuery(this).alert('close');
          });
          // Add 'html' data to table
          jQuery('#my_table tbody').html(data);

          // Update Tabledit plugin
          jQuery('#my_table').Tabledit('update');

        },
        error: function() {
          jQuery("#notification").append('<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">Something went wrong! Please contact the system administrator if the error occures again<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        }
      })
    });

    jQuery('#waitinglist').on('click', function() {
      var ProcessID = jQuery('#processID').html();
      var DSRecordID = jQuery('#DSRecordID').html();
      redirectUrl = 'ST_waiting_list.php';
      var form = jQuery('<form action="' + redirectUrl + '" method="post">' +
        '<input type="hidden" name="ProcessID" value="' + ProcessID + '"></input>' +
        '<input type="hidden" name="DSRecordID" value="' + DSRecordID + '"></input>' + '</form>');
      jQuery('body').append(form);
      jQuery(form).submit();
    });

    var countDownDate = new Date("<?php echo $deadline ?>").getTime();
    // Update the count down every 1 second
    var x = setInterval(function() {

      // Get today's date and time
      var now = new Date().getTime();
      // Find the distance between now and the count down date
      var distance = countDownDate - now;

      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);

      // Output the result in an element with id="demo"
      document.getElementById("timer").innerHTML = days + "d " + hours + "h " +
        minutes + "m " + seconds + "s ";

      // If the count down is over, write some text 
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("timer").innerHTML = "EXPIRED";
      }
    }, 1000);

    var dataTable = jQuery("#my_table").DataTable();
    //custom search function
    jQuery.fn.DataTable.ext.search.push((_, __, i) => {
      //get current row

      const currentTr = dataTable.row(i).node();
      //look for all <input>, <select> nodes within 
      //that row and check whether current value of
      //any of those contains searched string
      const inputMatch = jQuery(currentTr)
        .find('select,input')
        .toArray()
        .some(input => jQuery(input).val().toLowerCase().includes(jQuery('#my_table_filter').children().children().val().toLowerCase()));
      //check whether "regular" cells contain the
      //value being searched
      const textMatch = jQuery(currentTr)
        .children()
        .not('td:has("input,select")')
        .toArray()
        .some(td => jQuery(td).text().toLowerCase().includes(jQuery('#my_table_filter').children().children().val().toLowerCase()))
      //make final decision about match
      return inputMatch || textMatch || jQuery('#my_table_filter').children().children().val() == ''
    });
  })(jQuery);
</script>