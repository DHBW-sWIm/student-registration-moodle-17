<?php
 
// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.

define('AJAX_SCRIPT', true);

require(__DIR__ . '/../../../../config.php');
require_login();

global $USER, $DB;

// CHECK REQUEST METHOD
if ($_SERVER['REQUEST_METHOD']=='POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};
 
// PHP QUESTION TO MYSQL DB
 
// Connect to DB
 
  /*  Your code for new connection to DB*/ 
 
 
// Php question
  if ($input['action'] === 'edit') {

  $attributes['id'] = $input['ID'];
  $attributes['study_course_name'] = $input['Study_course_name'];
  $attributes['study_course_abbreviation'] = $input['Abbreviation'];
  $attributes['start_date'] = $input['Start_date'];
  $attributes['end_date'] = $input['End_date'];
  $attributes['course_capacity'] = $input['Course_capacity'];
 
  $DB->update_record('sr_active_study_course',$attributes);
 
} else if ($input['action'] === 'delete') {

  $param = $input['ID'];
 
  $DB->delete_records('sr_active_study_course',array('id'=>$param));
 
} else if ($input['action'] === 'restore') {
 
  // PHP code for edit restore
 
};
 
// Close connection to DB
 
/*  Your code for close connection to DB*/
 
// RETURN OUTPUT
echo json_encode($input);
 
?>