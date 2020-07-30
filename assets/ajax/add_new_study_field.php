<?php

// add new study field

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");

// Get value from ajax
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};
$ProgramID = $input['ProgramID'];

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');


global $DB, $USER;
require_login();
$user =  $USER->id;

$id = $DB->insert_record("sr_study_fields", array('study_field_name' => '', 'description' => '', 'old' => 0, 'sr_study_programs_id' => $ProgramID));

$records = $DB->get_records_select("sr_study_fields", 'sr_study_programs_id = ? AND old = 0', array($ProgramID));
$edata = '';
foreach ($records as $result => $record) {


  $edata .= '<tr courseID = "' . $record->id . '">
                 <td >' . $record->id . '</td>
                 <td >' . $record->study_field_name . '</td>
                 <td >' . $record->description . '</td>
              </tr>';
};

// RETURN OUTPUT
echo $edata;
