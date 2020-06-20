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


 /* This document should contain the Seat reservation view for Company representatives (CR) 
  * Here the company representative should be able to add records (each record is a reserved seat)
  * to the database based on the available seats for a certain ST process 
  * CR must be able to adjust the records and enter student names and contract status 
  * idea: table for adding records and editing them (AJAX and JQuery) live editable tables
  * in case all seats were reserved, CR must be prevented from adding new records and should 
  * be redirected to waiting list view 
  */


  global $DB, $PAGE, $OUTPUT, $CFG , $USER;
  require_once('../../../../config.php');
  $notificationtype = \core\output\notification::NOTIFY_INFO;
  $PAGE->set_heading('DHBW Student Registration');
  echo $OUTPUT->header();
  echo $OUTPUT->heading('Reservation list');
  echo $OUTPUT->notification('You are now in reservation list', $notificationtype);



  echo '

  <div class="row">
    <div class="col-md-12 m-b-20">
      <input type="button" value="Add row" id="addRow9" class="btn btn-info pull-right">
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="table-responsive">
        <table class="table table-striped table-bordered" id="example9">
          <thead>
          <tr >
            <th>#</th>
            <th>Make</th>
            <th>Model</th>
            <th>Year</th>
            <th>Platform</th>
          </tr>
          </thead>
          <tbody>
          <tr data-href="index.php">
            <td>1</td>
            <td>BMW</td>
            <td>5-Series</td>
            <td>2018</td>
            <td>G30</td>
          </tr>
          <tr>
            <td>2</td>
            <td>BMW</td>
            <td>6-Series, M6</td>
            <td>2009</td>
            <td>F12/F13</td>
          </tr>
          <tr>
            <td>3</td>
            <td>Ford</td>
            <td>Mustang</td>
            <td>2015</td>
            <td>(6th gen)</td>
          </tr>
          <tr>
            <td>4</td>
            <td>Ford</td>
            <td>Explorer</td>
            <td>2017</td>
            <td>(5th gen)</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>';



  echo $OUTPUT->footer();

  ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="../../assets/JavaScript/jquery.tabledit.js"></script> 

<script>

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



$('#example91').Tabledit({
      url: 'ajax/example.php',
      columns: {
        identifier: [0, 'id'],
        editable: [[1, 'make'],[2, 'year'],[3, 'model'],[4, 'platform']]
      }
    });
     
    $("#addRow92").on('click', function () {
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
     
    $('#addRow9').click(function() {
      var table = $('#example9');
      var body = $('#example9 tbody');
      var nextId = body.find('tr').length + 1;
      table.prepend($('' + nextId + ''));
      table.Tabledit('update');
    });
  </script>