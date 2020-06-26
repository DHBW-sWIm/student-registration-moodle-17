<?php
 
// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.

define('AJAX_SCRIPT', true);

require(__DIR__ . '/../../../../config.php');
require_login();

global $USER, $DB;

// CHECK REQUEST METHOD
if ($_SERVER['REQUEST_METHOD']=='POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};
 
// PHP QUESTION TO MYSQL DB
 
// Connect to DB
 
  /*  Your code for new connection to DB*/
 try{


 
// Php question
  if ($input['action'] === 'edit') {
    

    $attributes['id'] = $input['ID'];
    $attributes['title'] = $input['Title'];
    $attributes['button_name'] = $input['Button_Name'];
    $attributes['button_url'] = $input['btn_URL'];
    $attributes['button_icon'] = $input['Icon'];
    $attributes['list_element_1'] = $input['Element_1'];
    $attributes['list_element_2'] = $input['Element_2'];
    $attributes['list_element_3'] = $input['Element_3'];
    $attributes['list_element_4'] = $input['Element_4'];
    $attributes['color'] = $input['Color'];
    $attributes['tile_order'] = $input['Order'];
    $attributes['plugin'] = $input['Plugin'];
    
  $DB->update_record('sr_management_tiles',$attributes);
 
} else if ($input['action'] === 'delete') {

  $param = $input['ID'];
 
  $DB->delete_records('sr_management_tiles',['id'=>$param]);
 
} else if ($input['action'] === 'restore') {
 
  $DP->insert_record('sr_management_tiles', $input);
 
};
 
// Close connection to DB
 
/*  Your code for close connection to DB*/
}catch(dml_exception $e){

}
// RETURN OUTPUT
echo json_encode($input);
 
?>