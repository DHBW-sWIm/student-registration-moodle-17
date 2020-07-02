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

 /*
  * The manager should obtain an overview on the current demand for an active process 
  * and should be able to compare it with the previous' year demand and actual registered students
  * We should enable the manager to make decision without having to navigate to different UI to obtain infos
  * UI design e.g. Top: 2 charts , buttom: submition from for capacity planning  
  */

  //if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");
 
// Get value from ajax
//define('AJAX_SCRIPT', true);



global $CFG, $DB , $PAGE;

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $input = filter_input_array(INPUT_POST);
  } else {
    $input = filter_input_array(INPUT_GET);
  };
  
      //if (!has_capability('moodle/site:config', context_system::instance())) {
    //header('HTTP/1.1 403 Forbidden');
    //die();}

  $PAGE->set_heading('DHBW Student Registration');
  $PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
  $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
  $PAGE->navbar->add('Capacity Planning',new moodle_url('/local/student_registration/views/ST_process/ST_active_process_Ma_CP.php'));

  echo $OUTPUT->header();
  echo $OUTPUT->heading('Submit capacity planning');
  echo '</br>';


  $ProcessID = $input['ID'];
  unset($_SESSION['ProcessID']);

  $record = $DB->get_record_select('sr_process' , 'id = ?' , array($ProcessID));

  $fields = $DB->get_records_select('sr_study_fields', 'sr_study_programs_id = ?' , array($record->sr_study_programs_id));
  $supply = $DB->get_records_select('sr_study_places_status', 'sr_process_id = ?', array($ProcessID));

  echo '<form  action="../../assets/PHPFunctions/st_cp_submission.php" method="post">';
  echo '<div class="form-group">';

  if($supply){

    $_SESSION['ProcessID'] = $ProcessID;
    $demand = 0;
    foreach($fields as $field){
      foreach($supply as $SFS){
        if ($field->id == $SFS->sr_study_fields_id){
          $demand = $SFS->study_places_available;
        break;
        }
      }

     echo '<div class="form-group row">';
     echo '<label for="colFormLabel" id="'.$field->study_field_name.'" class="col-sm-3 col-form-label">'.$field->study_field_name.'</label>';
     echo '<div class="col-sm-4">';
     echo '<input type="number" name="'.$field->id.'" class="form-control" id="'.$field->id.'" value="'.$demand.'"></div>';
     echo '</div>';
    };
    echo '</br>';
    echo '<button type="submit" class="btn btn-danger">Update</button>';
    echo '<input id = "Update" name="Update" value="true" hidden>';
    echo '</div>';
    echo '</form>';


  }else {

    $_SESSION['ProcessID'] = $ProcessID;
  foreach($fields as $field){
   echo '<div class="form-group row">';
   echo '<label for="colFormLabel" id="'.$field->study_field_name.'" class="col-sm-3 col-form-label">'.$field->study_field_name.'</label>';
   echo '<div class="col-sm-4">';
   echo '<input type="number" name="'.$field->id.'" class="form-control" id="'.$field->id.'" placeholder=""></div>';
   echo '</div>';
  };
  echo '</br>';
  echo '<button type="submit" class="btn btn-danger">Submit</button>';
  echo '</div>';
  echo '</form>';

  
}

echo $OUTPUT->footer();

?>