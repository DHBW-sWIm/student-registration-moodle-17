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


if (has_capability('local/student_registration:manage', $context)) {
    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->requires->jquery();
    $PAGE->requires->css('/local/student_registration/assets/CSS/jquery.dataTables.min.css', true);
    $PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.dataTables.min.js', true);
    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php', array('id' => $user)));
    $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->navbar->add('Reporting', new moodle_url('/local/student_registration/views/ST_Reporting/ST_reporting_main.php'));

    $PAGE->requires->jquery();
    echo $OUTPUT->header();
    echo $OUTPUT->heading('Annual Reporting');
    echo '</br>';
    $date = new  DateTime("now");
    $table = new html_table();
    $table->id = 'my-table';
    $table->attributes['class'] = 'table table-sm';
    $records = $DB->get_records_select("sr_study_programs", 'old = 0', array());

    $table->head = array('ID', 'Study Program');
    $table->align = array('center', 'center');

    $i = 0;
    foreach ($records as $record) {

        $row = new html_table_row();
        $row->attributes['RecordID'] = $record->id;

        $cell1 = new html_table_cell();
        $cell1->text = ++$i;

        $cell2 = new html_table_cell();
        $cell2->text = $record->study_program_name;

        $row->cells  = array($cell1, $cell2);
        $table->data[]  = $row;
    };

    echo html_writer::table($table);
} else {
    redirect($CFG->wwwroot);
};


?>

<script>
    $('#my-table').DataTable();
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
                var processID = $(this).parent().attr('RecordID');
                redirectUrl = 'ST_annual_SF.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                    '<input type="hidden" name="processID" value="' + processID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();

            });
        });
    });
</script>