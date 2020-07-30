<?php

// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.

define('AJAX_SCRIPT', true);

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');


global $USER, $DB;
require_login();
// CHECK REQUEST METHOD
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};


$attributes['id'] = $input['CompanyID'];
$attributes['company_name'] = $input['partner_company'];
$attributes['classification'] = $input['Classfication'];

$DB->update_record('dg_company', $attributes);


echo json_encode($input);
