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
  * ST_reservation_list.php view so that they enter there studnets infos
  * and reservie seats for a certain ST process
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
    $PAGE->requires->jquery();
        echo $OUTPUT->header();
        echo $OUTPUT->heading('Currently active process');
        /*
        * Dynamic table creation based on records for sr_process tables that are still open
        */

        echo'</br>';
        $table = new html_table();
        $table->id= 'my-table';
        $table->attributes['class'] = 'table table-sm';
        $parnterID = $DB->get_records_select('dg_company_representative' , 'mdl_user_id = ?' , array($user) ,''  , 'compnay_id');
        foreach($parnterID as $partner=>$val){
            $PID = $val->compnay_id;
        }
        $partnerclass = $DB->get_records_select('dg_company' , 'id = ? ' , array((int)$PID) , '' , 'classification');

        foreach($partnerclass as $class=>$val){
            $classification = $val->classification;
        }

        if($classification === 'A'){
            $startDate = 'start_date_for_a';
        }else {$startDate = 'start_date_for_b';}

        $records = $DB->get_records_select("sr_process",'closed = ? AND '.$startDate.' < CURRENT_TIMESTAMP' ,array('0') );
    
            $table->head = array('Action','Study Program', 'Registration Period');
            $table->align = array('center', 'center', 'center');
            

      
            foreach($records as $record) {


               $row = new html_table_row();
               $row->attributes['RecordID'] = $record->id;

               $cell1 = new html_table_cell();
               $cell1->text = '<h5><b><span class="badge badge-pill badge-light">Register Students</span></b></h5>';

               $cell2 = new html_table_cell();
               $cell2->id = 'program_name';
               $cell2->text =$record->program_name;
               
               if(new DateTime($record->end_date) < new DateTime("now")){
                $padg2 = '<b><span class="badge badge-pill badge-danger"> Ended: </span></b>';
               }else $padg2 = '<b><span class="badge badge-pill badge-warning"> Ends:</span></b>';
               $padg1 = '<b><span class="badge badge-pill badge-success"> Started:</span></b>';

               $cell3 = new html_table_cell();
               $cell3->text = $padg1 .' '.$record->$startDate .' '.$padg2 .' '. $record->end_date;



               $row->cells  = array($cell1,$cell2,$cell3);
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
                var program_name = $(this).find("#program_name").html();    
                redirectUrl = 'ST_field_registration.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                '<input type="hidden" name="ID" value="' + ID + '"></input>' + 
                '<input type="hidden" name="program_name" value="' + program_name + '"></input>'+ '</form>');
                $('body').append(form);
                $(form).submit();

            }
        );
    });
});

</script>

