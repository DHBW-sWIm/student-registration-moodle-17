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
require_once(dirname(__DIR__) . '../../assets/PHPClasses/UI.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};

if (has_capability('local/student_registration:manage', $context)) {
    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->requires->jquery();
    $PAGE->requires->css('/local/student_registration/assets/CSS/jquery.dataTables.min.css', true);
    $PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.dataTables.min.js', true);
    $PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.tabledit.js', true);

    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php', array('id' => $user)));
    $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->navbar->add('Reporting', new moodle_url('/local/student_registration/views/ST_Reporting/ST_reporting_main.php'));
    echo $OUTPUT->header();
    echo $OUTPUT->heading('Annual Reporting');
    echo '<div id="notification"></div>';
    echo '</br>';
    echo '<input type="button" id="annual_report" class="btn-danger pull-right" value="View Report">';
    echo '</br>';
    echo '</br>';
    $date = new  DateTime("now");
    $table = new html_table();
    $table->id = 'my-table';

    $records = $DB->get_records_select("sr_process", 'sr_study_programs_id = ?', array($input['processID']));

    $table->head = array('ID', 'Select', 'Study Program', 'Select', 'Semester', 'Year');
    $table->align = array('center', 'center', 'center', 'center', 'center', 'center');

    $i = 0;
    foreach ($records as $record) {

        $row = new html_table_row();
        $row->attributes['RecordID'] = $record->id;

        $cell1 = new html_table_cell();
        $cell1->text = ++$i;

        $cell5 = new html_table_cell();
        $cell5->text = '<input type="checkbox">';

        $cell2 = new html_table_cell();
        $cell2->text = $record->program_name;

        $cell6 = new html_table_cell();


        $cell3 = new html_table_cell();
        $cell3->text = $record->semester;

        $cell4 = new html_table_cell();
        $cell4->text = (new DateTime($record->end_date))->format('Y');

        $row->cells  = array($cell1, $cell5, $cell2, $cell6, $cell3, $cell4);
        $table->data[]  = $row;
    };

    echo html_writer::table($table);
} else {
    redirect($CFG->wwwroot);
};


?>

<script>
    jQuery('#my-table').Tabledit({
        url: "../../assets/ajax/set_session.php",
        editButton: true,
        saveButton: false,
        restoreButton: false,
        deleteButton: false,

        columns: {
            identifier: [0, 'id', 'hidden'],
            editable: [
                [3, 'Select', 'select', <?PHP
                                        $STFIELDS = $DB->get_records_select('sr_study_fields', 'sr_study_programs_id = ? AND old = 0', array($input['processID']));
                                        echo '\'{';
                                        $count = count($STFIELDS);
                                        $i = 1;
                                        $coma = ',';
                                        echo '"0":"All",';
                                        foreach ($STFIELDS as $STF) {

                                            ($i == $count) ? $coma  = '' : $i++;

                                            echo '"' . $STF->id . '": "' . $STF->study_field_name . '"' . $coma;
                                        }

                                        echo '}\''; ?>]
            ]
        }
    });

    dataTable = jQuery("#my-table").DataTable();
    // redirect when click on a row
    jQuery(function() {
        jQuery("#my- tr[RecordID] td").each(function() {
            jQuery(this)
                .css("cursor", "pointer")
                .hover(
                    function() {
                        jQuery(this).addClass("active");
                    },
                    function() {
                        jQuery(this).removeClass("active");
                    }
                )
                .click(function() {
                    var processID = jQuery(this).parent().attr("RecordID");
                    redirectUrl = "ST_annual_SF.php";
                    var form = jQuery(
                        '<form action="' +
                        redirectUrl +
                        '" method="post">' +
                        '<input type="hidden" name="processID" value="' +
                        processID +
                        '"></input>' +
                        "</form>"
                    );
                    jQuery("body").append(form);
                    jQuery(form).submit();
                });
        });
    });
    jQuery("#annual_report").on("click", function() {
        // Getting value
        var selectedreprot = [];
        jQuery("#my-table td.cell.c1 > input[type=checkbox]").each(function() {
            if (this.checked) {
                var process = [
                    jQuery(this).parent().parent().attr("RecordID"),
                    jQuery(this)
                    .parent()
                    .parent()
                    .find("td.cell.c3.tabledit-view-mode > select option:selected")
                    .val(),
                ];
                selectedreprot.push(process);
            }
        });
        JSON.stringify(selectedreprot);
        jQuery.ajax({
            type: "POST",
            url: "../../assets/ajax/create_annual_report.php",
            datatype: "html",
            data: {
                selectedreprot: selectedreprot,
            },
            success: function(data) {
                //dataTable.clear();
                jQuery("#my-table").empty();
                jQuery("#annual_report").remove();
                jQuery("#my-table").html(data);
                dataTable.clear();
                dataTable.destroy();
                jQuery("#my-table").DataTable();
            },
            error: function() {
                jQuery("#notification").append(
                    '<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">Something went wrong! Please contact the system administrator if the error occures again<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
                );
            },
        });
    });
</script>