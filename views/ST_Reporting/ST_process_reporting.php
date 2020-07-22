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

    $PAGE->requires->jquery();
    echo $OUTPUT->header();
    echo $OUTPUT->heading('Process Reporting');
    echo '</br>';
    $processID = $input['processID'];
    /*
        * Dynamic table creation based on records for sr_process tables that are still open
        */



    echo '</br>';
    $table = new html_table();
    $table->id = 'my-table';
    $table->attributes['class'] = 'table table-sm';
    $records = $DB->get_records_select('sr_study_places_status', 'sr_process_id = ?', array($processID));

    $table->head = array('Study Field', 'Total Capacity', 'Reserved Seats', 'Confirmed Seats', 'Withdrown', 'Total records on Waiting list');
    $table->align = array('left', 'left', 'left', 'left', 'left', 'left');

    $i = 0;
    foreach ($records as $record) {

        $SFieldname = $DB->get_records_select('sr_study_fields', 'id = ? ', array($record->sr_study_fields_id));
        $countWaitingList = $DB->count_records('sr_waiting_list', array('sr_study_fields_id' => $record->sr_study_fields_id, 'sr_process_id' => $processID));
        $SFieldname = current($SFieldname);
        $row = new html_table_row();
        $row->attributes['RecordID'] = $record->id;


        $cell1 = new html_table_cell();
        $cell1->text = $SFieldname->study_field_name;

        $cell2 = new html_table_cell();
        $cell2->text = $record->study_places_available;

        $cell3 = new html_table_cell();
        $countreservationlist = $DB->count_records('sr_reservation_list', array('sr_study_fields_id' => $record->sr_study_fields_id, 'sr_process_id' => $processID));
        $cell3->text = $countreservationlist;

        $cell5 = new html_table_cell();
        $cell5->text = $DB->count_records_select('sr_reservation_list', 'sr_study_fields_id = ? AND sr_process_id = ? AND (contract_status = ? OR  contract_status = ?) ', array($record->sr_study_fields_id, $processID, 'Signed', 'Sent'));

        $cell6 = new html_table_cell();
        $cell6->text = $DB->count_records_select('sr_reservation_list', 'sr_study_fields_id = ? AND sr_process_id = ? AND contract_status = ? ', array($record->sr_study_fields_id, $processID, 'Withdrown'));

        $cell4 = new html_table_cell();
        $cell4->text = $countWaitingList;

        $row->cells  = array($cell1, $cell2, $cell3, $cell5, $cell6, $cell4);
        $table->rowclasses[$record->id] = '';
        $table->data[]  = $row;
    };

    echo html_writer::table($table);
} else {
    redirect($CFG->wwwroot);
};


?>

<script>
    // redirect when click on a row
    $(function() {
        $('#my-table tr[RecordID] td').each(function() {
            $(this).css('cursor', 'pointer').hover(
                function() {
                    $(this).addClass('active');
                },
                function() {


                    $(this).removeClass('active');
                }).click(function() {
                var ID = $(this).parent().attr('RecordID');
                redirectUrl = 'ST_course_fill_rate.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                    '<input type="hidden" name="ID" value="' + ID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();

            });


        });
    });
</script>