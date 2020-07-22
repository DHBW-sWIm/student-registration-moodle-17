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

/* This document should contain the an overview of current RL of a study fields for a certain process and a certain company
  * The manager should be able to delete records from RL of a company 
  */


require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');


//  Other possible option is  flexible_table  class fount at C:\xampp\htdocs\moodle\lib\tablelib.php  

global $DB, $PAGE, $OUTPUT, $CFG, $USER;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};

$user = $USER->id;
$context = context_system::instance();
require_login();
if (has_capability('local/student_registration:manage', $context)) {

    $companyID = $input['companyID'];
    $processID = $input['processID'];
    $SFID = $input['SFID']; // study field ID

    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
    $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->navbar->add('Student Registration Process Overview', new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));

    $programname = $DB->get_records_select('sr_process', 'id = ?', array($processID), '', 'program_name');
    $programname = current($programname);
    $fieldname = $DB->get_records_select('sr_study_fields', 'id = ?', array($SFID), '', 'study_field_name');
    $fieldname = current($fieldname);

    $PAGE->requires->jquery();
    echo $OUTPUT->header();
    $companyname = $DB->get_record_select('dg_company', 'id = ?', array($companyID), 'company_name');
    echo $OUTPUT->heading('Reservation List Management for ' . $programname->program_name . ' ' . $fieldname->study_field_name . ': <b>' . $companyname->company_name . '</b>');
    echo '<br>';
    echo '<div id="notification"></div>';
    // get all records for a company on WL for a certain study field of an active process
    $where = "WHERE com.id = $companyID AND rl.sr_study_fields_id = $SFID AND rl.sr_process_id = $processID";
    $sort = "";
    $sql = "SELECT rl.id, rl.first_name, rl.last_name, rl.date_of_birth, rl.private_email, rl.timecreated ,rl.contract_status
            FROM {sr_reservation_list} AS rl
            INNER JOIN {dg_company_representative} AS cr ON rl.sr_company_representative_id = cr.mdl_user_id
            INNER JOIN {dg_company} AS com ON cr.compnay_id = com.id
            {$where}";

    $recordsjoinRL = $DB->get_records_sql($sql);


    $table = new html_table();
    $table->id = 'my-table';
    $table->attributes['class'] = 'table table-sm ';


    $table->head = array('ID', 'First Name', 'Last Name', 'Date of Birth', 'Email', 'Reservation Date', 'Contract Status');
    $table->align = array('left', 'left', 'left', 'left', 'left');
    $i = 0;
    foreach ($recordsjoinRL as $record) {
        $i++;
        $row = new html_table_row();
        $row->attributes['RecordID'] = $record->id; // company ID 
        $cell0 = new html_table_cell();
        $cell0->text = $i;
        $cell1 = new html_table_cell();
        $cell1->text = $record->first_name;

        $cell2 = new html_table_cell();
        $cell2->text = $record->last_name;

        $cell3 = new html_table_cell();
        $cell3->text = $record->date_of_birth;
        $cell4 = new html_table_cell();
        $cell4->text = $record->private_email;

        $cell6 = new html_table_cell();
        $date = new DateTime($record->timecreated);
        $timecompare = new DateTime($record->timecreated);
        $timecompare->modify('+1 month');
        if ($timecompare > new DateTime("now")) {
            $padg = '<b><h5><span class="badge badge-pill badge-success">' . $date->format('Y:m:d') . '</span></h5></b>';
        } else $padg = '<b><h5><span class="badge badge-pill badge-danger">' . $date->format('Y:m:d') . '</span></h5></b>';

        $cell6->text = $padg;


        $cell5 = new html_table_cell();
        $cell5->text = $record->contract_status;
        $row->cells  = array($cell0, $cell1, $cell2, $cell3, $cell4, $cell6, $cell5);
        $table->rowclasses[$record->id] = '';
        $table->data[]  = $row;
    }

    echo html_writer::table($table);
    echo $OUTPUT->footer();
} else {
    redirect($CFG->wwwroot);
};

?>


<script>
    $(function() {
        $('#my-table thead tr').append('<td class="text-right" style="padding-right: 4%;"><b>Action</b></td>');
        i = 0;
        $('#my-table tr[RecordID]').each(function() {
            i++;
            $(this).append('<td id="reservationlist' + i + '" class="" style="cursor: pointer; "><button type="button" class="btn btn-danger pull-right" style="cursor: pointer;">Cancel Reservation</button></td>');
            $('#reservationlist' + i + '').css('cursor', 'pointer').hover(
                function() {
                    $(this).addClass('active');
                },
                function() {

                    $(this).removeClass('active');
                }).click(function() {
                var RecordID = $(this).parent().attr('RecordID');
                alert("xxxxxx");
                $.ajax({
                    type: "POST",
                    url: "../../assets/ajax/delete_row_rl.php",
                    datatype: 'html',
                    data: {
                        RecordID: RecordID,
                    },
                    success: function(data) {
                        $("#notification").append('<div id="errormsg" class="alert alert-success alert-dismissible fade show" role="alert">Record has been deleted from Reservation List<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');

                    },
                    error: function() {
                        $("#notification").append('<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">Something went wrong!!!!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                    }
                });
                $(this).parent().fadeOut();
            });

        });
    });
</script>