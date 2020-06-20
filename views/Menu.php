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


 /* This document should contain the Student registration main menu 
  * from here we navigate to Student Registration porcess creation tile & to Status Overview tile 
  * Settings tile is optional 
  */

require_once(dirname(dirname(dirname(__DIR__))) . '/config.php');
require_once(dirname(__DIR__) . '/assets/PHPClasses/UI.php');

global $PAGE, $OUTPUT, $CFG, $USER;
require_login();
$user = $USER->id;
$context = context_system::instance();

if(has_capability('local/student_registration:manage', $context)){

  $PAGE->set_heading('Student Registration');
  $PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php', array('id' => $user)));  
  echo $OUTPUT->header();

  $tiledp = new CreateTile();
  $tiledp->setTitle('Capacity Planning');
  $tiledp->setButtonName('Manage');
  $tiledp->setButtonURL(new moodle_url('/local/student_registration/views/ST_process/ST_active_process_Ma_CP.php', array('id' => $user)));
  $tiledp->addListElement('Manage Capacity Planning');

  $tilesr = new CreateTile();
  $tilesr->setTitle('Student Registration');
  $tilesr->setButtonName('Manage');
  $tilesr->setButtonURL(new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php', array('id' => $user)));
  $tilesr->addListElement('Create a new registration process');
  $tilesr->addListElement('Manage active processes');

  $tiless = new CreateTile();
  $tiless->setTitle('Settings for STRE');
  $tiless->setButtonName('Edit');
  $tiless->setButtonURL(new moodle_url('/local/student_registration/views/Settings.php', array('id' => $user)));
  $tiless->addListElement('Go to Settings');

  $tilecc = new CreateTile();
  $tilecc->setTitle('Course Management');
  $tilecc->setButtonName('Manage');
  $tilecc->setButtonURL(new moodle_url('/local/student_registration/views/ST_process/ST_active_process_Ma_CC.php', array('id' => $user)));
  $tilecc->addListElement('Create new courses');
  $tilecc->addListElement('Assign students to courses');

  $tileContainer = new TileContainer();
  $tileContainer->addTile($tilesr);
  $tileContainer->addTile($tiledp);
  $tileContainer->addTile($tilecc);
  $tileContainer->addTile($tiless);


  $tileContainer->render();

  echo $OUTPUT->footer();

}elseif (has_capability('local/student_registration:cr', $context)){

  $PAGE->set_heading('Student Registration');

  echo $OUTPUT->header();

  $tiledp = new CreateTile();
  $tiledp->setTitle('Demand Planning');
  $tiledp->setButtonName('Manage');
  $tiledp->setButtonURL(new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_DP.php?#FetchMode=DP&CR', array('FetchMode'=>'DP'),'helloll'));
  $tiledp->addListElement('Start your Demand Planning');

  $tilesr = new CreateTile();
  $tilesr->setTitle('Student Registration');
  $tilesr->setButtonName('Manage');
  $tilesr->setButtonURL(new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_ST.php?#FetchMode=ST&CR', array('FetchMode'=>'ST'),'Hellp'));
  $tilesr->addListElement('Start registering your students');

  $tileContainer = new TileContainer();
  $tileContainer->addTile($tiledp);
  $tileContainer->addTile($tilesr);

  $tileContainer->render();

  echo $OUTPUT->footer();

}else {
  redirect($CFG->wwwroot);
}

?>