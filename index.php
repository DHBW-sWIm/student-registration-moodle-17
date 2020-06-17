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


require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');

//  Other possible option is  flexible_table  class fount at C:\xampp\htdocs\moodle\lib\tablelib.php  

global $DB, $PAGE, $OUTPUT, $CFG , $USER;

if (isloggedin()) {
    $context = context_system::instance();
    if (has_capability('local/student_registration:view', $context) ||
        has_capability('local/student_registration:manage', $context)) {

         $PAGE->set_heading('DHBW Student Registration');
        echo $OUTPUT->header();
        echo $OUTPUT->heading(get_string('newprocess', 'local_student_registration'));


        /*
        * Dynamic table creation based on records for sr_process tables that are still open
        */
        $table = new html_table();
        $table->attributes['class'] = 'generaltable mod_index';
        $count = $DB->count_records_select("sr_process",'id',array('closed=0'));
        $records = $DB->get_records_select("sr_process",'closed=0' ,array('*') );
    
            $table->head = array('Study Program', 'Registration Start Date','Registration End Date');
            $table->align = array('left', 'left','left');

            for($i=0; $i<$count; $i++) {

                $table->data[] = array($records[$i+1]->program_name, $records[$i+1]->start_date, $records[$i+1]->end_date);
 
            }

            $datacount = $DB->count_records('user_info_data', array('fieldid' => $id));


        echo html_writer::table($table);
  
        echo $OUTPUT->single_button(new moodle_url('/local/student_registration/views/main_view.php', array('id' => $USER->id)),
            'Go to View 1', $attributes = null);
    
        echo $OUTPUT->single_button(new moodle_url('/local/student_registration/views/Menu.php', array('id' => $USER->id)),
            'Go to Menu', $attributes = null);

  
        echo $OUTPUT->footer();

    }
}

