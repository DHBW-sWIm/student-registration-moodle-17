<?php

// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.



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
try {



  // Php question

  if ($input['defaultCheck1'] === 'true') {

    $DB->delete_records('sr_management_tiles', ['id' => $input['rowID']]);
  } else {

    $attributes['id'] = $input['rowID'];
    $attributes['title'] = $input['newtitle'];
    $attributes['button_name'] = $input['btnname'];
    $attributes['button_url'] = $input['btnurl'];
    $attributes['button_icon'] = $input['btnicon'];
    $attributes['list_element_1'] = $input['element1'];
    $attributes['list_element_2'] = $input['element2'];
    $attributes['list_element_3'] = $input['element3'];
    $attributes['list_element_4'] = $input['element4'];
    $attributes['element_1_link'] = $input['element_1_link'];
    $attributes['element_2_link'] = $input['element_2_link'];
    $attributes['element_3_link'] = $input['element_3_link'];
    $attributes['element_4_link'] = $input['element_4_link'];
    $attributes['color'] = $input['btncolor'];
    $attributes['tile_order'] = $input['tileorder'];
    $attributes['plugin'] = $input['pluginname'];
    $attributes['task_path'] = $input['taskpath'];
    $attributes['function'] = $input['functionName'];
    $attributes['moodle_capability'] = $input['moodle_capability'];
    $DB->update_record('sr_management_tiles', $attributes);
  }

  redirect(new moodle_url('/local/student_registration/Dashboard_Settings.php'));

  // Close connection to DB

  /*  Your code for close connection to DB*/
} catch (dml_exception $e) {
}
// RETURN OUTPUT
echo json_encode($input);
