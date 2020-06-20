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


 /* This document should contain the Student registration process creation view 
  * Here the manager should be able to see an create and later manage (update, or close) a 
  * certain current active ST process
  * Here the manager sets deadlines for demand submission as well as Seats reservation  
  * by company representitives 
  */


require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
require_once(dirname(dirname(__DIR__)) . '/assets/PHPClasses/form.php');

global $DB, $PAGE, $OUTPUT, $CFG, $USER;


$PAGE->set_heading('Student registration process creation');

$PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
$PAGE->navbar->add('Main Menu',new moodle_url('/local/student_registration/views/Menu.php'));
$PAGE->navbar->add('Student Registration process overview',new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));



echo $OUTPUT->header();


$newform = new ST_process_creation();

$newform->render();
$newform->display();



echo $OUTPUT->footer();