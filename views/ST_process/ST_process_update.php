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


/* This document should contain the Student registration process creation view 
  * Here the manager should be able to see an create and later manage (update, or close) a 
  * certain current active ST process
  * Here the manager sets deadlines for demand submission as well as Seats reservation  
  * by company representitives 
  */




require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

global $DB, $PAGE, $OUTPUT, $CFG, $USER;
if (!has_capability('local/student_registration:manage', context_system::instance())) {
  header('HTTP/1.1 403 Forbidden');
  die();
}
require_login();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

$id = $input['ID'];


$PAGE->set_heading('Student registration process creation');

$PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
$PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
$PAGE->navbar->add('Student Registration process overview', new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));
$PAGE->requires->jQuery();
echo $OUTPUT->header();

$programs = $DB->get_records_select('sr_study_programs', 'old = ?', array(0), '',   'distinct study_program_name');
$record = $DB->get_records_select('sr_process', 'id = ?', array($id));

$array = json_decode(json_encode($record), true);
$defaultdate = $array[$id]['start_date'];
$fafda =  str_replace(' ', '', $array[$id]['start_date_for_b']);

echo '<form>

<div class="form-row">
<div class="form-group col-md-3">
<label for="studyprogram" value="' . $array[$id]['program_name'] . '"><b>Study Program</b></label>
<select id="studyprogram" name="studyprogram" class="form-control">
  <option selected>Choose...</option>
  ';
foreach ($programs as $record => $val) {
  if ($record === $array[$id]['program_name']) {
    echo '<option selected>' . $record . '</option>';
  } else echo '<option>' . $record . '</option>';
}
echo '
  
</select>
</div>
</div>

<div class="form-row">
<div class="form-group col-md-3">
<label for="semester"><b>Semester</b></label>
<select id="semester" name="semester" class="form-control">
  <option >Choose...</option>
  ';
if ($array[$id]['semester'] === 'Winter Semester') {
  echo '<option selected>Winter Semester</option>';
  echo '<option>Summer Semester</option>';
} else {
  echo '<option>Winter Semester</option>';
  echo '<option selected>Summer Semester</option>';
}
echo '
</select>
</div>
</div>



<div class="form-row">
<div class="form-group col-md-6">
<label for="processend"><b>General deadline for the active process</b></label>
<input type="datetime-local" value="' . str_replace(' ', 'T', $array[$id]['end_date']) . '" name="processend" class="form-control" id="processend">
</div>
<div class="form-group col-md-6">
<label for="demandstart"><b>Demand planning start date</b></label>
<input type="datetime-local" value="' . str_replace(' ', 'T', $array[$id]['start_date']) . '" name="demandstart" class="form-control" id="demandstart">
</div>
</div>
<div class="form-row">
<div class="form-group col-md-6">
<label for="regstarta"><b>Start date for student registration Partner A</b></label>
<input type="datetime-local"name="regstarta" value="' . str_replace(' ', 'T', $array[$id]['start_date_for_a']) . '" class="form-control" id="regstarta">
</div>
<div class="form-group col-md-6">
<label for="regstartb"><b>Start date for student registration Partner B</b></label>
<input type="datetime-local" value="' . str_replace(' ', 'T', $array[$id]['start_date_for_b']) . '" name="regstartb" class="form-control" id="regstartb">
</div>
</div>
<div class="form-group">
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="" name="processclose" id="processclose">
  <label class="form-check-label" for="processclose">
    Close the process
  </label>
</div>
</div>
<button type="submit" id="process_update" class="btn btn-danger" >Update</button>
<input type="number" class="hidden" name="recordID" id="recordID" value="' . $input['ID'] . '">
</form>

';

?>
<script>
  $('form').on('submit',
    function(event) {
      event.preventDefault();
      var studyprogram = $('#studyprogram option:selected').val();
      var semester = $('#semester option:selected').val();
      var processend = $('#processend').val();
      var demandstart = $('#demandstart').val();
      var regstarta = $('#regstarta').val();
      var regstartb = $('#regstartb').val();
      var recordID = $('#recordID').val();
      if ($("#processclose").is(':checked')) {
        var processclose = true;
      } else {
        var processclose = false;
      }
      $.ajax({
        type: "POST",
        url: "../../assets/PHPFunctions/st_process_update.php",
        datatype: 'html',
        data: {
          studyprogram: studyprogram,
          semester: semester,
          processend: processend,
          demandstart: demandstart,
          regstarta: regstarta,
          regstartb: regstartb,
          processclose: processclose,
          recordID: recordID
        },
        success: function(data) {
          $.ajax({
            type: "POST",
            url: "../../assets/PHPFunctions/st_process_update.php",
            datatype: 'html',
            data: {
              sendMail: true,
              studyprogram: studyprogram,
              processend: processend,
              demandstart: demandstart,
              regstarta: regstarta,
              regstartb: regstartb,
              processclose: processclose
            },
            success: function(data) {},
            error: function() {}
          });
          document.location = "ST_prcess_overview.php"
        },
        error: function(e) {
          console.log(e)
        }
      });


    });
</script>

<?php
echo $OUTPUT->footer();
?>