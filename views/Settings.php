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
require_once('../../../config.php');
global $DB, $PAGE, $OUTPUT, $CFG , $USER;


$PAGE->requires->jquery();

if ($_SERVER['REQUEST_METHOD']=='POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

$user = $USER->id;


$PAGE->set_heading('DHBW Student Registration');
$PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
$PAGE->navbar->add('Student Registration',new moodle_url('/local/student_registration/views/Menu.php'));

echo $OUTPUT->header();
echo $OUTPUT->heading('Student Registration Process Settings');




echo '<form>

<div class="alert alert-secondary" role="alert">
<h4 class="alert-heading"><b>Dear User</b></h4>
<p><b>Managing waiting list is automatically done by the system  based on FIFO principle.</b><hr> You can change the
settings to manually move students from waiting list to reservation list. This change will only affect your account.
</p>
<hr>
</div>
<div class="form-check">
  <input type="checkbox" class="form-check-input" id="exampleCheck1">
  <label class="form-check-label" for="exampleCheck1">Set Manual</label>
</div>
<hr>
<button type="submit" class="btn btn-danger">Save</button><hr>
</form>';


echo $OUTPUT->footer();


?>

<script src="../assets/JavaScript/jquery.tabledit.js"></script> 

<script>
// 
$('#my_table').Tabledit({
  url: "../assets/ajax/update_tile.php",
        editButton: true,
        saveButton: true,
        restoreButton: false,
        
columns: {
  identifier: [0, 'ID'],                    
  editable: [[1, 'Title'], [2, 'Button Name'], [3, 'btn URL'], [4, 'Icon'], [5, 'Element 1'], [6, 'Element 2'], [7, 'Element 3'], [8, 'Element 4'], [9, 'Color'], [10, 'Order']]
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
     
      $.ajax({
        type: "POST",
        url: "../assets/ajax/add_new_tile.php",
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