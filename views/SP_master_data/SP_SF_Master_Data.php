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

/* This document should contain a view for managing master data of study fields
  */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
global $DB, $PAGE, $OUTPUT, $CFG, $USER;
require_login();
$user = $USER->id;
$context = context_system::instance();
require_login();
if (has_capability('local/student_registration:manage', $context)) {
  $ProgramID = $input['ProgramID'];
  $ProgramName = $input['ProgramName'];

  $PAGE->set_heading('DHBW Student Registration');
  $PAGE->requires->jquery();
  $PAGE->requires->css('/local/student_registration/assets/CSS/jquery.dataTables.min.css', true);
  $PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.tabledit.js', true);
  $PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.dataTables.min.js', true);
  $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
  $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
  $PAGE->navbar->add('Study Program Management', new moodle_url('/local/student_registration/views/SP_master_data/SP_Master_Data.php'));


  echo $OUTPUT->header();
  echo $OUTPUT->heading('Study Fields for ' . $ProgramName);


  echo '<div class="row">
        <div class="col-12 m-b-20">
          <input type="button" value="Add row" id="addRow9" class="btn btn-danger pull-right">
        </div>
      </div>';
  echo '</br>';
  $table = new html_table();
  $table->id = 'my_table';
  $table->attributes['class'] = 'table table-sm ';
  $records = $DB->get_records_select("sr_study_fields", 'sr_study_programs_id = ? AND old = 0', array($ProgramID));

  $table->head = array('ID', 'Study Field', 'Description');
  $table->align = array('left', 'left', 'left');


  foreach ($records as $record) {

    $row = new html_table_row();
    $row->attributes['courseID'] = $record->id;

    $cell1 = new html_table_cell();
    $cell1->text = $record->id;

    $cell2 = new html_table_cell();
    $cell2->text = $record->study_field_name;

    $cell3 = new html_table_cell();

    $cell3->text = $record->description;


    $row->cells  = array($cell1, $cell2, $cell3);


    $table->data[]  = $row;
  };


  echo html_writer::table($table);

  echo '</br>';

  echo '<form action="../../assets/PHPFunctions/sp_closure.php" method="post">
  <div class="form-group">
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="true" name="closecourse" id="closecourse">
  <label class="form-check-label" for="closecourse">
    Close this Program
  </label>
</div>
</div>
<input  type="submit" class="btn btn-danger" value="Save">
<input type="number" class="hidden" name="ProgramID" id="ProgramID" value="' . $ProgramID . '">
</form>';

  echo '<div id="program_id" hidden>' . $ProgramID . '</div>';
} else {
  redirect($CFG->wwwroot);
};


?>

<script>
  (function(jQuery) {
    // You pass-in jQuery and then alias it with the jQuery-sign
    // So your internal code doesn't change


    jQuery('#my_table').Tabledit({
      url: "../../assets/ajax/edit_study_field.php",
      editButton: true,
      saveButton: true,
      restoreButton: false,

      columns: {
        identifier: [0, 'ID'],
        editable: [
          [1, 'Study Field'],
          [2, 'Description']
        ]
      }
    });


    jQuery("#addRow9").on('click', function() {
      // Getting value
      var ProgramID = jQuery('#program_id').html();

      jQuery.ajax({
        type: "POST",
        url: "../../assets/ajax/add_new_study_field.php",
        datatype: 'html',
        data: {
          ProgramID: ProgramID
        },
        success: function(data) {
          // Add 'html' data to table
          jQuery('#my_table tbody').html(data);
          // Update Tabledit plugin
          jQuery("#my_table").Tabledit('update');


        },
        error: function() {

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