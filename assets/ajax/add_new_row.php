<?php
 
// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.
 
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");
 
// Get value from ajax
if ($_SERVER['REQUEST_METHOD']=='POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};
$processID = $input['ID'];

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
 
// PHP QUESTION TO MYSQL DB
 
// Connect to DB
 
  /*  Your code for new connection to DB*/
 
// Php question
 
  /*  Your code for insert data to DB */


global $DB, $USER;

$user =  $USER->id;



$id = $DB->insert_record("sr_reservation_list", array('first_name'=>'dummy','last_name'=>'dummy','sr_company_representative_id'=>$user, 'sr_process_id'=>$processID));
 
$records = $DB->get_records_select("sr_reservation_list",'sr_company_representative_id= ? AND sr_process_id = ?' ,array($USER->id , $processID) );
 
foreach ($records as $result=>$record) {
  $edata .= '<tr>
                 <td>' . $record->id. '</td>
                 <td>' . $record->first_name . '</td>
                 <td>' . $record->last_name . '</td>
                 <td>' . $record->date_of_birth . '</td>
              </tr>
             ';
};
 
// RETURN OUTPUT
echo $edata;
 
?>