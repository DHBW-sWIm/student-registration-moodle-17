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

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
global $DB, $PAGE, $OUTPUT, $CFG, $USER;
require_login();
$context = context_system::instance();
if (has_capability('local/management_dashbaord:edit', $context)) {



  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
  } else {
    $input = filter_input_array(INPUT_GET);
  };

  $user = $USER->id;


  $PAGE->set_heading('DHBW Student Registration');
  $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
  $PAGE->requires->jquery();

  echo $OUTPUT->header();
  echo $OUTPUT->heading('Tiles Configuration Site');
  echo '<div id="notification"></div>';

  echo '
  <br/>
  <form>
    <div class="form-row">
      <div class="col-md-11">
        <input type="text" class="form-control" id="myInput" placeholder="Plugin Name"/>
      </div>
      <div class="col-md-1">
        <input type="button" value="Add row" id="addRow9" class="btn btn-danger mb-2 pull-right"/>
      </div>
    </div>
  </form>
  <br/>
  ';


  $table = new html_table();
  $table->id = 'my_table';
  $table->attributes['class'] = 'table table-sm';


  $records = $DB->get_records_select("sr_management_tiles", array());

  $table->head = array('ID', 'Plugin Name', 'Tile');
  $table->align = array('left', 'left', 'left');
  $i = 0;
  foreach ($records as $record) {
    $i++;
    $row = new html_table_row();
    $row->attributes['rowID'] = $record->id;

    $cell1 = new html_table_cell();
    $cell1->text = $record->plugin;

    $cell2 = new html_table_cell();
    $cell2->text = $record->title;

    $cell3 = new html_table_cell();
    $cell3->text = $i;

    $row->cells  = array($cell3, $cell1, $cell2);

    $table->rowclasses[$record->id] = '';
    $table->data[]  = $row;
  };


  echo html_writer::table($table);



  echo $OUTPUT->footer();
} else {
  http_response_code(403);
  die('<h1>Forbidden</h1>');
}

?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="assets/JavaScript/jquery.tabledit.js"></script>

<script>
  // 
  $(document).ready(function() {
    $('#my_table tr[rowID]').each(function() {
      $(this).css('cursor', 'pointer').hover(
        function() {
          $(this).addClass('active');
        },
        function() {
          $(this).removeClass('active');
        }).on('click', this, function() {
        var rowID = $(this).attr('rowID');
        redirectUrl = 'assets/ajax/update_tile.php';
        var form = $('<form action="dashboard_tile_edit.php" method="post">' +
          '<input type="hidden" name="rowID" value="' + rowID + '"></input>' + '</form>');
        $('body').append(form);
        $(form).submit();

      });
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

  $("#addRow9").on('click', function() {
    // Getting value


    $.ajax({
      type: "POST",
      url: "assets/ajax/add_new_tile.php",
      datatype: 'html',
      data: {

      },
      success: function(data) {

        $("#notification").html('<div id"statusnotification" class="alert alert-success alert-dismissible fade show" role="alert">A new Tile was added successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        $(".alert").delay(4000).slideUp(200, function() {
          (this).alert('close');
        });
        // Add 'html' data to table
        $('#my_table tbody').html(data);

        // Update Tabledit plugin
        $('#my_table').Tabledit('update');
        $(document).ready(function() {
          $('#my_table tr[rowID]').each(function() {
            $(this).css('cursor', 'pointer').hover(
              function() {
                $(this).addClass('active');
              },
              function() {
                $(this).removeClass('active');
              }).on('click', this, function() {
              var rowID = $(this).attr('rowID');

              var form = $('<form action="dashboard_tile_edit.php" method="post">' +
                '<input type="hidden" name="rowID" value="' + rowID + '"></input>' + '</form>');
              $('body').append(form);
              $(form).submit();

            });
          });
        });
      },
      error: function() {
        $("#notification").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Something went wrong!!!!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        $(".alert").delay(4000).slideUp(200, function() {
          $(this).alert('close');
        });
      }
    })
  });
</script>