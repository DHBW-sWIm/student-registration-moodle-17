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

/* This document should contain the an overview of current active study fields for a certain process
  * when clicking on a certain row, the manager should be able to see records on waiting list for the relevant study field 
  */


require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');


//  Other possible option is  flexible_table  class fount at C:\xampp\htdocs\moodle\lib\tablelib.php  

global $DB, $PAGE, $OUTPUT, $CFG, $USER;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};

$processID = $input['processID'];

$user = $USER->id;
$context = context_system::instance();
require_login();
if (has_capability('local/student_registration:manage', $context)) {


    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
    $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->navbar->add('Student Registration Process Overview', new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));

    $PAGE->requires->jquery();
    echo $OUTPUT->header();

    $name = $DB->get_records_select('sr_process', 'id = ?', array($processID), '', 'program_name');
    $name = current($name);
    echo $OUTPUT->heading('Reservation Management for ' . $name->program_name);
    /*
        * Dynamic table creation based on records for sr_process tables that are still open
        */



    echo '</br>';
    $table = new html_table();
    $table->id = 'my-table';
    $table->attributes['class'] = 'table table-sm';
    $records = $DB->get_records_select('sr_study_places_status', 'sr_process_id = ?', array($processID));
    //$records = $DB->get_records_select("sr_process",'closed = 0 AND director_id = ?' ,array($user) );

    $table->head = array('Study Field', 'Total Capacity', 'Reserved Seats', 'Confirmed Seats', 'Withdrown',  'Total records on Waiting list');
    $table->align = array('left', 'left', 'left');

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


    echo '<div id="processID" hidden>' . $processID . '</div>';
} else {
    redirect($CFG->wwwroot);
};




?>

<script src="../../assets/JavaScript/jquery.tabledit.js"></script>

<script>
    // redirect when click on a row
    $(function() {
        $('#my-table tr[RecordID]').each(function() {
            $(this).css('cursor', 'pointer').hover(
                function() {
                    $(this).addClass('active');
                },
                function() {


                    $(this).removeClass('active');
                }).click(function() {
                var RecordID = $(this).attr('RecordID');
                var processID = $('#processID').html();
                redirectUrl = 'ST_WL_management.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                    '<input type="hidden" name="processID" value="' + processID + '"></input>' +
                    '<input type="hidden" name="RecordID" value="' + RecordID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();

            });
        });
    });
</script>

<?PHP
echo $OUTPUT->footer();
?>