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


 /* This document should contain the course creation view 
  * Here the manager should be able to create courses for a certain ST process 
  */

  require_once('../../../../config.php');
  global $DB, $PAGE, $OUTPUT, $CFG , $USER;



  require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

      //if (!has_capability('moodle/site:config', context_system::instance())) {
    //header('HTTP/1.1 403 Forbidden');
    //die();}

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $input = filter_input_array(INPUT_POST);
  } else {
    $input = filter_input_array(INPUT_GET);
  };

  $user = $USER->id;
  
  $ProcessID = $input['process_id'];
  $courseID = $input['courseID'];

  $PAGE->set_heading('DHBW Student Registration');
  $PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
  $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
  $PAGE->navbar->add('Course Management',new moodle_url('/local/student_registration/views/ST_process/ST_active_process_Ma_CC.php'));

  echo $OUTPUT->header();
  echo $OUTPUT->heading('Manage the New Course');

  echo '<form action="../../assets/PHPFunctions/st_cc_adjustment.php" method="post">
  <div class="form-group">
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="true" name="closecourse" id="closecourse">
  <label class="form-check-label" for="closecourse">
    Close this course
  </label>
</div>
</div>
<input  type="submit" class="btn btn-danger" value="Update">
<input type="number" class="hidden" name="ProcessID" id="ProcessID" value="'.$ProcessID.'">
<input type="number" class="hidden" name="courseID" id="courseID" value="'.$courseID.'">
</form>';