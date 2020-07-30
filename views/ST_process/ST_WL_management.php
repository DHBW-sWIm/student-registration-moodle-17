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

/* This document should contain the an overview of current WL and RL of a study fields for a certain process grouped by company name
  * when clicking on a certain row, the manager should be able to see records on waiting list of resrevation list of a company for the relevant study field 
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
    $processID = $input['processID'];
    $RecordID = $input['RecordID']; // demand status row ID
    $DSrow = $DB->get_record_select('sr_study_places_status', 'id = ?', array($RecordID)); // demand status row 
    $SFID = $DSrow->sr_study_fields_id; // study field ID

    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
    $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->navbar->add('Student Registration Process Overview', new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));

    $PAGE->requires->jquery();
    echo $OUTPUT->header();

    $programname = $DB->get_records_select('sr_process', 'id = ?', array($processID), '', 'program_name');
    $programname = current($programname);
    $fieldname = $DB->get_records_select('sr_study_fields', 'id = ?', array($SFID), '', 'study_field_name');
    $fieldname = current($fieldname);
    echo $OUTPUT->heading('Reservation Management for ' . $programname->program_name . ' ' . $fieldname->study_field_name);
    echo '<br>';
    echo '<input type="button" id="export_to_excel" class="btn-danger pull-right" value="Export to Excel">';
    echo '</br>';
    // count records on WL of each company for a spcific study field of an active process  
    $where = "WHERE wl.sr_process_id = $processID AND wl.sr_study_fields_id = $SFID";
    $sort = "ORDER BY count(*)";
    $sql = "SELECT company_name, com.id AS ID , count(*) AS total
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_waiting_list} wl ON cr.mdl_user_id = wl.sr_company_representative_id
                {$where}
                GROUP BY company_name
                {$sort}";

    $recordsjoinWL = $DB->get_records_sql($sql);

    // count records on RL of each company for a spcific study field of an active process  
    $where = "WHERE rl.sr_process_id = $processID AND rl.sr_study_fields_id = $SFID";
    $sort = "ORDER BY count(*)";
    $sql = "SELECT company_name, com.id AS ID , count(*) AS total
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_reservation_list} AS rl ON cr.mdl_user_id = rl.sr_company_representative_id      
                {$where}
                GROUP BY company_name
                {$sort}";

    $recordsjoinRL = $DB->get_records_sql($sql);

    $c = $recordsjoinRL + $recordsjoinWL;

    $table = new html_table();
    $table->id = 'my-table';
    $table->attributes['class'] = 'table table-sm';
    $records = $DB->get_records_select('sr_study_places_status', 'sr_process_id = ?', array($processID));
    //$records = $DB->get_records_select("sr_process",'closed = 0 AND director_id = ?' ,array($user) );

    $table->head = array('Company Name', 'Total Seats Reserved', 'Total Records on Waiting List', 'Manage Reservation List');
    $table->align = array('left', 'center', 'center', 'left', 'left');


    if (count($recordsjoinRL) == count($recordsjoinWL) || count($recordsjoinRL) > count($recordsjoinWL)) {

        foreach ($recordsjoinRL as $record) {

            $row = new html_table_row();
            $row->attributes['RecordID'] = $record->id; // company ID 


            $cell1 = new html_table_cell();
            $cell1->text = $record->company_name;

            $cell2 = new html_table_cell();
            $cell2->text = $record->total;

            $cell3 = new html_table_cell();
            if (isset($recordsjoinWL[$record->company_name])) {
                $cell3->text = $recordsjoinWL[$record->company_name]->total;
            } else {
                $cell3->text = 0;
            }
            unset($recordsjoinWL[$record->company_name]);
            $row->cells  = array($cell1, $cell2, $cell3);
            $table->rowclasses[$record->id] = '';
            $table->data[]  = $row;
        }
        foreach ($recordsjoinWL as $record) {

            $row = new html_table_row();
            $row->attributes['RecordID'] = $record->id; // company ID 

            $cell1 = new html_table_cell();
            $cell1->text = $record->company_name;

            $cell2 = new html_table_cell();
            if (isset($recordsjoinRL[$record->company_name])) {
                $cell2->text = $recordsjoinRL[$record->company_name]->total;
            } else {
                $cell2->text = 0;
            }

            $cell3 = new html_table_cell();
            $cell3->text = $record->total;

            $row->cells  = array($cell1, $cell2, $cell3);
            $table->rowclasses[$record->id] = '';
            $table->data[]  = $row;
        }
    } else {
        foreach ($recordsjoinWL as $record) {

            $row = new html_table_row();
            $row->attributes['RecordID'] = $record->id; // company ID 

            $cell1 = new html_table_cell();
            $cell1->text = $record->company_name;

            $cell2 = new html_table_cell();
            if (isset($recordsjoinRL[$record->company_name])) {
                $cell2->text = $recordsjoinRL[$record->company_name]->total;
            } else {
                $cell2->text = 0;
            }
            unset($recordsjoinWL[$record->company_name]);
            $cell3 = new html_table_cell();
            $cell3->text = $record->total;

            $row->cells  = array($cell1, $cell2, $cell3);
            $table->rowclasses[$record->id] = '';
            $table->data[]  = $row;
        }
        foreach ($recordsjoinRL as $record) {

            $row = new html_table_row();
            $row->attributes['RecordID'] = $record->id; // company ID 


            $cell1 = new html_table_cell();
            $cell1->text = $record->company_name;

            $cell2 = new html_table_cell();
            $cell2->text = $record->total;

            $cell3 = new html_table_cell();
            if (isset($recordsjoinWL[$record->company_name])) {
                $cell3->text = $recordsjoinWL[$record->company_name]->total;
            } else {
                $cell3->text = 0;
            }
            $row->cells  = array($cell1, $cell2, $cell3);
            $table->rowclasses[$record->id] = '';
            $table->data[]  = $row;
        }
    }
    arsort($table->data);
    echo html_writer::table($table);
    echo '<div id="processID" hidden>' . $processID . '</div>';
    echo '<div id="SFID" hidden>' . $SFID . '</div>';
    $waitingliststatus = $DB->get_records_select('sr_process_settings', 'user_id = ?', array($user), '', 'manual_waiting_list');
    $waitingliststatus = current($waitingliststatus);
    ($waitingliststatus->manual_waiting_list == '0' || !isset($waitingliststatus->manual_waiting_list)) ? $waitingliststatus = 'off' : $waitingliststatus = 'on';
    echo '<div id="waitingliststatus" hidden>' . $waitingliststatus . '</div>';
} else {
    redirect($CFG->wwwroot);
};




?>


<script>
    $(function() {

        i = 0;
        $('#my-table tr[RecordID]').each(function() {
            i++;
            $(this).append('<td id="reservationlist' + i + '" class="" style="cursor: pointer; "><button type="button" class="btn btn-default" style="cursor: pointer;">Reservation List</button></td>');
            $('#reservationlist' + i + '').css('cursor', 'pointer').hover(
                function() {
                    $(this).addClass('active');
                },
                function() {

                    $(this).removeClass('active');
                }).click(function() {
                var companyID = $(this).parent().attr('RecordID');
                var processID = $('#processID').html();
                var SFID = $('#SFID').html();
                redirectUrl = 'ST_WL_RL_manage.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                    '<input type="hidden" name="companyID" value="' + companyID + '"></input>' +
                    '<input type="hidden" name="processID" value="' + processID + '"></input>' +
                    '<input type="hidden" name="SFID" value="' + SFID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();

            });

        });


    });
    $(function() {
        var status = document.getElementById("waitingliststatus").innerHTML;
        if (status === 'on') {
            i = 0;
            $('#my-table thead tr').append('<td class="pull-left"><b>Mange Waiting List</b></td>');
            $('#my-table tr[RecordID]').each(function() {
                i++;
                $(this).append('<td id="waitinglist' + i + '" style="cursor: pointer;"><button type="button" class="btn btn-default" style="cursor: pointer;">Waiting List</button></td>');
                $('#waitinglist' + i + '').css('cursor', 'pointer').hover(
                    function() {
                        $(this).addClass('active');
                    },
                    function() {

                        $(this).removeClass('active');
                    }).click(function() {
                    var companyID = $(this).parent().attr('RecordID');
                    var processID = $('#processID').html();
                    var SFID = $('#SFID').html();
                    redirectUrl = 'ST_WL_WL_manage.php';
                    var form = $('<form action="' + redirectUrl + '" method="post">' +
                        '<input type="hidden" name="companyID" value="' + companyID + '"></input>' +
                        '<input type="hidden" name="processID" value="' + processID + '"></input>' +
                        '<input type="hidden" name="SFID" value="' + SFID + '"></input>' + '</form>');
                    $('body').append(form);
                    $(form).submit();

                });

            });

        }
    });

    //export to excel
    $(document).ready(function() {
        $('#export_to_excel').click(function() {
            var processID = $('#processID').html();
            var SFID = $('#SFID').html();
            var form = $('<form action="../../assets/PHPFunctions/generate_RL_report.php" method="post">' +
                '<input type="hidden" name="processID" value="' + processID + '"></input>' +
                '<input type="hidden" name="SFID" value="' + SFID + '"></input>' + '/form>');
            $('body').append(form);
            $(form).submit();
        });
    });
</script>

<?PHP
echo $OUTPUT->footer();
?>