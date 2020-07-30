<?php

// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");

// Get value from ajax
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};
$processID = $input['ProcessID'];

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

// PHP QUESTION TO MYSQL DB

// Connect to DB

/*  Your code for new connection to DB*/

// Php question

/*  Your code for insert data to DB */


global $DB, $USER;

$user =  $USER->id;

require_login();

$id = $DB->insert_record("sr_active_study_course", array('study_course_name' => 'Initial', 'study_course_abbreviation' => 'Initial', 'sr_employees_id' => $user, 'sr_process_id' => $processID));

$records = $DB->get_records_select("sr_active_study_course", 'sr_employees_id = ? AND sr_process_id = ? AND closed = 0', array($user, $processID));
$i = 0;
$edata = '';
foreach ($records as $result => $record) {
  $i++;

  if ($record->end_date < date("Y-m-d h-m-s", time())) {
    $padg2 = '<h5><b><span class="badge badge-pill badge-danger">' . $record->end_date . '</span></b></h5>';
  } else $padg2 = '<h5><b><span class="badge badge-pill badge-warning">' . $record->end_date . ' </span></b></h5>';
  if ($record->start_date < date("Y-m-d h-m-s", time())) {
    $padg = '<h5><b><span class="badge badge-pill badge-success">' . $record->start_date . '</span></b></h5>';
  } else $padg = '<h5><b><span class="badge badge-pill badge-info">' . $record->start_date . ' </span></b></h5>';
  $SFName = $DB->get_record_select('sr_study_fields', 'id = ?', array($record->sr_study_fields_id))->study_field_name;
  $edata .= '<tr class="" courseID="' . $record->id . '" style="cursor: pointer;">
                 <td >' . $record->study_course_name . '</td>
                 <td ><h5><b><span class="badge badge-pill badge-light">' . $record->study_course_abbreviation . '</span></b></h5></td>
                 <td >' . $SFName . '</td>
                 <td >' . $padg . '</td>
                 <td >' . $padg2 . '</td>
                 <td >' . $record->course_capacity . '</td>
              </tr>
             ';
};

// RETURN OUTPUT
echo $edata;
