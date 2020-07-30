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

$ProcessID = $input['ProcessID'];
$courseID = $input['courseID'];

if (isset($input['closecourse'])) {
  if ($input['closecourse'] === 'true') {
    $DB->update_record('sr_active_study_course', array('id' => $courseID, 'closed' => '1'));
  }
} else {
  $DB->update_record('sr_active_study_course', array(
    'id' => $courseID, 'study_course_name' => $input['study_course_name'], 'study_course_abbreviation' => $input['study_course_abbreviation'],
    'start_date' => $input['startdate'], 'end_date' => $input['enddate'], 'course_capacity' => $input['course_capacity'], 'sr_study_fields_id' => $input['studyfields']
  ));
}


$_SESSION['ProcessID'] = $ProcessID;


redirect(new moodle_url('/local/student_registration/views/ST_process/ST_course_creation.php'));
