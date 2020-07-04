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

 /* This document should contain the an overview of current WL of a study fields for a certain process and a certain company
  * The manager should be able to move records on WL to RL of a company 
  */


  require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');


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
if(has_capability('local/student_registration:manage', $context)){

    $companyID = $input['companyID'];
    $processID = $input['processID'];
    $SFID = $input['SFID'];// study field ID

    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
    $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->navbar->add('Student Registration Process Overview',new moodle_url('/local/student_registration/views/ST_process/ST_prcess_overview.php'));

    $PAGE->requires->jquery();
    echo $OUTPUT->header();
    $companyname = $DB->get_record_select('dg_company' , 'id = ?', array($companyID), 'company_name');
    echo $OUTPUT->heading('Waiting List Management for '.$companyname->company_name);
    echo '<br>';
    echo '<div id="notification"</div>';
    // get all records for a company on WL for a certain study field of an active process
    $where = "WHERE com.id = $companyID AND wl.sr_study_fields_id = $SFID AND wl.sr_process_id = $processID";
    $sort = "";
    $sql = "SELECT wl.id, wl.first_name, wl.last_name, wl.date_of_birth, wl.private_email
            FROM {sr_waiting_list} AS wl
            INNER JOIN {dg_company_representative} AS cr ON wl.sr_company_representative_id = cr.mdl_user_id
            INNER JOIN {dg_company} com ON cr.compnay_id = com.id
            {$where}";            
            
    $recordsjoinWL = $DB->get_records_sql($sql);


    $table = new html_table();
    $table->id= 'my-table'; 
    $table->attributes['class'] = 'table table-sm ';
  
 
    $table->head = array('First Name', 'Last Name','Date of Birth', 'Email');
    $table->align = array('left', 'left', 'left','left');
        
    foreach($recordsjoinWL as $record){

        $row = new html_table_row();
        $row->attributes['RecordID'] = $record->id;// company ID 

        $cell1 = new html_table_cell();
        $cell1->text = $record->first_name;

        $cell2 = new html_table_cell();
        $cell2->text = $record->last_name;

        $cell3 = new html_table_cell();
        $cell3->text = $record->date_of_birth; 
        $cell4 = new html_table_cell();
        $cell4->text = $record->private_email;

        $row->cells  = array($cell1, $cell2, $cell3 , $cell4);
        $table->rowclasses[$record->id]= '';
        $table->data[]  = $row;

    }

    echo html_writer::table($table);
    echo $OUTPUT->footer();

}else {redirect($CFG->wwwroot);};

?>


<script>
$(function(){
$('#my-table thead tr').append('<td class="text-right" style="padding-right: 5.5%;"><b>Action</b></td>');
i = 0;
$('#my-table tr[RecordID]').each(function(){
i++;
$(this).append('<td id="waitinglist'+i+'" class="" style="cursor: pointer; "><button type="button" class="btn btn-default pull-right" style="cursor: pointer;">Move to reservation list</button></td>');
$('#waitinglist'+i+'').css('cursor','pointer').hover(
    function(){ 
        $(this).addClass('active'); 
    },  
    function(){      

    $(this).removeClass('active'); 
    }).click( function(){ 
        var RecordID = $(this).parent().attr('RecordID');                           

        $.ajax({
        type: "POST",
        url: "../../assets/ajax/move_row_wl.php",
        datatype: 'html',
        data: {
            RecordID: RecordID,
        },
        success: function (data) {
            $( "#notification" ).append( '<div id="errormsg" class="alert alert-success alert-dismissible fade show" role="alert">Record has been moved into Reservation List<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' );  
            $(this).parent().fadeOut();
        },
        error: function () {
          $( "#notification" ).append( '<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">Something went wrong!!!!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' );
        }
      });
    }
);

});
});
</script>