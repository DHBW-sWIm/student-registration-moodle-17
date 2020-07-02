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


 /*
  * Setting for ST plugin 
  * idea: configuration for manual or automatice waiting list record transfers
  * configuration of tiles of the management dashboard
 */

require_once('../../config.php');
global $DB, $PAGE, $OUTPUT, $CFG , $USER;




if ($_SERVER['REQUEST_METHOD']=='POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

$user = $USER->id;


$PAGE->set_heading('DHBW Student Registration');
$PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
$PAGE->requires->jquery();

echo $OUTPUT->header();
echo $OUTPUT->heading('General settings');
echo '<div id="notification"></div>';

echo '<div class="row">
<div class="col-12 m-b-20">
  <input type="button" value="Add row" id="addRow9" class="btn btn-danger pull-right">
</div>
</div>';


$table = new html_table();
$table->id= 'my_table';
$table->attributes['class'] = 'table table-sm';


$records = $DB->get_records_select("sr_management_tiles", 'user_id = ?', array($user));

  $table->head = array( 'ID','Plugin Name' , 'Tile');
  $table->align = array('center', 'center', 'center');
  $i= 0;
  foreach($records as $record) {
    $i++;
    $row = new html_table_row();
    $row->attributes['rowID'] = $record->id;

    $cell1 = new html_table_cell();
    $cell1->text = $record->plugin;

    $cell2 = new html_table_cell();
    $cell2->text =$record->title;

    $cell3 = new html_table_cell();
    $cell3->text =$i;

    $row->cells  = array($cell3, $cell1,$cell2);

    $table->rowclasses[$record->id]= '';
     $table->data[]  = $row;

 };


echo '<input id="myInput" type="text" />';
echo html_writer::table($table);



echo $OUTPUT->footer();


?>

<script src="assets/JavaScript/jquery.tabledit.js"></script> 

<script>
// 
$(document).ready(function() {
    $('#my_table tr[rowID]').each(function(){
        $(this).css('cursor','pointer').hover(
            function(){ 
                $(this).addClass('active'); 
            },  
            function(){      
            $(this).removeClass('active'); 
            }).on('click' , this, function(){ 
                var rowID = $(this).attr('rowID');                 
                redirectUrl = 'assets/ajax/update_tile.php';
                var form = $('<form action="dashboard_tile_edit.php" method="post">' +
                '<input type="hidden" name="rowID" value="' + rowID + '"></input>'+ '</form>');
                $('body').append(form);
                $(form).submit();                

            }
        );
    });
});

function filterTable(event) {
    var filter = event.target.value.toUpperCase();
    var rows = document.querySelector("#my_table tbody").rows;

    for (var i = 0; i < rows.length; i++) {
        var secondCol = rows[i].cells[1].textContent.toUpperCase();
        if (secondCol.indexOf(filter) > -1) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }      
    }
}

document.querySelector('#myInput').addEventListener('keyup', filterTable, false);

    $("#addRow9").on('click', function () {
      // Getting value

     
      $.ajax({
        type: "POST",
        url: "assets/ajax/add_new_tile.php",
        datatype: 'html',
        data: {

        },
        success: function (data) {
       
          $( "#notification" ).append( '<div id"statusnotification" class="alert alert-success alert-dismissible fade show" role="alert">A new Tile was added successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>' );

          // Add 'html' data to table
          $('#my_table tbody').html(data);

          // Update Tabledit plugin
          $('#my_table').Tabledit('update');
          $(document).ready(function() {
          $('#my_table tr[rowID]').each(function(){
              $(this).css('cursor','pointer').hover(
                  function(){ 
                      $(this).addClass('active'); 
                  },  
                  function(){      
                  $(this).removeClass('active'); 
                  }).on('click' , this, function(){ 
                      var rowID = $(this).attr('rowID');                 
               
                      var form = $('<form action="dashboard_tile_edit.php" method="post">' +
                      '<input type="hidden" name="rowID" value="' + rowID + '"></input>'+ '</form>');
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