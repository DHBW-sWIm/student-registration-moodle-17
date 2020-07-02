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

 /* This document should contain a view for managing master data of study programs 
  */

  
  require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
  global $DB, $PAGE, $OUTPUT, $CFG , $USER;
  $user = $USER->id;
  $context = context_system::instance();
  require_login();
  if(has_capability('local/student_registration:manage', $context)){

    $PAGE->requires->jquery();
    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
    $PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));

        echo $OUTPUT->header();
        echo $OUTPUT->heading('Active Study Programs'); 

        echo '<div id="notification"></div>';

echo '<div class="row">
<div class="col-md-12 m-b-20">
<input class="btn btn-link" onclick="myFunction()" id="collapselink" value="Add a new Study Program" type="button">
</div>
</div></br>';

echo '<div style="display: none;" id="myDIV">
<div class="form">
       <div class="row">
        <div class="col" >
        
  <input type="text" id="study_program_name" class="form-control" placeholder="Study Program Name">

       </div>
      <div class="col-3">
       
  <input type="date" id="valid_from" class="form-control" placeholder="Valid From">
 
      </div>
      <div class="col-3">
      
      <input type="date" id="valid_to" class="form-control" placeholder="Valid To">
      </div>
     </div>

<div>
</div>
</br>
<div class="row">
<div class="col" >
<textarea  id="description" class="form-control" placeholder="Description" rows="3"></textarea>

</div>
</div>
</br>
<button id="addnew" class="btn btn-danger ">Add new</button>   
</div>
</div>';



$table = new html_table();
$table->id= 'my-table';
$table->attributes['class'] = 'table table-sm';

$records = $DB->get_records_select("sr_study_programs",'old = 0' ,array() );

$table->head = array('Action','Study Program','Duration');
$table->align = array('center', 'center', 'center');
    
    
    foreach($records as $record) {

       $row = new html_table_row();
       $row->attributes['ProgramID'] = $record->id;

       $cell1 = new html_table_cell();
       $cell1->text = '<h5><b><span class="badge badge-pill badge-light"> Add new study fields </span></b></h5>';

       $cell2 = new html_table_cell();
       $cell2->id = "pn";
       $cell2->text =$record->study_program_name;

       $cell3 = new html_table_cell();
       if($record->valid_from > date("Y-m-d h-m-s",time())){
        $padg = '<b><span class="badge badge-pill badge-info"> Valid From:  </span></b>';
       }else $padg = '<b><span class="badge badge-pill badge-success"> Valid From:  </span></b>';
       
       if($record->valid_to > date("Y-m-d h-m-s",time())){
        $padg2 = '<b><span class="badge badge-pill badge-warning"> Valid To:  </span></b>';
       }else $padg2 = '<b><span class="badge badge-pill badge-danger"> Valid To:  </span></b>';
       
       $cell3->text =$padg.' '.date("Y-m-d",strtotime($record->valid_from)).' '.$padg2.' '.date("Y-m-d",strtotime($record->valid_to));


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
// 

// redirect when click on a row
$(function(){
    $('#my-table tr[ProgramID]').each(function(){
        $(this).css('cursor','pointer').hover(
            function(){ 
                $(this).addClass('active'); 
            },  
            function(){      


            $(this).removeClass('active'); 
            }).click( function(){ 
                var ProgramID = $(this).attr('ProgramID');
                var ProgramName = $(this).find('td#pn.cell.c1').html();                  
                redirectUrl = 'SP_SF_Master_Data.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                '<input type="hidden" name="ProgramID" value="' + ProgramID + '"></input>' +
                '<input type="hidden" name="ProgramName" value="' + ProgramName + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();                

            }
        );
    });
});


function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

    $("#addnew").on('click', function () {
      // Getting value
      var study_program_name = $('#study_program_name').val();
      var description = $('#description').val();
      var valid_from = $('#valid_from').val();
      var valid_to = $('#valid_to').val();

     
      $.ajax({
        type: "POST",
        url: "../../assets/ajax/add_new_SP.php",
        datatype: 'html',
        data: {
            study_program_name: study_program_name,
            description: description,
            valid_from: valid_from,
            valid_to: valid_to
        },
        success: function (data) {
        
          $( "#notification" ).append( '<div class="alert alert-success alert-dismissible fade show" role="alert">A new Study Program was added successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' );
          // Add 'html' data to table
          $('#my-table tbody').html(data);

          // Update Tabledit plugin
          $('#my-table').Tabledit('update');
          $(function(){
            $('#my-table tr[ProgramID]').each(function(){
                $(this).css('cursor','pointer').hover(
                    function(){ 
                        $(this).addClass('active'); 
                    },  
                    function(){      

                        
                    $(this).removeClass('active'); 
                    }).click( function(){ 
                        var ProgramID = $(this).attr('ProgramID');                 
                        redirectUrl = 'SP_SF_Master_Data.php';
                        var form = $('<form action="' + redirectUrl + '" method="post">' +
                        '<input type="hidden" name="ProgramID" value="' + ProgramID + '"></input>' + '</form>');
                        $('body').append(form);
                        $(form).submit();                

                            }
                        );
                    });
                });

        },
        error: function () {
          $( "#notification" ).append( '<div class="alert alert-danger alert-dismissible fade show" role="alert">Something went wrong!!!!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' );
        }
      })
    });

  </script>