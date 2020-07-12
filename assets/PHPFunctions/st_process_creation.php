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

use MongoDB\Exception\Exception;
use PhpOffice\PhpSpreadsheet\Calculation\ExceptionHandler;

//if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");


require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
global $DB, $CFG, $USER;

$user = $USER->id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

try {


  $studyprogram = $input['studyprogram'];

  $studyprogramid = $DB->get_record_select('sr_study_programs', 'study_program_name = ? AND old = 0', array($studyprogram), 'id');
  $studyprogramid = $studyprogramid->id;
  $studyprogram = $input['studyprogram'];
  if ($input['semester'] === 'Choose...') {
    $semester = '';
  } else {
    $semester = $input['semester'];
  };
  if ($input['processend'] !== '') {
    $processend = $input['processend'];
  } else $processend = "0000-00-00 00:00:00";
  if ($input['demandstart'] !== '') {
    $demandstart = $input['demandstart'];
  } else $demandstart = "0000-00-00 00:00:00";
  if ($input['regstarta'] !== '') {
    $regstarta = $input['regstarta'];
  } else $regstarta = "0000-00-00 00:00:00";
  if ($input['regstartb'] !== '') {
    $regstartb = $input['regstartb'];
  } else $regstartb = "0000-00-00 00:00:00";

  $DB->insert_record('sr_process', array(
    'program_name' => $studyprogram,
    'start_date' => $demandstart, 'end_date' => $processend, 'start_date_for_a' => $regstarta,
    'start_date_for_b' => $regstartb, 'closed' => 0, 'director_id' => $user,
    'sr_study_programs_id' => $studyprogramid, 'semester' => $input['semester']
  ));

  include_once('../../dashboard_lib.php');
  $new = notify_cr('Email to Company');


  redirect(new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));
} catch (Exception $e) {
  redirect(new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));
} catch (ExceptionHandler $e) {
  redirect(new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));
} catch (dml_exception $e) {
  redirect(new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));
}
