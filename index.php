<?php
// This file is part of the Local Analytics plugin for Moodle
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Information about the version of the plugin.
 *
 * @package   local_student_registration
 * @copyright 2020 "DHBW Mannheim" 
 * @license   https://moodle.dhbw-mannheim.de/ 
 */


require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/assets/PHPClasses/UI.php');

global $DB, $PAGE, $OUTPUT, $CFG, $USER;
require_login();

$context = context_system::instance();

if(has_capability('local/student_registration:manage', $context)){

$PAGE->set_heading('DHBW Management Dashboard');

echo $OUTPUT->header();

$tilesr = new CreateTile();
$tilesr->setTitle('Student Registration');
$tilesr->setButtonName('Manage');
$tilesr->setButtonURL(new moodle_url('/local/student_registration/views/Menu.php'));
$tilesr->addListElement('Create a new ST process');
$tilesr->addListElement('Demand Planning');

$tilessp = new CreateTile();
$tilessp->setTitle('Scientific Paper');
$tilessp->setButtonName('Manage');
$tilessp->setButtonURL(new moodle_url('/local/student_registration/index.php'));
$tilessp->addListElement('20 users included');
$tilessp->addListElement('10 GB of storage');
$tilessp->addListElement('Priority email support');
$tilessp->addListElement('Help center access');

$tilesla = new CreateTile();
$tilesla->setTitle('Lecturer Acquisition');
$tilesla->setButtonName('Manage');
$tilesla->setButtonURL(new moodle_url('/local/student_registration/index.php'));
$tilesla->addListElement('20 users included');
$tilesla->addListElement('10 GB of storage');
$tilesla->addListElement('Priority email support');
$tilesla->addListElement('Help center access');

$tilescrm = new CreateTile();
$tilescrm->setTitle('CRM');
$tilescrm->setButtonName('Acess V-Tiger');
$tilescrm->setButtonURL(new moodle_url('/local/student_registration/index.php'));
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

}else {
    redirect($CFG->wwwroot);
}


