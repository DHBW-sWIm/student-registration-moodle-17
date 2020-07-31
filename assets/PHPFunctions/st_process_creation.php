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
require_once(dirname(dirname(__DIR__)) . '/assets/PHPMailer/process_creation_mail.php');
global $DB, $CFG, $USER;
require_login();
$user = $USER->id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

if (isset($input['sendMail'])) {
  if ($input['demandstart'] !== '') {
    $demandstart = (new DateTime($input['demandstart']))->format('Y-m-d H:m:s');
  } else $demandstart = "0000-00-00 00:00:00";
  if ($input['regstarta'] !== '') {
    $regstarta = (new DateTime($input['regstarta']))->format('Y-m-d H:m:s');
  } else $regstarta = "0000-00-00 00:00:00";
  if ($input['regstartb'] !== '') {
    $regstartb = (new DateTime($input['regstartb']))->format('Y-m-d H:m:s');
  } else $regstartb = "0000-00-00 00:00:00";
  notify_cr_pc('', $demandstart, $regstarta, $regstartb, (new DateTime($input['processend']))->format('Y-m-d H:m:s'), $input['studyprogram']);
} else {

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
      $processend = (new DateTime($input['processend']))->format('Y-m-d H:m:s');
    } else $processend = "0000-00-00 00:00:00";
    if ($input['demandstart'] !== '') {
      $demandstart = (new DateTime($input['demandstart']))->format('Y-m-d H:m:s');
    } else $demandstart = "0000-00-00 00:00:00";
    if ($input['regstarta'] !== '') {
      $regstarta = (new DateTime($input['regstarta']))->format('Y-m-d H:m:s');
    } else $regstarta = "0000-00-00 00:00:00";
    if ($input['regstartb'] !== '') {
      $regstartb = (new DateTime($input['regstartb']))->format('Y-m-d H:m:s');
    } else $regstartb = "0000-00-00 00:00:00";

    $DB->insert_record('sr_process', array(
      'program_name' => $studyprogram,
      'start_date' => $demandstart, 'end_date' => $processend, 'start_date_for_a' => $regstarta,
      'start_date_for_b' => $regstartb, 'closed' => 0, 'director_id' => $user,
      'sr_study_programs_id' => $studyprogramid, 'semester' => $input['semester']
    ));



    // notify_cr_pc('', $demandstart, $regstarta, $regstartb);


    // redirect(new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));
  } catch (Exception $e) {
    redirect(new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));
  } catch (ExceptionHandler $e) {
    redirect(new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));
  } catch (dml_exception $e) {
    redirect(new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));
  }
}
