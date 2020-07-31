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

    echo '<div id="notification"></div>';

    echo '<div class="row">
<div class="col-md-12 m-b-20">
<input class="btn btn-link" onclick="myFunction()" id="collapselink" value="Add a new Partner Company" type="button">
</div>
</div></br>';

    echo '<div style="display: none;" id="myDIV">
<div class="form">
       <div class="row">
        <div class="col-6">
              <label for="partner_company"><b>Company Name</b></label>
  <input type="text" id="partner_company" class="form-control">
       </div>
      <div class="col-3">
      <label for="Classfication"><b>Classfication</b></label>
     <select id="Classfication" name="Classfication" class="form-control" required>
     <option value="A">A</option>
     <option value="B">B</option>
     </select>
      </div>
     </div>
<div>
</div>
</br>
<button id="addnew" class="btn btn-danger ">Add new</button>   
</div>
</div>';


    $table = new html_table();
    $table->id = 'my-table';
    $table->attributes['class'] = 'table table-sm';

    $records = $DB->get_records("dg_company");

    $table->head = array('Action', 'Partner Company', 'Classfication');
    $table->align = array('center', 'center', 'center');


    foreach ($records as $record) {

        $row = new html_table_row();
        $row->attributes['CompanyID'] = $record->id;

        $cell1 = new html_table_cell();
        $cell1->text = '<h5><b><span class="badge badge-pill badge-light">Add Representatives</span></b></h5>';

        $cell2 = new html_table_cell();
        $cell2->text = $record->company_name;
        $cell3 = new html_table_cell();
        $cell3->text = $record->classification;


        $row->cells  = array($cell1, $cell2, $cell3);

        $table->data[]  = $row;
    };


    echo html_writer::table($table);
} else {
    redirect($CFG->wwwroot);
};




?>

<script src="../../assets/JavaScript/jquery.tabledit.js"></script>

<script>
    //

    // redirect when click on a row
    $(function() {
        $('#my-table tr[CompanyID] td').each(function() {
            $(this).css('cursor', 'pointer').hover(
                function() {
                    $(this).addClass('active');
                },
                function() {


                    $(this).removeClass('active');
                }).click(function() {
                var CompanyID = $(this).parent().attr('CompanyID');
                redirectUrl = 'DG_representative.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                    '<input type="hidden" name="CompanyID" value="' + CompanyID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();
            });
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

    $("#addnew").on('click', function() {
        // Getting value
        var partner_company = $('#partner_company').val();
        var Classfication = $('#Classfication').val();
        $.ajax({
            type: "POST",
            url: "../../assets/ajax/add_new_pc.php",
            datatype: 'html',
            data: {
                partner_company: partner_company,
                Classfication: Classfication
            },
            success: function(data) {

                $("#notification").append('<div class="alert alert-success alert-dismissible fade show" role="alert">A new Partner Company was added successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
                // Add 'html' data to table
                $('#my-table tbody').html(data);
                $(function() {
                    $('#my-table tr[CompanyID] td').each(function() {
                        $(this).css('cursor', 'pointer').hover(
                            function() {
                                $(this).addClass('active');
                            },
                            function() {
                                $(this).removeClass('active');
                            }).click(function() {
                            var CompanyID = $(this).parent().attr('CompanyID');
                            redirectUrl = 'DG_representative.php';
                            var form = $('<form action="' + redirectUrl + '" method="post">' +
                                '<input type="hidden" name="CompanyID" value="' + CompanyID + '"></input>' + '</form>');
                            $('body').append(form);
                            $(form).submit();

                        });
                    });
                });
                $(function() {

                    i = 0;
                    $('#my-table tr[CompanyID]').each(function() {
                        i++;
                        $(this).append('<td id="company_id' + i + '" class="" style="cursor: pointer; "><button type="button" class="btn btn-danger pull-right" style="cursor: pointer;">Update</button></td>');
                        $('#company_id' + i + '').css('cursor', 'pointer').hover(
                            function() {
                                $(this).addClass('active');
                            },
                            function() {
                                $(this).removeClass('active');
                            }).click(function() {
                            var CompanyID = $(this).parent().attr('CompanyID');
                            redirectUrl = 'update_pc.php';
                            var form = $('<form action="' + redirectUrl + '" method="post">' +
                                '<input type="hidden" name="CompanyID" value="' + CompanyID + '"></input>' + '</form>');
                            $('body').append(form);
                            $(form).submit();
                        });

                    });
                });

            },
            error: function() {
                $("#notification").append('<div class="alert alert-danger alert-dismissible fade show" role="alert">This Company Already Exists!!!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            }
        })
    });

    $(function() {
        $('#my-table thead tr').append('<td class="text-right" style="padding-right: 4%;"><b>Action</b></td>');
        i = 0;
        $('#my-table tr[CompanyID]').each(function() {
            i++;
            $(this).append('<td id="company_id' + i + '" class="" style="cursor: pointer; "><button type="button" class="btn btn-danger pull-right" style="cursor: pointer;">Update</button></td>');
            $('#company_id' + i + '').css('cursor', 'pointer').hover(
                function() {
                    $(this).addClass('active');
                },
                function() {
                    $(this).removeClass('active');
                }).click(function() {
                var CompanyID = $(this).parent().attr('CompanyID');
                redirectUrl = 'update_pc.php';
                var form = $('<form action="' + redirectUrl + '" method="post">' +
                    '<input type="hidden" name="CompanyID" value="' + CompanyID + '"></input>' + '</form>');
                $('body').append(form);
                $(form).submit();
            });

        });
    });
</script>

<?PHP
echo $OUTPUT->footer();
?>