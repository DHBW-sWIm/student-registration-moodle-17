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

 /* This document should contain the active processes view for Manager
  * This view should be accessable only for Manager so that they see the current active
  * processes (table) whenever they click on a record the will be redirected to 
  * ST_capacity_planning.php view so that they submit the available capacity infos
  */


  require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');


//  Other possible option is  flexible_table  class fount at C:\xampp\htdocs\moodle\lib\tablelib.php  

global $DB, $PAGE, $OUTPUT, $CFG , $USER;

$user = $USER->id;
$context = context_system::instance();
require_login();
if(has_capability('local/student_registration:manage', $context)){


    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
    $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->requires->jquery();
        echo $OUTPUT->header();
        echo $OUTPUT->heading('Manage Capacity for Active Processes');
        /*
        * Dynamic table creation based on records for sr_process tables that are still open
        */



        echo'</br>';
        $table = new html_table();
        $table->id= 'my-table';
        $table->attributes['class'] = 'table table-sm';
        $records = $DB->get_records_select('sr_process','closed = 0 AND director_id = ? ' ,array($user));
        //$records = $DB->get_records_select("sr_process",'closed = 0 AND director_id = ?' ,array($user) );
    
        $table->head = array('ID','Study Program', 'Capacity Status' , 'Demand Planning','Process End Date');
        $table->align = array('center', 'center','center', 'center','center');
            
            $i=0;
            foreach($records as $record) {

                $capacity = $DB->get_records_select('sr_study_places_status','sr_process_id = ? ' ,array($record->id));
                $NotSubmitted = $DB->count_records('sr_study_places_status' , ['sr_process_id'=>$record->id , 'study_places_available' => 0]);
                $count = $DB->count_records('sr_study_fields' , ['sr_study_programs_id'=>$record->sr_study_programs_id]);

               $row = new html_table_row();
               $row->attributes['RecordID'] = $record->id;

               $cell1 = new html_table_cell();
               $cell1->text = ++$i;

               $cell2 = new html_table_cell();
               $cell2->text =$record->program_name;

               $cell3 = new html_table_cell();
               
               if(new DateTime($record->start_date) > new DateTime("now")){
                $padg = '<b><span class="badge badge-pill badge-info"> Starts:</span></b>';
               }else $padg = '<b><span class="badge badge-pill badge-success"> Started: </span></b>';
               
               if(new DateTime($record->start_date_for_a) < new DateTime("now")){
                $padg2 = '<b><span class="badge badge-pill badge-danger"> Ended: </span></b>';
               }else $padg2 = '<b><span class="badge badge-pill badge-warning"> Ends:</span></b>';

               $cell3->text =$padg.' '.$record->start_date.' '.$padg2.' '.$record->start_date_for_a;

               $cell4 = new html_table_cell();
               
               if(new DateTime($record->end_date) < new DateTime("now")){
                $padg3 = '<b><span class="badge badge-pill badge-danger"> Ended: '.$record->end_date.' </span></b>';
               }else  $padg3 = '<b><span class="badge badge-pill badge-warning"> Ends: '.$record->end_date.' </span></b>';

               $cell4->text = $padg3;

               if($capacity){

                if($NotSubmitted == 0){
                    $padg4 =  '<b><span class="badge badge-pill badge-success">Submitted</span></b>';
                } else {$padg4 =  '<b><span class="badge badge-pill badge-warning">Not Complete</span></b>';}

               }else  $padg4 =  '<b><span class="badge badge-pill badge-danger">Not Submitted</span></b>';

              



               $cell5 = new html_table_cell();
               $cell5->text = $padg4 ; 

               $row->cells  = array($cell1,$cell2,$cell5,$cell3, $cell4);
               $table->rowclasses[$record->id]= '';
               $table->data[]  = $row;
 
            };

        echo html_writer::table($table);


        
        echo $OUTPUT->footer();
    }else {redirect($CFG->wwwroot);};




    ?>

<script src="../../assets/JavaScript/jquery.tabledit.js"></script> 

<script>

// redirect when click on a row
$(function(){
    $('#my-table tr[RecordID]').each(function(){
        $(this).css('cursor','pointer').hover(
            function(){ 
                $(this).addClass('active'); 
            },  
            function(){      


            $(this).removeClass('active'); 
            }).click( function(){ 
                var ID = $(this).attr('RecordID');                 
                redirectUrl = 'ST_capacity_planning.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                '<input type="hidden" name="ID" value="' + ID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();

            }
        );
    });
});

</script>

