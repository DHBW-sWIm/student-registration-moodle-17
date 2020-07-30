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
$CompanyID = $input['CompanyID'];

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
require_login();

$id = $DB->insert_record(
    "dg_company_representative",
    array('compnay_id' => $CompanyID)
);

$records = $DB->get_records_select("dg_company_representative", 'compnay_id = ? ', array($CompanyID));
$edata = '';
foreach ($records as $result => $record) {
    $mdl_user = $DB->get_record('user', array('id' => $record->mdl_user_id));

    $edata .= '<tr repID = "' . $record->id . '">
                <td style="text-align:center;">' . $record->id . '</td>
                <td style="text-align:center;">' . $record->first_name . '</td>
                <td style="text-align:center;">' . $record->last_name . '</td>
                <td style="text-align:center;">' . $record->email . '</td>
                <td style="text-align:center;">' . $record->phone . '</td>
                <td style="text-align:center;">' . $mdl_user->username . '</td>
              </tr>';
};

// RETURN OUTPUT
echo $edata;
