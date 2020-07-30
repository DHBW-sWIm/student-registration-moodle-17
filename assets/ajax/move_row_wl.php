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

$RecordID = $input['RecordID'];
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
require_once('../PHPMailer/waiting_list_record_moved.php');
global $DB, $USER;
require_login();
$user =  $USER->id;

$record = $DB->get_record_select("sr_waiting_list", 'id = ?', array($RecordID));

$DB->delete_records('sr_waiting_list', ['id' => (int) $record->id]);
unset($record->id);
$record->contract_status = 'Not Signed';
$record->sr_study_fields_id = $input['SFID'];
unset($record->moved_to_rl);
$time_posted = date("Y-m-d H:i:s", time());
$DB->insert_record('sr_reservation_list', array(
  'first_name' => $record->first_name, 'last_name' => $record->last_name, 'date_of_birth' => $record->date_of_birth, 'private_email' => $record->private_email, 'sr_company_representative_id' => $record->sr_company_representative_id, 'sr_process_id' => $record->sr_process_id, 'sr_study_fields_id' => $record->sr_study_fields_id, 'usermodified' => $record->usermodified, 'timecreated' => $time_posted, 'contract_status' => $record->contract_status
));

notify_cr_wl_to_rl('', $record->sr_process_id, $record->sr_study_fields_id, $record->sr_company_representative_id);

echo 'true';
