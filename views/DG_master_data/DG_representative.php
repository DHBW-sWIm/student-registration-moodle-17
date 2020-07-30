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

/* This document should contain a view for managing master data of study fields
  */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
global $DB, $PAGE, $OUTPUT, $CFG, $USER;
require_login();
$user = $USER->id;
$context = context_system::instance();
require_login();
if (has_capability('local/student_registration:manage', $context)) {
    $CompanyID = $input['CompanyID'];


    $PAGE->set_heading('DHBW Student Registration');
    $PAGE->requires->jquery();
    $PAGE->requires->css('/local/student_registration/assets/CSS/jquery.dataTables.min.css', true);
    $PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.tabledit.js', true);
    $PAGE->requires->js('/local/student_registration/assets/JavaScript/jquery.dataTables.min.js', true);
    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
    $PAGE->navbar->add('Student Registration', new moodle_url('/local/student_registration/views/Menu.php'));
    $PAGE->navbar->add('Partner Company Master Data', new moodle_url('/local/student_registration/views/DG_master_data/DG_company.php'));

    $Company = $DB->get_record('dg_company', array('id' => $CompanyID));
    echo $OUTPUT->header();
    echo $OUTPUT->heading('Company Representative List for ' . $Company->company_name);

    echo '<div id="notification"></div>';
    echo '<div class="row">
        <div class="col-12 m-b-20">
          <input type="button" value="Add row" id="addRow9" class="btn btn-danger pull-right">
        </div>
      </div>';
    echo '</br>';
    $table = new html_table();
    $table->id = 'my_table';
    $table->attributes['class'] = 'table table-sm ';
    $records = $DB->get_records_select("dg_company_representative", 'compnay_id = ? ', array($CompanyID));

    $table->head = array('ID', 'First Name', 'Last Name', 'Company Email', 'Phone', 'Moodle User Name');
    $table->align = array('center', 'center', 'center', 'center', 'center', 'center');


    foreach ($records as $record) {

        $row = new html_table_row();
        $row->attributes['repID'] = $record->id;

        $cell1 = new html_table_cell();
        $cell1->text = $record->id;

        $cell2 = new html_table_cell();
        $cell2->text = $record->first_name;

        $cell3 = new html_table_cell();
        $cell3->text = $record->last_name;

        $cell4 = new html_table_cell();
        $cell4->text = $record->email;
        $cell5 = new html_table_cell();
        $cell5->text = $record->phone;
        $cell6 = new html_table_cell();
        $mdl_user = $DB->get_record('user', array('id' => $record->mdl_user_id));
        if ($mdl_user) {
            $cell6->text = $mdl_user->username;
        }


        $row->cells  = array($cell1, $cell2, $cell3, $cell4, $cell5, $cell6);


        $table->data[]  = $row;
    };


    echo html_writer::table($table);

    echo '</br>';

    echo '<form action="../../assets/PHPFunctions/pc_deletetion.php" method="post">
  <div class="form-group">
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="true" name="partner_remove" id="partner_remove">
  <label class="form-check-label" for="partner_remove">
    Remove Partner Company
  </label>
</div>
</div>
<input  type="submit" class="btn btn-danger" value="Save">
<input type="number" class="hidden" name="CompanyID" id="CompanyID" value="' . $CompanyID . '">
</form>';
    $capability = 'local/student_registration:cr';
    $sql = "SELECT us.username FROM {user} AS us
                               INNER JOIN {role_assignments} AS ra ON ra.userid = us.id
                               INNER JOIN {role_capabilities} AS rc ON ra.roleid = rc.roleid
                               AND rc.capability =  ?";
    $users = $DB->get_records_sql($sql, array($capability));

    echo '<datalist id="user_name">';

    foreach ($users as $user => $val) {

        echo '<option value="' . $user . '">';
    }
    echo '</datalist>';
} else {
    redirect($CFG->wwwroot);
};


?>

<script>
    (function(jQuery) {
        // You pass-in jQuery and then alias it with the jQuery-sign
        // So your internal code doesn't change


        jQuery('#my_table').Tabledit({
            url: "../../assets/ajax/edit_pc_representative.php",
            editButton: true,
            saveButton: true,
            restoreButton: false,

            columns: {
                identifier: [0, 'ID'],
                editable: [
                    [1, 'First Name'],
                    [2, 'Last Name'],
                    [3, 'Company Email'],
                    [4, 'Phone'],
                    [5, 'Moodle User Name'],
                ]
            }
        });


        jQuery("#addRow9").on('click', function() {
            // Getting value
            var CompanyID = jQuery('#CompanyID').val();

            jQuery.ajax({
                type: "POST",
                url: "../../assets/ajax/add_new_representative.php",
                datatype: 'html',
                data: {
                    CompanyID: CompanyID
                },
                success: function(data) {
                    // Add 'html' data to table
                    jQuery('#my_table tbody').html(data);
                    // Update Tabledit plugin
                    jQuery("#my_table").Tabledit('update');
                    jQuery(function() {
                        jQuery('#my_table tbody tr td:nth-child(6) > input').each(function() {
                            jQuery(this).attr('list', 'user_name');
                        });
                    });
                },
                error: function(error) {

                }
            })
        });

        var dataTable = jQuery("#my_table").DataTable();
        //custom search function
        jQuery.fn.DataTable.ext.search.push((_, __, i) => {
            //get current row

            const currentTr = dataTable.row(i).node();
            //look for all <input>, <select> nodes within 
            //that row and check whether current value of
            //any of those contains searched string
            const inputMatch = jQuery(currentTr)
                .find('select,input')
                .toArray()
                .some(input => jQuery(input).val().toLowerCase().includes(jQuery('#my_table_filter').children().children().val().toLowerCase()));
            //check whether "regular" cells contain the
            //value being searched
            const textMatch = jQuery(currentTr)
                .children()
                .not('td:has("input,select")')
                .toArray()
                .some(td => jQuery(td).text().toLowerCase().includes(jQuery('#my_table_filter').children().children().val().toLowerCase()))
            //make final decision about match
            return inputMatch || textMatch || jQuery('#my_table_filter').children().children().val() == ''
        });
    })(jQuery);

    jQuery(function() {
        jQuery('#my_table tbody tr td:nth-child(6) > input').each(function() {
            jQuery(this).attr('list', 'user_name');
        });
    });
    window.addEventListener('error', function(event) {
        jQuery("#notification").html('<div id="errormsg" class="alert alert-danger alert-dismissible fade show" role="alert">The selected user name is already assigned to another company representative<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    })
</script>

<?PHP
echo $OUTPUT->footer();
?>