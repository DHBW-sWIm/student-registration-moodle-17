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

require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.php');
global $DB, $PAGE, $OUTPUT, $CFG, $USER;
require_login();
if (!has_capability('local/student_registration:cr', context_system::instance())) {
  header('HTTP/1.1 403 Forbidden');
  die();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};
$DSRecordID = $input['DSRecordID']; // demand status record ID 
$ProcessID = $input['ProcessID'];
$PAGE->set_heading('DHBW Student Registration');
$PAGE->requires->jquery();
$PAGE->requires->css('/local/student_registration/assets/CSS/jquery.dataTables.min.css', true);
$PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.tabledit.js', true);
$PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.dataTables.min.js', true);
$PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
$PAGE->navbar->add('Active Processes', new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_ST.php'));
$PAGE->set_url(new moodle_url('/local/student_registration/views/ST_process/ST_waiting_list.php'));
echo $OUTPUT->header();
$user = $USER->id;
$DSRow = $DB->get_records_select('sr_study_places_status', 'id = ?', array($DSRecordID)); //Demand status row 
$field_id =  $DSRow[$DSRecordID]->sr_study_fields_id;
$SFRow = $DB->get_records_select('sr_study_fields', 'id = ?', array($field_id)); // study field row

echo $OUTPUT->heading('Waiting list for ' . $SFRow[$field_id]->study_field_name . '');

echo '<div id="notification"></div>';

echo '<div class="row">
  <div class="col-md-12 m-b-20">
    <input type="button" value="Add row" id="addRow9" class="btn btn-danger pull-right">
  </div>
  </div>';
echo '</br>';
$table = new html_table();
$table->id = 'my_table';
$table->attributes['class'] = 'table table-sm ';

$records = $DB->get_records_select("sr_waiting_list", 'sr_company_representative_id= ? AND sr_process_id = ? AND sr_study_fields_id = ? AND moved_to_rl = 0 ', array($user, $ProcessID, $field_id));

$table->head = array('ID', 'First Name', 'Last Name', 'Date of Birth', 'Email');
$table->align = array('center', 'center', 'center', 'center', 'center');




foreach ($records as $record) {

  $table->data[] = array($record->id, $record->first_name, $record->last_name, $record->date_of_birth, $record->private_email);
};


echo html_writer::table($table);



echo '<div id="ProcessID" type="hidden" hidden>' . $ProcessID . '</div>';
echo '<div id="DSRecordID" type="hidden" hidden>' . $DSRecordID . '</div>';



?>

<script>
  (function(jQuery) {
    jQuery.noConflict();

    jQuery('#my_table').Tabledit({
      url: "../../assets/ajax/edit_row_wl.php",
      editButton: true,
      saveButton: true,
      restoreButton: false,

      columns: {
        identifier: [0, 'id', 'hidden'],
        editable: [
          [1, 'First Name', 'input'],
          [2, 'Last Name', 'input'],
          [3, 'Date of Birth', 'date'],
          [4, 'Email', 'input']
        ]
      }
    });

    jQuery("#addRow9").on('click', function() {
      // Getting value
      var ProcessID = jQuery('#ProcessID').html();
      var DSRecordID = jQuery('#DSRecordID').html();
      var counter = jQuery('#counterSP').html();
      var counterMinus = counter--
      if (counter >= 0) {
        jQuery('#counterSP').slideUp(300).delay(1000).fadeIn(400).html(counter);
      }

      jQuery.ajax({
        type: "POST",
        url: "../../assets/ajax/add_new_row_wl.php",
        datatype: 'html',
        data: {
          ProcessID: ProcessID,
          DSRecordID: DSRecordID
        },
        success: function(data) {

          // Add 'html' data to table
          jQuery('#my_table tbody').html(data);

          // Update Tabledit plugin
          jQuery('#my_table').Tabledit('update');

        },
        error: function() {
          jQuery("#notification").append('<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">Something went wrong!!!!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        }
      })
    });

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

<?PHP
echo $OUTPUT->footer();
?>