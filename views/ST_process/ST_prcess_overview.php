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


 /* This document should contain the Student registration process overview (only for managers)
  * Here the manager should be able to see an overview of the current active ST process (table)
  * and should be able to create a new process (navigate to ST_prcess_creation)
  * 
  */


  
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');


//  Other possible option is  flexible_table  class fount at C:\xampp\htdocs\moodle\lib\tablelib.php  

global $DB, $PAGE, $OUTPUT, $CFG , $USER;


$user = $USER->id;
$context = context_system::instance();
require_login();
if(has_capability('local/student_registration:manage', $context)){

        $PAGE->set_heading('DHBW Student Registration');
        $PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php', array('id' => $user)));
        $PAGE->navbar->add('Main Menu',new moodle_url('/local/student_registration/views/Menu.php'));

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
               $row->attributes['data-href'] = 'ST_process_creation.php?#RecordID='.$records[$i+1]->id.'';

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


            
        echo $OUTPUT->single_button(new moodle_url('/local/student_registration/views/ST_process/ST_process_creation.php', array('id' => $user)),
            'Add new Process', $attributes = null);

        echo $OUTPUT->footer();

        }elseif (has_capability('local/student_registration:cr', $context)){

            $PAGE->set_heading('DHBW Student Registration');
            $PAGE->navbar->add('Main Menu',new moodle_url('/local/student_registration/views/Menu.php'));
            
            echo $OUTPUT->header();
            echo $OUTPUT->heading('Currently active process');
            /*
            * Dynamic table creation based on records for sr_process tables that are still open
            */
            $table = new html_table();
            $table->id= 'example9';
            $table->attributes['class'] = 'generaltable mod_index';
            $count = $DB->count_records_select("sr_process",'id',array('closed=0'));
            $records = $DB->get_records_select("sr_process",'closed=0' ,array('*') );
        
                $table->head = array('ID','Study Program', 'Registration Start Date','Registration End Date');
                $table->align = array('left', 'left', 'left','left');
                
    
                for($i=0; $i<$count; $i++) {
    
                   $row = new html_table_row();
                   
                   $row->attributes['data-href'] = 'ST_process_creation.php?#RecordID='.$records[$i+1]->id.'';

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
            echo $OUTPUT->single_button(new moodle_url('/local/student_registration/views/ST_process/ST_reservation_list.php', array('id' => $user)),
            'Go to reservation list', $attributes = null);

        }else {
         redirect($CFG->wwwroot);
    }

    
/*

//https://bluesatkv.github.io/jquery-tabledit/#documentation

https://www.jqueryscript.net/table/Inline-Table-Editing-jQuery-Tabledit.html

*/
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

$('#example9').Tabledit({
        editButton: true,
        saveButton: true,
columns: {
  identifier: [0, 'id'],                    
  editable: [[1, 'col1'], [2, 'col1'], [3, 'col3'], [4, 'col4']]
},
  buttons: {
      edit: {
          class: 'btn btn-sm btn-default',
          html: '<span class="fa fa-pencil"></span>',
          action: 'edit'
      },
      delete: {
          class: 'btn btn-sm btn-default',
          html: '<span class="fa fa-trash"></span>',
          action: 'delete'
      },
      save: {
          class: 'btn btn-sm btn-success',
          html: 'Save'
      },
      restore: {
          class: 'btn btn-sm btn-warning',
          html: 'Restore',
          action: 'restore'
      },
      confirm: {
          class: 'btn btn-sm btn-danger',
          html: 'Confirm'
      }
  }
});


$('#my-table2').Tabledit({
        editButton: true,
        saveButton: true,
columns: {
  identifier: [0, 'id'],                    
  editable: [[1, 'col1'], [2, 'col1'], [3, 'col3'], [4, 'col4']]
},
  buttons: {
      edit: {
          class: 'btn btn-sm btn-default',
          html: '<span class="fa fa-pencil"></span>',
          action: 'edit'
      },
      delete: {
          class: 'btn btn-sm btn-default',
          html: '<span class="fa fa-trash"></span>',
          action: 'delete'
      },
      save: {
          class: 'btn btn-sm btn-success',
          html: 'Save'
      },
      restore: {
          class: 'btn btn-sm btn-warning',
          html: 'Restore',
          action: 'restore'
      },
      confirm: {
          class: 'btn btn-sm btn-danger',
          html: 'Confirm'
      }
  }
});



$('#example9').Tabledit({
      url: 'ajax/example.php',
      columns: {
        identifier: [0, 'id'],
        editable: [[1, 'make'],[2, 'year'],[3, 'model'],[4, 'platform']]
      }
    });
     
    $("#addRow9").on('click', function () {
      // Getting value
      var ID = '3';
     
      $.ajax({
        type: "POST",
        url: "ajax/add_new.php",
        datatype: 'html',
        data: {
          ID: ID
        },
        success: function (data) {
     
          // Add 'html' data to table
          $('#example9 tbody').html(data);
     
          // Update Tabledit plugin
          $('#example9').Tabledit('update');
     
        },
        error: function () {
     
        }
      })
    });



    $('#example10').Tabledit({
      url: 'ajax/example.php',
      columns: {
        identifier: [0, 'id'],
        editable: [[1, 'make'],[2, 'year'],[3, 'model'],[4, 'platform']]
      }
    });
     
    $('#addRow10').click(function() {
      var table = $('#example9');
      var body = $('#example9 tbody');
      var nextId = body.find('tr').length + 1;
      table.prepend($('' + nextId + ''));
      table.Tabledit('update');
    });



</script>

