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
require_once(dirname(dirname(__DIR__)) . '/assets/PHPClasses/form.php');

global $DB, $PAGE, $OUTPUT, $CFG, $USER;

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

$id=$input['ID'];


$PAGE->set_heading('Student registration process creation');

$PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
$PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
$PAGE->navbar->add('Student Registration process overview',new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));

echo $OUTPUT->header();

$programs = $DB->get_records_select('sr_study_programs' , '' , array() ,'',  'study_program_name');
$record = $DB->get_records_select('sr_process','id = ?', array($id) );

$array = json_decode(json_encode($record ), true);
$defaultdate= $array[$id]['start_date'];
$fafda =  str_replace(' ', '', $array[$id]['start_date_for_b']);

echo '<form action="../../assets/PHPFunctions/st_process_update.php" method="post">

<div class="form-row">
<div class="form-group col-md-3">
<label for="studyprogram" value="'.$array[$id]['program_name'].'">Study Program</label>
<select id="studyprogram" name="studyprogram" class="form-control">
  <option selected>Choose...</option>
  ';foreach($programs as $record=>$val){
      if($record === $array[$id]['program_name']){
        echo '<option selected>'.$record.'</option>';
      }else echo '<option>'.$record.'</option>';
     
   } echo'
  
</select>
</div>
</div>

<div class="form-row">
<div class="form-group col-md-3">
<label for="semester">Semester</label>
<select id="semester" name="semester" class="form-control">
  <option selected>Choose...</option>
  <option>Summer Semester</option>
  <option>Winter Semester</option>
</select>
</div>
</div>



<div class="form-row">
<div class="form-group col-md-6">
<label for="processend">General deadline for the active process</label>
<input type="datetime-local" value="'.str_replace(' ', 'T', $array[$id]['end_date']).'" name="processend" class="form-control" id="processend">
</div>
<div class="form-group col-md-6">
<label for="demandstart">Demand planning start date</label>
<input type="datetime-local" value="'.str_replace(' ', 'T', $array[$id]['start_date']).'" name="demandstart" class="form-control" id="demandstart">
</div>
</div>
<div class="form-row">
<div class="form-group col-md-6">
<label for="regstarta">Start date for student registratoin Partner A</label>
<input type="datetime-local"name="regstarta" value="'.str_replace(' ', 'T', $array[$id]['start_date_for_a']).'" class="form-control" id="regstarta">
</div>
<div class="form-group col-md-6">
<label for="regstartb">Start date for student registratoin Partner B</label>
<input type="datetime-local" value="'.str_replace(' ', 'T', $array[$id]['start_date_for_b']).'" name="regstartb" class="form-control" id="regstartb">
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
<input  type="submit" class="btn btn-danger" value="Update">
<input type="number" class="hidden" name="recordID" id="recordID" value="'.$input['ID'].'">
</form>

';

echo $OUTPUT->footer();

?>
