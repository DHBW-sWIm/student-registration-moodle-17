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


/* This document should contain the Student registration process reporting overview (only for managers)
  * Here the manager should see two tiles:  
  * 1- tile for all active ST processes, their relevant study fields, planned demand by company vs. actual registration (reservation) 
  * 2- tile for comparison of the actual vs. plannded demand among years ; totals 
  */



require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');


//  Other possible option is  flexible_table  class fount at C:\xampp\htdocs\moodle\lib\tablelib.php  

global $DB, $PAGE, $OUTPUT, $CFG, $USER;


$user = $USER->id;
$context = context_system::instance();
require_login();

//if (!has_capability('moodle/site:config', context_system::instance())) {
//header('HTTP/1.1 403 Forbidden');
//die();}
require_once(dirname(__DIR__) . '../../assets/PHPClasses/UI.php');


if (has_capability('local/student_registration:manage', $context)) {
    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php', array('id' => $user)));
    $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
    echo $OUTPUT->header();



    $tileContainer = new TileContainer();

    $record['moodle_capability'] = 'local/student_registration:manage';
    $record['button_name'] = 'View';
    $record['button_url'] = '/local/student_registration/views/ST_Reporting/ST_current_processes.php';
    $record['button_icon'] = 'fa fa-pie-chart';
    $record['color'] = 'danger';
    $record['title'] =  'Process Report';

    $tile = new CreateTile((object)$record, context_system::instance());
    if ($tile->build == true) {
        $tileContainer->addTile($tile);
    }

    $record['moodle_capability'] = 'local/student_registration:manage';
    $record['button_name'] = 'View';
    $record['button_url'] = '/local/student_registration/views/ST_Reporting/ST_com_active_pro.php';
    $record['button_icon'] = 'fa fa-area-chart';
    $record['color'] = 'danger';
    $record['title'] =  'Company Report';

    $tile = new CreateTile((object)$record, context_system::instance());
    if ($tile->build == true) {
        $tileContainer->addTile($tile);
    }

    $record['moodle_capability'] = 'local/student_registration:manage';
    $record['button_name'] = 'View';
    $record['button_url'] = '/local/student_registration/views/ST_Reporting/ST_annual_report.php';
    $record['button_icon'] = 'fa fa-line-chart';
    $record['color'] = 'danger';
    $record['title'] =  'Annual Report';

    $tile = new CreateTile((object)$record, context_system::instance());
    if ($tile->build == true) {
        $tileContainer->addTile($tile);
    }



    $tileContainer->render();
}
