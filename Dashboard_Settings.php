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


echo $OUTPUT->header();
echo $OUTPUT->heading('General settings');
echo '</br>';

$pluginlist = $DB->get_records_select('sr_management_tiles' , '' ,array() ,'' , 'distinct plugin');


echo '<div class="row">
<div class="col-md-12 m-b-20">
<input class="btn btn-link" onclick="myFunction()" id="collapselink" value="Add a new tile" type="button">
</div>
</div>';

echo '<div style="display: none;" id="myDIV">
<div class="form">
       <div class="row">
        <div class="col" >
        
  <input type="text" id="newtitle" class="form-control" placeholder="Title">
  <input type="text" id="btnurl" class="form-control" placeholder="Button URL">
  <input type="text" id="element1" class="form-control" placeholder="Element 1">
  <input type="text" id="element3" class="form-control" placeholder="Element 3">
  <input type="text" id="btncolor" class="form-control" placeholder="Button Color e.g. danger">
        
       </div>
      <div class="col">
       

  <input type="text" id="btnname" class="form-control" placeholder="Button Name">
  <input type="text" id="btnicon" class="form-control" placeholder="Button Icon e.g. fa fa-user">
  <input type="text" id="element2" class="form-control" placeholder="Element 2">
  <input type="text" id="element4" class="form-control" placeholder="Element 4">
  <div class ="row justify-content-center">
  <input type="number" id="tileorder" class="form-control col-5" placeholder="Order">
  <input type="text" list="pluginnames" id="pluginname" class="form-control col-5" placeholder="Plugin">
      </div>
      </div>
     </div>
<button id="addnewtile" class="btn btn-danger ">Add</button>   
<div>
</div>
</div>
</div>';


echo '
<datalist id="pluginnames">';
foreach($pluginlist as $plugin=>$val){
  echo '<option value="'.$plugin.'">';
}
echo'</datalist>';

$table = new html_table();
$table->id= 'my_table';
$table->attributes['class'] = 'table table-sm';
$table->attributes['style'] = 'font-size: 11px !important;';

$records = $DB->get_records_select("sr_management_tiles", '');

  $table->head = array('ID','Plugin Name' , 'Title', 'Button Name','btn URL' , 'Icon' ,'Element 1','Element 2','Element 3','Element 4','Color','Order');
  $table->align = array('left', 'left', 'left','left','left','left','left','left','left','left','left');
  
  foreach($records as $record){

    $table->data[] = array($record->id  , $record->plugin , $record->title , $record->button_name ,$record->button_url ,$record->button_icon , $record->list_element_1, $record->list_element_2, $record->list_element_3, $record->list_element_4, $record->color, $record->tile_order);

  };


echo html_writer::table($table);



echo $OUTPUT->footer();






?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="assets/JavaScript/jquery.tabledit.js"></script> 

<script>
// 
$('#my_table').Tabledit({
  url: "assets/ajax/update_tile.php",
        editButton: true,
        saveButton: true,
        restoreButton: false,
        
columns: {
  identifier: [0, 'ID'],                    
  editable: [[1, 'Plugin'] ,[2, 'Title'], [3, 'Button Name'], [4, 'btn URL'], [5, 'Icon'], [6, 'Element 1'], [7, 'Element 2'], [8, 'Element 3'], [9, 'Element 4'], [10, 'Color'], [11, 'Order']]
}
});

function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}


     
    $("#addnewtile").on('click', function () {
      // Getting value
      var newtitle = $('#newtitle').val();
      var btnname = $('#btnname').val();
      var btnurl = $('#btnurl').val();
      var btnicon = $('#btnicon').val();
      var btncolor = $('#btncolor').val();
      var tileorder = $('#tileorder').val();
      var element1 = $('#element1').val();
      var element2 = $('#element2').val();
      var element3 = $('#element3').val();
      var element4 = $('#element4').val();
      var plugin = $('#pluginname').val();; 
     
      $.ajax({
        type: "POST",
        url: "assets/ajax/add_new_tile.php",
        datatype: 'html',
        data: {
            newtitle: newtitle,
            btnname: btnname,
            btnurl: btnurl,
            btnicon: btnicon,
            btncolor: btncolor,
            tileorder: tileorder,
            element1: element1,
            element2: element2,
            element3: element3,
            element4: element4,
            plugin: plugin
        },
        success: function (data) {
     
          // Add 'html' data to table
          $('#my_table tbody').html(data);
     
          // Update Tabledit plugin
          $('#my_table').Tabledit('update');
     
        },
        error: function () {
     
        }
      })
    });

  </script>