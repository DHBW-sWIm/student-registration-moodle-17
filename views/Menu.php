<?php
require_once(dirname(dirname(dirname(__DIR__))) . '/config.php');
require_once(dirname(__DIR__) . '/assets/PHPClasses/UI.php');

global $DB, $PAGE, $OUTPUT, $CFG, $USER;


$PAGE->set_heading('Student Registration');
$PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/views/test_architecture.php'));
$PAGE->navbar->add('Main Menu',new moodle_url('/local/student_registration/views/Menu.php'));


echo $OUTPUT->header();
//echo $OUTPUT->heading('Menu');



$tiledp = new CreateTile();
$tiledp->setTitle('Demand Planning');
$tiledp->setButtonName('Manage');
$tiledp->setButtonURL($CFG->wwwroot.'/local/student_registration/index.php');
$tiledp->addListElement('Start your Demand Planing');

$tilesr = new CreateTile();
$tilesr->setTitle('Student Registration');
$tilesr->setButtonName('Manage');
$tilesr->setButtonURL($CFG->wwwroot.'/local/student_registration/index.php');
$tilesr->addListElement('Start registering your students');

$tiless = new CreateTile();
$tiless->setTitle('Settings for STRE');
$tiless->setButtonName('Manage');
$tiless->setButtonURL(new moodle_url('/local/student_registration/index.php', array('id' => $USER->id)));
$tiless->addListElement('Go to Settings');


$tileContainer = new TileContainer();

$tileContainer->addTile($tiledp);
$tileContainer->addTile($tilesr);
$tileContainer->addTile($tiless);

$tileContainer->render();


    echo $OUTPUT->footer();
?>