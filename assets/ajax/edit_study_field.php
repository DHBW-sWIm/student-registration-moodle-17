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

// PHP QUESTION TO MYSQL DB

// Connect to DB

/*  Your code for new connection to DB*/


// Php question
if ($input['action'] === 'edit') {

  $attributes['id'] = $input['ID'];
  $attributes['study_field_name'] = $input['Study_Field'];
  $attributes['description'] = $input['Description'];



  $DB->update_record('sr_study_fields', $attributes);
} else if ($input['action'] === 'delete') {

  $param = $input['ID'];

  $DB->delete_records('sr_study_fields', ['id' => $param]);
} else if ($input['action'] === 'restore') {

  // PHP code for edit restore

};

// Close connection to DB

/*  Your code for close connection to DB*/

// RETURN OUTPUT
echo json_encode($input);
