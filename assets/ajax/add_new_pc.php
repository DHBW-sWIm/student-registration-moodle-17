<?php

// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");

// Get value from ajax
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};


require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

// PHP QUESTION TO MYSQL DB

// Connect to DB

/*  Your code for new connection to DB*/

// Php question

/*  Your code for insert data to DB */


global $DB, $USER;

$user =  $USER->id;
require_login();
$status = $DB->get_record_select('dg_company', 'company_name = ?', array($input['partner_company']));
if ($status) {
    http_response_code(500);
    exit;
} else {
    $id = $DB->insert_record("dg_company", array('company_name' => $input['partner_company'], 'classification' => $input['Classfication']));
}


$records = $DB->get_records("dg_company");
$edata = '';
foreach ($records as $result => $record) {

    $edata .= '<tr CompanyID = "' . $record->id . '">
                 <td style="text-align:center;"><h5><b><span class="badge badge-pill badge-light">Add Representatives</span></b></h5></td>
                 <td style="text-align:center;">' . $record->company_name . '</td>
                 <td style="text-align:center;">' . $classification . '</td>
              </tr>
             ';
};

// RETURN OUTPUT
echo $edata;
