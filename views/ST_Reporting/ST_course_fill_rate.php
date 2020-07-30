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



global $DB, $PAGE, $OUTPUT, $CFG, $USER;


$user = $USER->id;
$context = context_system::instance();
require_login();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};


if (has_capability('local/student_registration:manage', $context)) {
    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php', array('id' => $user)));
    $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->navbar->add('Reporting', new moodle_url('/local/student_registration/views/ST_Reporting/ST_reporting_main.php'));
    $PAGE->navbar->add('Current Processes', new moodle_url('/local/student_registration/views/ST_Reporting/ST_current_processes.php'));


    echo $OUTPUT->header();

    // if all study field were selected
    if ($input['ProcessID']) {
        echo '</br>';
        $processID = $input['ProcessID'];

        $programName = $DB->get_record_select('sr_process', 'id = ?', array($processID))->program_name;
        echo $OUTPUT->heading('Process Reporting for ' . $programName);
        $records = $DB->get_records_select("sr_active_study_course", 'sr_process_id = ? AND closed = 0', array($processID));

        $capacity = array();
        $fill_rate = array();
        $course = array();
        foreach ($records as $record) {
            array_push($fill_rate, $DB->count_records('sr_reservation_list', array('sr_study_fields_id' => $record->sr_study_fields_id, 'sr_process_id' => $processID, 'sr_active_study_course_id' => $record->id)));
            array_push($capacity, $record->course_capacity);
            array_push($course, $record->study_course_abbreviation);
        }

        echo '<div class="row">';
        echo '<div class = "col">';


        $serie1 = new core\chart_series('Capacity', $capacity);
        $serie2 = new core\chart_series('Reserved', $fill_rate);
        $chart = new core\chart_bar();
        $chart->set_title('Reserved Seats by course');
        $chart->add_series($serie1);
        $chart->add_series($serie2);
        $chart->set_labels($course);
        echo $OUTPUT->render($chart);
        // query for collecting demand info on weekly bases for a study field within a process
        $sql = "SELECT WEEK(FROM_UNIXTIME(timecreated)) AS week , SUM(initial_demand) AS weekly_demand
            FROM {sr_capacity_planning}
            WHERE sr_process_id = $processID
            GROUP BY week";
        $demand = $DB->get_records_sql($sql);
        $demandSeries = [];
        $labelWeek_demand = [];
        foreach ($demand as $week) {
            array_push($demandSeries, $week->weekly_demand);
            array_push($labelWeek_demand, $week->week);
        }
        $WD = new core\chart_series('Weekly Demand', $demandSeries);

        // query for collecting reservation info on weekly bases for a study field within a process
        $sql = "SELECT WEEK(timecreated) AS week , count(id) AS weekly_reservation
            FROM {sr_reservation_list}
            WHERE sr_process_id = $processID
            GROUP BY week";
        $reservation = $DB->get_records_sql($sql);
        $reservationSeries = [];
        $labelWeek_reservation = [];
        foreach ($reservation as $week) {
            array_push($reservationSeries, $week->weekly_reservation);
            array_push($labelWeek_reservation, $week->week);
        }
        $WR = new core\chart_series('Weekly Reservation', $reservationSeries);


        echo '</div>';

        echo '</div>';
        echo '<div class = "row">';
        echo '<div class = "col-6">';
        $chart = new \core\chart_line();
        $chart->add_series($WD); // On pie charts we just need to set one series.
        $chart->set_labels($labelWeek_demand);
        $chart->set_title('Demand Overview');
        echo $OUTPUT->render_chart($chart, false);
        echo '</div>';

        echo '<div class = "col-6">';
        $chart = new \core\chart_line();
        $chart->add_series($WR); // On pie charts we just need to set one series.
        $chart->set_labels($labelWeek_reservation);
        $chart->set_title('Reservation Overview');
        echo $OUTPUT->render_chart($chart, false);
        echo '</div>';
        echo '</div>';
        //for one study field
    } else {
        $ID = $input['ID'];
        $sps = $DB->get_record_select('sr_study_places_status', 'id = ?', array($ID));
        $FID = $sps->sr_study_fields_id;
        $SFieldname = $DB->get_record_select('sr_study_fields', 'id = ? ', array($FID))->study_field_name;
        echo $OUTPUT->heading('Process Reporting for ' . $SFieldname);
        echo '</br>';


        $processID = $sps->sr_process_id;

        $records = $DB->get_records_select("sr_active_study_course", 'sr_process_id = ? AND sr_study_fields_id = ? AND closed = 0', array($processID, $FID));

        $capacity = array();
        $fill_rate = array();
        $course = array();
        foreach ($records as $record) {
            array_push($fill_rate, $DB->count_records('sr_reservation_list', array('sr_study_fields_id' => $record->sr_study_fields_id, 'sr_process_id' => $processID, 'sr_active_study_course_id' => $record->id)));
            array_push($capacity, $record->course_capacity);
            array_push($course, $record->study_course_abbreviation);
        }

        echo '<div class="row">';
        echo '<div class = "col-9">';


        $serie1 = new core\chart_series('Capacity', $capacity);
        $serie2 = new core\chart_series('Reserved', $fill_rate);
        $chart = new core\chart_bar();
        $chart->set_title('Reserved Seats by course');
        $chart->add_series($serie1);
        $chart->add_series($serie2);
        $chart->set_labels($course);
        echo $OUTPUT->render($chart);
        // query for collecting demand info on weekly bases for a study field within a process
        $sql = "SELECT WEEK(FROM_UNIXTIME(timecreated)) AS week , SUM(initial_demand) AS weekly_demand
            FROM {sr_capacity_planning}
            WHERE sr_process_id = $processID AND sr_study_fields_id = $FID
            GROUP BY week";
        $demand = $DB->get_records_sql($sql);
        $demandSeries = [];
        $labelWeek_demand = [];
        foreach ($demand as $week) {
            array_push($demandSeries, $week->weekly_demand);
            array_push($labelWeek_demand, $week->week);
        }
        $WD = new core\chart_series('Weekly Demand', $demandSeries);

        // query for collecting reservation info on weekly bases for a study field within a process
        $sql = "SELECT WEEK(timecreated) AS week , count(id) AS weekly_reservation
            FROM {sr_reservation_list}
            WHERE sr_process_id = $processID AND sr_study_fields_id = $FID
            GROUP BY week";
        $reservation = $DB->get_records_sql($sql);
        $reservationSeries = [];
        $labelWeek_reservation = [];
        foreach ($reservation as $week) {
            array_push($reservationSeries, $week->weekly_reservation);
            array_push($labelWeek_reservation, $week->week);
        }
        $WR = new core\chart_series('Weekly Reservation', $reservationSeries);

        echo '<br>';
        echo '</div>';

        echo '</div>';
        echo '<div class = "row">';
        echo '<div class = "col-6">';
        $chart = new \core\chart_line();
        $chart->add_series($WD); // On pie charts we just need to set one series.
        $chart->set_labels($labelWeek_demand);
        $chart->set_title('Demand Overview');
        echo $OUTPUT->render_chart($chart, false);
        echo '</div>';

        echo '<div class = "col-6">';
        $chart = new \core\chart_line();
        $chart->add_series($WR); // On pie charts we just need to set one series.
        $chart->set_labels($labelWeek_reservation);
        $chart->set_title('Reservation Overview');
        echo $OUTPUT->render_chart($chart, false);
        echo '</div>';
        echo '</div>';
    }
    echo $OUTPUT->footer();
}
