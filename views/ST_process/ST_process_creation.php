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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};


//if (!has_capability('moodle/site:config', context_system::instance())) {
//header('HTTP/1.1 403 Forbidden');
//die();}


$PAGE->set_heading('Student registration process creation');

$PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
$PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
$PAGE->navbar->add('Student Registration Process Overview', new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));

echo $OUTPUT->header();

$records = $DB->get_records_select('sr_study_programs', 'old = ?', array(0), '',  'study_program_name');
echo '<div id="notification"></div>';
echo '
<form action="../../assets/PHPFunctions/st_process_creation.php" method="post">
  <form class="needs-validation">
    <div class="form-row">
      <div class="form-group col-md-3">
      <label for="studyprogram"><b>Study Program</b></label>
      <select id="studyprogram" name="studyprogram" class="form-control">
        <option selected>Choose...</option>
        ';
foreach ($records as $record => $val) {
  echo '<option>' . $record . '</option>';
}
echo '
      </select>
      <div class="invalid-feedback">
        Invalid Study Course selection.
      </div>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-md-3">
        <label for="semester"><b>Semester</b></label>
        <select id="semester" name="semester" class="form-control">
          <option selected>Winter Semester</option>
          <option>Summer Semester</option>
        </select>
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="processend"><b>General deadline for the active process</b></label>
        <input type="datetime-local" name="processend" class="form-control" id="processend" required>
        <span class="validity"></span>
      </div>
      <div class="form-group col-md-6">
        <label for="demandstart"><b>Demand planning start date</b></label>
        <input type="datetime-local" name="demandstart" class="form-control" id="demandstart" max= required>
        <span class="validity"></span>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="regstarta"><b>Start date for student registration Partner A</b></label>
        <input type="datetime-local" name="regstarta" class="form-control" id="regstarta" required>
        </div>
        <div class="form-group col-md-6">
        <label for="regstartb"><b>Start date for student registration Partner B</b></label>
        <input type="datetime-local" name="regstartb" class="form-control" id="regstartb" required>
        <span class="validity"></span>
      </div>
    </div>
    <input  type="submit" class="btn btn-danger" value="Create" onclick="checkHTMLForm()">
  </form>
</form>
  ';


echo $OUTPUT->footer(); ?>

<script src="../../assets/JavaScript/jquery.tabledit.js"></script>

<script>
  function checkHTMLForm() {
    var studyCourse = document.getElementById("studyprogram").value;
    var dateProcessEnd = document.getElementById("processend").value;
    var dateDemandStart = document.getElementById("demandstart").value;
    var dateRegStartA = document.getElementById("regstarta").value;
    var dateRegStartB = document.getElementById("regstartb").value;
    if (studyCourse == "Choose...") {
      event.preventDefault();
      event.stopPropagation();
      $("#notification").append('<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">No valid Study Program selected<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    } else if (dateDemandStart >= dateProcessEnd || dateRegStartA >= dateProcessEnd || dateRegStartB >= dateProcessEnd) {
      event.preventDefault();
      event.stopPropagation();
      $("#notification").append('<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">Check the date inputs for valid input.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    } else if (dateRegStartA <= dateDemandStart || dateRegStartB <= dateDemandStart) {
      event.preventDefault();
      event.stopPropagation();
      $("#notification").append('<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">Check the date inputs for valid input.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    } else if (dateregStartB <= dateRegStartA) {
      event.preventDefault();
      event.stopPropagation();
      $("#notification").append('<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">Check the date inputs for valid input.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }
  }
</script>