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





  //if (!has_capability('moodle/site:config', context_system::instance())) {
    //header('HTTP/1.1 403 Forbidden');
    //die();}

//  Other possible option is  flexible_table  class fount at C:\xampp\htdocs\moodle\lib\tablelib.php  

global $DB, $PAGE, $OUTPUT, $CFG , $USER;

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $input = filter_input_array(INPUT_POST);
  } else {
    $input = filter_input_array(INPUT_GET);
  };
  

$user = $USER->id;
$context = context_system::instance();
require_login();
if(has_capability('local/student_registration:cr', $context)){


    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->navbar->add('Active Processes',new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_ST.php'));
    $PAGE->requires->jquery();
        echo $OUTPUT->header();
        echo $OUTPUT->heading('Available Study Fields');
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





        $records = $DB->get_records_select("sr_study_places_status",'sr_process_id = ? ' ,array((int)$input['ID']) );

        if($records){

        
    
            $table->head = array('Action','Study Field', 'Total Seats' , 'Available Seats');
            $table->align = array('center', 'left', 'center', 'center');
            

        
            foreach($records as $record) {


               $row = new html_table_row();
               $row->attributes['DSRecordID'] = $record->id;

               $cell1 = new html_table_cell();


               $cell2 = new html_table_cell();
               $studyField = $DB->get_records_select('sr_study_fields' , 'id = ?' , array($record->sr_study_fields_id));
               foreach($studyField as $name){
                    $studyFieldName = $name->study_field_name;
               }
               $cell2->text =$studyFieldName;

               if(isset($record->current_demand)){}else  $record->current_demand = 0;

               $available = $record->study_places_available -  $record->current_demand ;



               $padg1 = '<b><span class="badge badge-pill badge-success"> '.$record->study_places_available.' </span></b>';
            
               if($record->study_places_available != 0){
               if($available > 0){
                $padg2 = '<b><span class="badge badge-pill badge-info"> '.$available.' </span></b>';
                $cell1->text = '<h5><b><span class="badge badge-pill badge-info">Reservation Possible</span></b></h5>';
               }else{ $padg2 = '<b><span class="badge badge-pill badge-danger">No Seats left</span></b>';
                $cell1->text = '<h5><b><span class="badge badge-pill badge-warning">Wating list Possible</span></b></h5>';
               }
               }else {$padg2 = '<b><span class="badge badge-pill badge-danger">No Seats availabe</span></b>';
                $cell1->text = '<h5><b><span class="badge badge-pill badge-warning">Capacity has not been set yet</span></b></h5>';}


               $cell3 = new html_table_cell();
               $cell3->text = $padg1;

               $cell4 = new html_table_cell();
               $cell4->text = $padg2;



               $row->cells  = array($cell1,$cell2,$cell3,$cell4);
               $table->rowclasses[$record->id]= '';
               $table->data[]  = $row;
 
            };


        echo html_writer::table($table);

        echo '<div id="ProcessID"  hidden>'.(int)$input['ID'].'</div>';

    }else {
        echo '<div class="alert alert-warning" role="alert">
        <h4 class="alert-heading"><b>Dear User</b></h4>
        <p>The capacity for student registration of <b>'.$input['program_name'].'</b> has not been set yet. This will be update soon!!!</p>
        <hr>
        <p class="mb-0">As soon as the DHBW administration sets the capacity, you will be able to see the available study places. <b>Thanks for your patience.</b></p>
      </div>';
    }


        
        echo $OUTPUT->footer();
    }else {redirect($CFG->wwwroot);};



?>

<script src="../../assets/JavaScript/jquery.tabledit.js"></script> 
<script>

// redirect when click on a row
$(function(){
    $('#my-table tr[DSRecordID]').each(function(){
        $(this).css('cursor','pointer').hover(
            function(){ 
                $(this).addClass('active'); 
            },  
            function(){      


            $(this).removeClass('active'); 
            }).click( function(){ 
                var DSRecordID = $(this).attr('DSRecordID');    
                var ProcessID = $('#ProcessID').html();
                redirectUrl = 'ST_reservation_list.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                '<input type="hidden" name="DSRecordID" value="' + DSRecordID + '"></input>' +
                '<input type="hidden" name="ProcessID" value="' + ProcessID + '"></input>'+'</form>');
                $('body').append(form);
                $(form).submit();

            }
        );
    });
});

</script>

