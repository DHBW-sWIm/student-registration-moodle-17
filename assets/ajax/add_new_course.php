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
$processID = $input['ProcessID'];

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
 
// PHP QUESTION TO MYSQL DB
 
// Connect to DB
 
  /*  Your code for new connection to DB*/
 
// Php question
 
  /*  Your code for insert data to DB */


global $DB, $USER;

$user =  $USER->id;



$id = $DB->insert_record("sr_active_study_course", array('study_course_name'=>'test','study_course_abbreviation'=>'test','sr_employees_id'=>$user , 'sr_process_id'=>$processID));
 
$records = $DB->get_records_select("sr_active_study_course",'sr_employees_id = ? AND sr_process_id = ?' ,array($user, $processID) );
 
foreach ($records as $result=>$record) {
  if($record->end_date > date("Y-m-d h-m-s",time())){
    $padg2 = '<h5><b><span class="badge badge-pill badge-warning">'.$record->end_date.'</span></b></h5>';
   }else $padg2 = '<h5><b><span class="badge badge-pill badge-danger">'.$record->end_date.' </span></b></h5>';

  $edata .= '<tr courseid="' . $record->id . '">
                 
                 <td>' . $record->study_course_name . '</td>
                 <td><h5><b><span class="badge badge-pill badge-light">' . $record->study_course_abbreviation . '</span></b></h5></td>
                 <td><b><span class="badge badge-pill badge-success">'.$record->start_date.'</span></b></td>
                 <td>' . $padg2 . '</td>
                 <td>' . $record->course_capacity . '</td>
              </tr>
             ';
};
 
// RETURN OUTPUT
echo $edata;
 
?>