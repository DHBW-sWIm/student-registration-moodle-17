<?php


require_once(dirname(dirname(dirname(__DIR__))) . '/config.php');
require_once(dirname(__DIR__) . '/assets/PHPClasses/test.php');

global $DB, $PAGE, $OUTPUT, $CFG, $USER;


$PAGE->set_heading('Second Test Page Heading');



$PAGE->navbar->add('Navigate to architecture page',new moodle_url('/local/student_registration/views/test_architecture.php'));



echo $OUTPUT->header();
echo $OUTPUT->heading('Test Heading');

$newform = new formexample();

$newform->render();
$newform->display();

echo $OUTPUT->single_button(new moodle_url('/local/student_registration/index.php', array('id' => $USER->id)),
    'Go to initial view', $attributes = null);

echo $OUTPUT->footer();