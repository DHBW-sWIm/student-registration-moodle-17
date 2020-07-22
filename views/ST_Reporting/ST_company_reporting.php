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
    $PAGE->navbar->add('Current Processes', new moodle_url('/local/student_registration/views/ST_Reporting/ST_com_active_pro.php'));

    $PAGE->requires->jquery();
    echo $OUTPUT->header();
    $RecordID = $input['RecordID'];
    $sps = $DB->get_record_select('sr_study_places_status', 'id = ?', array($RecordID));
    $FID = $sps->sr_study_fields_id;
    $SFieldname = $DB->get_record_select('sr_study_fields', 'id = ? ', array($FID))->study_field_name;
    echo $OUTPUT->heading('Company Reporting for ' . $SFieldname);
    echo '</br>';
    echo '<input type="button" id="export_to_excel" class="btn-danger pull-right" value="Export to Excel">';
    echo '</br>';
    // count records on WL of each company for a spcific study field of an active process  
    $where = "WHERE wl.sr_process_id = $sps->sr_process_id AND wl.sr_study_fields_id = $FID";
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
    $where = "WHERE rl.sr_process_id = $sps->sr_process_id AND rl.sr_study_fields_id = $FID";
    $sort = "ORDER BY count(*)";
    $sql = "SELECT company_name, com.id AS ID , count(*) AS total
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_reservation_list} AS rl ON cr.mdl_user_id = rl.sr_company_representative_id      
                {$where}
                GROUP BY company_name
                {$sort}";

    $recordsjoinRL = $DB->get_records_sql($sql);

    // count records on RL of each company for a spcific study field of an active process where the contract is signed or sent 
    $where = "WHERE rl.sr_process_id = $sps->sr_process_id AND rl.sr_study_fields_id = $FID";
    $sort = "ORDER BY count(*)";
    $sql = "SELECT company_name, com.id AS ID , count(contract_status) AS ConfirmedSeats
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_reservation_list} AS rl ON cr.mdl_user_id = rl.sr_company_representative_id AND contract_status = 'Signed' OR contract_status = 'Sent'      
                {$where}
                GROUP BY company_name
                {$sort}";
    $recordsjoinRLSeatsConfirmed = $DB->get_records_sql($sql);

    // count records of the demand for each company for a spcific study field of an active process  
    $where = "WHERE cp.sr_process_id = $sps->sr_process_id AND cp.sr_study_fields_id = $FID";
    $sort = "ORDER BY count(*)";
    $sql = "SELECT company_name, com.id AS ID , sum(initial_demand) AS totaldemand
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_capacity_planning} cp ON cp.sr_company_representative_id = cr.mdl_user_id
                {$where}
                GROUP BY company_name
                {$sort}";

    $companydemand = $DB->get_records_sql($sql);

    $table = new html_table();
    $table->id = 'my-table';
    $table->attributes['class'] = 'table table-sm';
    $records = $DB->get_records_select('sr_study_places_status', 'sr_process_id = ?', array($sps->sr_process_id));
    //$records = $DB->get_records_select("sr_process",'closed = 0 AND director_id = ?' ,array($user) );

    $table->head = array('Company Name', 'Total Seats Demanded', 'Total Seats Reserved', 'Confirmed Seats', 'Total Records on Waiting List');
    $table->align = array('left', 'center', 'center', 'center', 'center');


    if (count($recordsjoinRL) == count($recordsjoinWL) || count($recordsjoinRL) > count($recordsjoinWL)) {

        foreach ($recordsjoinRL as $record) {

            $row = new html_table_row();
            $row->attributes['RecordID'] = $record->id; // company ID 

            $cell0 = new html_table_cell();
            if (isset($companydemand[$record->company_name])) {
                $cell0->text = $companydemand[$record->company_name]->totaldemand;
            } else {
                $cell0->text = 0;
            }

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

            $cell4 = new html_table_cell();
            if (isset($recordsjoinRLSeatsConfirmed[$record->company_name])) {
                $cell4->text = $recordsjoinRLSeatsConfirmed[$record->company_name]->confirmedseats;
            } else {
                $cell4->text = 0;
            }


            $row->cells  = array($cell1, $cell0,  $cell2, $cell4, $cell3);
            $table->rowclasses[$record->id] = '';
            $table->data[]  = $row;
        }
        foreach ($recordsjoinWL as $record) {

            $row = new html_table_row();
            $row->attributes['RecordID'] = $record->id; // company ID 
            $cell0 = new html_table_cell();
            if (isset($companydemand[$record->company_name])) {
                $cell0->text = $companydemand[$record->company_name]->totaldemand;
            } else {
                $cell0->text = 0;
            }
            $cell1 = new html_table_cell();
            $cell1->text = $record->company_name;

            if (isset($recordsjoinRL[$record->company_name])) {
                $cell2->text = $recordsjoinRL[$record->company_name]->total;
            } else {
                $cell2->text = 0;
            }

            $cell3 = new html_table_cell();
            if (isset($recordsjoinWL[$record->company_name])) {
                $cell3->text = $recordsjoinWL[$record->company_name]->total;
            } else {
                $cell3->text = 0;
            }

            unset($recordsjoinWL[$record->company_name]);

            $cell4 = new html_table_cell();
            if (isset($recordsjoinRLSeatsConfirmed[$record->company_name])) {
                $cell4->text = $recordsjoinRLSeatsConfirmed[$record->company_name]->confirmedseats;
            } else {
                $cell4->text = 0;
            }

            $row->cells  = array($cell1, $cell0, $cell2, $cell4, $cell3);
            $table->rowclasses[$record->id] = '';
            $table->data[]  = $row;
        }
    } else {
        foreach ($recordsjoinWL as $record) {

            $row = new html_table_row();
            $row->attributes['RecordID'] = $record->id; // company ID 
            $cell0 = new html_table_cell();
            if (isset($companydemand[$record->company_name])) {
                $cell0->text = $companydemand[$record->company_name]->totaldemand;
            } else {
                $cell0->text = 0;
            }
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

            $cell4 = new html_table_cell();
            if (isset($recordsjoinRLSeatsConfirmed[$record->company_name])) {
                $cell4->text = $recordsjoinRLSeatsConfirmed[$record->company_name]->confirmedseats;
            } else {
                $cell4->text = 0;
            }

            $row->cells  = array($cell1, $cell0, $cell2, $cell4, $cell3);
            $table->rowclasses[$record->id] = '';
            $table->data[]  = $row;
        }
        foreach ($recordsjoinRL as $record) {

            $row = new html_table_row();
            $row->attributes['RecordID'] = $record->id; // company ID 

            $cell0 = new html_table_cell();
            if (isset($companydemand[$record->company_name])) {
                $cell0->text = $companydemand[$record->company_name]->totaldemand;
            } else {
                $cell0->text = 0;
            }
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
            $cell4 = new html_table_cell();
            if (isset($recordsjoinRLSeatsConfirmed[$record->company_name])) {
                $cell4->text = $recordsjoinRLSeatsConfirmed[$record->company_name]->confirmedseats;
            } else {
                $cell4->text = 0;
            }

            $row->cells  = array($cell1, $cell0, $cell2, $cell4, $cell3);
            $table->rowclasses[$record->id] = '';
            $table->data[]  = $row;
        }
    }
    arsort($table->data);
    echo html_writer::table($table);
    $report_info = $DB->get_record_select('sr_process', 'id = ?', array($sps->sr_process_id));
    echo '<div id="processID" hidden>' . $sps->sr_process_id . '</div>';
    echo '<div id="SFID" hidden>' . $FID . '</div>';
    echo '<div id="report_company" hidden>Company Report for ' . $report_info->program_name . ' ' . $SFieldname . '-' . $report_info->semester . ' ' . (new DateTime($report_info->end_date))->format('Y') . '</div>';
    echo $OUTPUT->footer();
} else {
    redirect($CFG->wwwroot);
};

?>
<script>
    //export to excel
    $(document).ready(function() {
        $('#export_to_excel').click(function() {
            var table_content = '<table>';
            table_content += '<thead><tr>';
            $('#my-table thead tr').children().each(function() {
                table_content += '<th>' + $(this).text() + '</th>'
            });
            table_content += '</tr></thead>';
            table_content += '<tbody>';
            $('#my-table tbody').children().each(function() {
                table_content += '<tr>'
                $(this).children().each(function() {
                    table_content += '<td>' + $(this).text() + '</td>'
                });
                table_content += '</tr>'
            });
            table_content += '</tbody>';
            table_content += '</table>';
            var description = '<h3>' + $('#report_company').text() + '</h3>'
            var form = $('<form action="../../assets/PHPFunctions/export_to_excel.php" method="post">' +
                ' <input type="hidden" value= "' + description + '"  name="description"/>' +
                ' <input type="hidden" value= "' + table_content + '"  name="file_content"/></form>');
            $('body').append(form);
            $(form).submit();
        });
    });
</script>