
<?php
// Study program closure
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

global $DB, $USER, $CFG;
require_login();
$user = $USER->id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};



if ($input['closecourse'] === 'true') {
  $DB->update_record('sr_study_programs', array('id' => $input['ProgramID'], 'old' => 1));

  $records = $DB->get_records_select('sr_study_fields', 'sr_study_programs_id = ? ', array($input['ProgramID']));
  foreach ($records as $record) {
    $DB->update_record('sr_study_fields', array('id' => $record->id, 'old' => 1));
  }
};



redirect(new moodle_url('/local/student_registration/views/SP_master_data/SP_Master_Data.php'));
?>