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
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
global $DB, $PAGE, $OUTPUT, $CFG, $USER;


$PAGE->requires->jquery();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

$user = $USER->id;


$PAGE->set_heading('DHBW Student Registration');
$PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
$PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));

echo $OUTPUT->header();
echo $OUTPUT->heading('Student Registration Process Settings');


$status = $DB->get_record_select('sr_process_settings', 'user_id =?', array($user));

echo '<form>

<div class="alert alert-secondary" role="alert">
<h4 class="alert-heading"><b>Dear User</b></h4>
<p><b>Managing the waiting list is automatically done by the system  based on FIFO principle.</b><hr> You can change the
settings to manually move students from waiting list into reservation list. This change will only affect your processes.
</p>
<hr>
</div>
<div class="row">
<div class="form-group col-md-3">
<label for="Watingsettings"><b>Waiting list settings</b></label>
<span id ="sucessbadge" class="badge badge-success" style="display:none">Saved</span>
<span id ="failbadge" class="badge badge-danger" style="display:none">Error Happend!!!</span>
<select id="Watingsettings" name="Watingsettings" class="form-control">
  ';
if ($status) {
  if ($status->manual_waiting_list == 0) {

    echo '<option selected>Automatic</option> <option>Manual</option>';
  } else echo '<option >Automatic</option> <option selected>Manual</option>';
} else {
  echo '<option selected>Automatic</option><option>Manual</option>';
}
echo '
  
</select>

<hr>
<button id="saveSettings"  class="btn btn-danger">Save</button><hr>
</form>';


echo $OUTPUT->footer();


?>

<script src="../assets/JavaScript/jquery.tabledit.js"></script>

<script>
  // 


  $("#saveSettings").on('click', function() {
    // Getting value
    var settings = $('#Watingsettings option:selected').html();

    $.ajax({
      type: "POST",
      url: "../assets/ajax/st_settings.php",
      datatype: 'html',
      data: {
        settings: settings

      },
      success: $("#sucessbadge").fadeIn().delay(3000).fadeOut(),

      error: function() {
        ("#failbadge").fadeIn().delay(3000).fadeOut()
      },


    })
  });
</script>