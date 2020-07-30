
<?php

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

global $DB, $USER, $CFG;
require_login();
$user = $USER->id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};
if ($input['partner_remove'] === 'true') {
    $DB->delete_records('dg_company_representative', array('compnay_id' => $input['CompanyID']));
    $DB->delete_records('dg_company', array('id' => $input['CompanyID']));
};
redirect(new moodle_url('/local/student_registration/views/DG_master_data/DG_company.php'));
?>