<?php


require_once(dirname(dirname(dirname(__DIR__))) . '/config.php');
require_once(dirname(__DIR__) . '/assets/PHPClasses/UI.php');
require_once(dirname(__DIR__) . '/assets/PHPClasses/test.php');

global $DB, $PAGE, $OUTPUT, $CFG, $USER;


$PAGE->set_heading('DHBW Management Dashboard');

//$PAGE->navbar->add('Main view',new moodle_url('/local/student_registration/index.php'));

echo $OUTPUT->header();
//echo $OUTPUT->heading();

$tilesr = new CreateTile();
$tilesr->setTitle('Student Registration');
$tilesr->setButtonName('Manage');
$tilesr->setButtonURL($CFG->wwwroot.'/local/student_registration/views/Menu.php');
$tilesr->addListElement('Create a new ST process');
$tilesr->addListElement('Demand Planning');

$tilessp = new CreateTile();
$tilessp->setTitle('Scientific Paper');
$tilessp->setButtonName('Manage');
$tilessp->setButtonURL($CFG->wwwroot.'/local/student_registration/index.php');
$tilessp->addListElement('20 users included');
$tilessp->addListElement('10 GB of storage');
$tilessp->addListElement('Priority email support');
$tilessp->addListElement('Help center access');
$tilessp->setColor('primary');

$tilesla = new CreateTile();
$tilesla->setTitle('Lecturer Acquisition');
$tilesla->setButtonName('Manage');
$tilesla->setButtonURL($CFG->wwwroot.'/local/student_registration/index.php');
$tilesla->addListElement('20 users included');
$tilesla->addListElement('10 GB of storage');
$tilesla->addListElement('Priority email support');
$tilesla->addListElement('Help center access');
$tilesla->setColor('primary');


$tilescrm = new CreateTile();
$tilescrm->setTitle('CRM');
$tilescrm->setButtonName('Acess V-Tiger');
$tilescrm->setButtonURL($CFG->wwwroot.'/local/student_registration/index.php');
$tilescrm->addListElement('20 users included');
$tilescrm->addListElement('10 GB of storage');
$tilescrm->addListElement('Priority email support');
$tilescrm->addListElement('Help center access');
$tilescrm->setButtonIcon('fa fa-globe');

$tileContainer = new TileContainer();

$tileContainer->addTile($tilesr);
$tileContainer->addTile($tilessp);
$tileContainer->addTile($tilesla);
$tileContainer->addTile($tilescrm);

$tileContainer->render();


echo $OUTPUT->footer();


