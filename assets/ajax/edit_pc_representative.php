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

if ($input['action'] === 'edit') {

    $attributes['id'] = $input['ID'];
    $attributes['first_name'] = $input['First_Name'];
    $attributes['last_name'] = $input['Last_Name'];
    $attributes['email'] = $input['Company_Email'];
    $attributes['phone'] = $input['Phone'];
    $mdl_user = $DB->get_record('user', array('username' => $input['Moodle_User_Name']));
    $attributes['mdl_user_id'] = $mdl_user->id;

    try {
        $DB->update_record('dg_company_representative', $attributes);
    } catch (dml_exception $e) {
        http_response_code(500);
    }
} else if ($input['action'] === 'delete') {

    $param = $input['ID'];

    $DB->delete_records('dg_company_representative', ['id' => $param]);
} else if ($input['action'] === 'restore') {

    // PHP code for edit restore

};

echo json_encode($input);
