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


 /* This document should contain the course creation view 
  * Here the manager should be able to create courses for a certain ST process 
  * and have an overview (table) of all other relevant courses of that ST 
  * whenever the manager click on a record he or she will redirect to 
  * ST_course_student_assignment view 
  */

  global $DB, $PAGE, $OUTPUT, $CFG , $USER;
  require_once('../../../../config.php');


  require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $input = filter_input_array(INPUT_POST);
  } else {
    $input = filter_input_array(INPUT_GET);
  };

  $user = $USER->id;
  
  $ProcessID = $input['ProcessID'];