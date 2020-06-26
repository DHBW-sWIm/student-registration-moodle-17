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


 /* This document should contain the wating list view for CR 
  * This view should be only accessable when all seats for a ST process were reserved 
  * records should be either automatically transfered to a reservation list or 
  * manually. This configuration should be included in the Settings tile in the main menu 
  * for manual adjustment of waiting list, the manager or secretary should see 
  * students at the waiting list and choose which records to transfer to reservation 
  * list (just in case a CR deleted a reserved Seat)
  */

  global $DB, $PAGE, $OUTPUT, $CFG , $USER;
  require_once('../../../../config.php');
  $notificationtype = \core\output\notification::NOTIFY_INFO;
  $PAGE->set_heading('DHBW Student Registration');
  echo $OUTPUT->header();
  echo $OUTPUT->heading('Wating list');
  echo $OUTPUT->notification('You are now in wating list', $notificationtype);
  echo $OUTPUT->footer();


  ?>