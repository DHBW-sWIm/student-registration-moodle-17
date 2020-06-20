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

 /* This document should contain the active processes view for Company Representatives
  * This view should be accessable only for CRs so that they see the current active
  * processes (table) whenever they click on a record the will be redirected to 
  * ST_demand_submission.php view so that they enter there demand for a certain ST process
  */


  require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');


//  Other possible option is  flexible_table  class fount at C:\xampp\htdocs\moodle\lib\tablelib.php  

global $DB, $PAGE, $OUTPUT, $CFG , $USER;


$user = $USER->id;
$context = context_system::instance();
require_login();
if(has_capability('local/student_registration:cr', $context)){


    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));

        echo $OUTPUT->header();
        echo $OUTPUT->heading('Currently active process');
        /*
        * Dynamic table creation based on records for sr_process tables that are still open
        */

        
        $table = new html_table();
        $table->id= 'my-table';
        $table->attributes['class'] = 'generaltable mod_index';
        $count = $DB->count_records_select("sr_process",'id',array('closed=0'));
        $records = $DB->get_records_select("sr_process",'closed=0' ,array('*') );
    
            $table->head = array('ID','Study Program', 'Registration Start Date','Registration End Date');
            $table->align = array('left', 'left', 'left','left');
            

            for($i=0; $i<$count; $i++) {

               $row = new html_table_row();
               $row->attributes['data-href'] = 'ST_demand_submission.php?#RecordID='.$records[$i+1]->id.'';
 
               $cell1 = new html_table_cell();
               $cell1->text = $records[$i+1]->id;

               $cell2 = new html_table_cell();
               $cell2->text =$records[$i+1]->program_name;

               $cell3 = new html_table_cell();
               $cell3->text =$records[$i+1]->start_date;

               $cell4 = new html_table_cell();
               $cell4->text = $records[$i+1]->end_date;

               $row->cells  = array($cell1,$cell2,$cell3, $cell4);

                $table->rowclasses[$i]= '';
                $table->data[]  = $row;
 
            }


        echo html_writer::table($table);


        
        echo $OUTPUT->footer();
    }else redirect($CFG->wwwroot);




    ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="../../assets/JavaScript/jquery.tabledit.js"></script> 
<script>

// redirect when click on a row
$(function(){
    $('#my-table tr[data-href]').each(function(){
        $(this).css('cursor','pointer').hover(
            function(){ 
                $(this).addClass('active'); 
            },  
            function(){ 
                $(this).removeClass('active'); 
            }).click( function(){ 
                document.location = $(this).attr('data-href'); 
            }
        );
    });
});
</script>