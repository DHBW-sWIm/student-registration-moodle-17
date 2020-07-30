<?php


require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

global $DB, $USER;
require_login();
$user = $USER->id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};
$processID = $input['ProcessID'];
$lastKey = key(array_slice($input, -1, 1, true));
unset($input['ProcessID']);
unset($input[$lastKey]);

foreach ($input as $record => $val) {

  if ($lastKey === 'update') {
    $row = $DB->get_record_select('sr_capacity_planning', 'sr_process_id = ? AND sr_study_fields_id = ? AND sr_company_representative_id = ? ', array($processID, $record, $user));
    $DB->update_record('sr_capacity_planning', array('id' => $row->id, 'initial_demand' => $val, 'timemodified' => time()));
  } else {
    $DB->insert_record('sr_capacity_planning', array('initial_demand' => $val, 'sr_process_id' => $processID, 'sr_study_fields_id' => $record, 'sr_company_representative_id' => $user, 'timecreated' => time(), 'usermodified' => $user));
  }
}
redirect(new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_DP.php'));
