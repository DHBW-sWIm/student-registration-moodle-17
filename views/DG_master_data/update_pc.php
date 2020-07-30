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

/* This document should contain a view on partner companies 
  */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
global $DB, $PAGE, $OUTPUT, $CFG, $USER;
$user = $USER->id;
$context = context_system::instance();
require_login();
if (has_capability('local/student_registration:manage', $context)) {

    $PAGE->requires->jquery();
    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
    $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));

    echo $OUTPUT->header();
    echo $OUTPUT->heading('Partner Companies');
    $company = $DB->get_record('dg_company', array('id' => $input['CompanyID']));
    echo '<div id="notification"></div>';

    echo '<div id="myDIV">
<div class="form">
       <div class="row">
        <div class="col-6">
              <label for="partner_company"><b>Company Name</b></label>
  <input type="text" id="partner_company" class="form-control" value="' . $company->company_name . '">
       </div>
      <div class="col-3">
      <label for="Classfication"><b>Classfication</b></label>
     <select id="Classfication" name="Classfication" class="form-control" required>
     ';

    if ($company->classification === 'A') {
        echo '<option value="A" selected>A</option>';
        echo '<option value="B">B</option>';
    } else {
        echo '<option value="B" selected>B</option>';
        echo '<option value="A">A</option>';
    }
    echo '</select>
      </div>
     </div>
<div>
</div>
</br>
<button id="addnew" class="btn btn-danger ">Update</button>   
</div>
</div>';
    echo '<div id="CompanyID" hidden>' . $company->id . '</div>';
} else {
    redirect($CFG->wwwroot);
};




?>

<script src="../../assets/JavaScript/jquery.tabledit.js"></script>

<script>
    $("#addnew").on('click', function() {
        // Getting value
        var partner_company = $('#partner_company').val();
        var Classfication = $('#Classfication').val();
        var CompanyID = $('#CompanyID').html();
        $.ajax({
            type: "POST",
            url: "../../assets/ajax/edit_pc.php",
            datatype: 'html',
            data: {
                partner_company: partner_company,
                Classfication: Classfication,
                CompanyID: CompanyID
            },
            success: function(data) {
                $("#notification").append('<div class="alert alert-success alert-dismissible fade show" role="alert">Record was successfully updated<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            },
            error: function() {
                $("#notification").append('<div class="alert alert-danger alert-dismissible fade show" role="alert">This Company Already Exists!!!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            }
        })
    });
</script>

<?PHP
echo $OUTPUT->footer();
?>