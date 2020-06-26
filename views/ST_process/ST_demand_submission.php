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


 /* This document should contain the demand submission view for Company Representatives
  * This view should be accessable only for CRs redirected from ST_active_process.php 
  * simple view just for input planned demand 
  */


  

global $CFG, $DB , $PAGE ,$USER;

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $input = filter_input_array(INPUT_POST);
  } else {
    $input = filter_input_array(INPUT_GET);
  };
  

  $PAGE->set_heading('DHBW Student Registration');
  $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
  $PAGE->navbar->add('Demand Planning',new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_DP.php'));

  echo $OUTPUT->header();
  echo $OUTPUT->heading('Submit demand planning');
  echo '</br>';

  $where = 'id = ?';
  $processID = array('ProcessID'=>$input['ID']);

  $record = $DB->get_record_select('sr_process' , $where , $processID);
  $studyProgramID = array($record->sr_study_programs_id);
  $where2 = 'sr_study_programs_id = ?';
  $fields = $DB->get_records_select('sr_study_fields', $where2 , $studyProgramID);

  $dpUpdate = $DB->get_records_select('sr_capacity_planning', 'sr_process_id = ? AND sr_company_representative_id = ?', array($processID['ProcessID'] , $USER->id ));


  echo '<form action="../../assets/PHPFunctions/st_dp_submission.php" method="post">';
  echo '<div class="form-group">';
  foreach($fields as $field=>$fval){
    if($dpUpdate){
      foreach($dpUpdate as $updaterecords=>$val){
        if($val->sr_study_fields_id  === $fval->id)
        $initial_demand = $val->initial_demand;
      }
    }else $initial_demand = 0;
   echo '<div class="form-group row">';
   echo '<label for="colFormLabel" class="col-sm-3 col-form-label">'.$fval->study_field_name.'</label>';
   echo '<div class="col-sm-4">';
   echo '<input type="number" name="'.$fval->id.'" value= "'.$initial_demand.'" class="form-control" id="'.$fval->id.'" placeholder=""></div>';
   echo '</div>';
  };
  echo '</br>';
  echo '<input type="hidden" name="ProcessID" value="'.$processID['ProcessID'].'" ></div>';
  if($dpUpdate){
    echo '<button type="submit" name ="update" class="btn btn-danger">Update</button>';
  }else   echo '<button type="submit" name ="insert" class="btn btn-danger">Submit</button>';

  echo '</div>';
  echo '</form>';

  echo $OUTPUT->footer();

  ?>