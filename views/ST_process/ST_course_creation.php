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


 /* This document should contain the course creation view 
  * Here the manager should be able to create courses for a certain ST process 
  * and have an overview (table) of all other relevant courses of that ST 
  * whenever the manager click on a record he or she will redirect to 
  * ST_course_student_assignment view 
  */

  global $DB, $PAGE, $OUTPUT, $CFG , $USER;
  require_once('../../../../config.php');

      //if (!has_capability('moodle/site:config', context_system::instance())) {
    //header('HTTP/1.1 403 Forbidden');
    //die();}

  require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $input = filter_input_array(INPUT_POST);
  } else {
    $input = filter_input_array(INPUT_GET);
  };




  $user = $USER->id;
  
  if($_SESSION['ProcessID']){
    $ProcessID = $_SESSION['ProcessID'];
    unset($_SESSION['ProcessID']);
  }else $ProcessID = $input['ProcessID'];


  $PAGE->requires->jquery();
  $PAGE->set_heading('DHBW Student Registration');
  $PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
  $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));
  $PAGE->navbar->add('Course Management',new moodle_url('/local/student_registration/views/ST_process/ST_active_process_Ma_CC.php'));
  echo $OUTPUT->header();
  echo $OUTPUT->heading('Available courses');
  //echo $OUTPUT->notification('You are now in reservation list', $notificationtype);



  echo '<div class="row">
    <div class="col-md-12 m-b-20">
      <input type="button" value="Add row" id="addRow9" class="btn btn-danger pull-right">
    </div>
  </div></br>';

  $table = new html_table();
  $table->id= 'my-table';
  $table->attributes['class'] = 'table table-sm';
 
  $records = $DB->get_records_select("sr_active_study_course",'sr_employees_id = ? AND sr_process_id = ? AND closed = 0' ,array($user, $ProcessID) );

      $table->head = array('Study course name', 'Abbreviation','Start date' , 'End date' ,'Course capacity');
      $table->align = array('left', 'left', 'left','left','left');
      

      foreach($records as $record) {

        $row = new html_table_row();
        $row->attributes['courseID'] = $record->id;

        $cell1 = new html_table_cell();
        $cell1->text = $record->study_course_name;

        $cell2 = new html_table_cell();
        $cell2->text ='<h5><b><span class="badge badge-pill badge-light">'.$record->study_course_abbreviation.'</span></b></h5>';

        $cell3 = new html_table_cell();
        
        if(new DateTime($record->start_date) < new DateTime("now")){
          $padg = '<h5><b><span class="badge badge-pill badge-success">'.$record->start_date.'</span></b></h5>';
         }else $padg = '<h5><b><span class="badge badge-pill badge-info">'.$record->start_date.' </span></b></h5>';
        $cell3->text =$padg;

        $cell4 = new html_table_cell();
        
        if(new DateTime($record->end_date) < new DateTime("now")){
         $padg2 = '<h5><b><span class="badge badge-pill badge-danger">'.$record->end_date.'</span></b></h5>';
        }else $padg2 = '<h5><b><span class="badge badge-pill badge-warning">'.$record->end_date.' </span></b></h5>';
        $cell4->text = $padg2;

        $cell5 = new html_table_cell();
        $cell5->text = $record->course_capacity;
        $row->cells  = array($cell1,$cell2,$cell3, $cell4, $cell5);

        $table->rowclasses[$record->id]= '';
         $table->data[]  = $row;

     };


  echo html_writer::table($table);




  echo $OUTPUT->footer();

  echo '<input id="process_id"  value="'.$ProcessID.'" hidden>';


  ?>



<script src="../../assets/JavaScript/jquery.tabledit.js"></script> 

<script>


$(document).ready(function() {
    $('#my-table tr[courseID]').each(function(){
        $(this).css('cursor','pointer').hover(
            function(){ 
                $(this).addClass('active'); 

            },  
            function(){      

              
            $(this).removeClass('active'); 
            }).on('click' , this, function(){ 
                var ProcessID = $('#process_id').val(); 
                var courseID = $(this).attr('courseID');                 
                redirectUrl = 'ST_course_creation_form.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                '<input type="hidden" name="process_id" value="' + ProcessID + '"></input>' +
                '<input type="hidden" name="courseID" value="' + courseID + '"></input>'+ '</form>');
                $('body').append(form);
                $(form).submit();                

            }
        );
    });
});


    $("#addRow9").on('click', function () {
      // Getting value
      var ProcessID = $('#process_id').val();
     
      $.ajax({
        type: "POST",
        url: "../../assets/ajax/add_new_course.php",
        datatype: 'html',
        data: {
          ProcessID: ProcessID
        },
        success: function (data) {
          // Add 'html' data to table
          $('#my-table tbody').html(data);
          // Update Tabledit plugin
          $('#my-table').Tabledit('update');
          $('#my-table tr[courseID]').each(function(){
          $(this).css('cursor','pointer').hover(
            function(){ 
                $(this).addClass('active'); 
            },  
            function(){      
            $(this).removeClass('active'); 
            }).on('click' , this, function(){ 
                var ProcessID = $('#process_id').val(); 
                var courseID = $(this).attr('courseID');                 
                redirectUrl = 'ST_course_creation_form.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                '<input type="hidden" name="process_id" value="' + ProcessID + '"></input>' +
                '<input type="hidden" name="courseID" value="' + courseID + '"></input>'+ '</form>');
                $('body').append(form);
                $(form).submit();                

             }
              );
              });
     
        },
        error: function () {
     
        }
      })
    });

  </script>