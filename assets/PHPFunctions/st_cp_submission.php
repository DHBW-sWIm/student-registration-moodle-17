<?php


require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

global $DB ,$USER;
require_login();
$user = $USER->id;

if ($_SERVER['REQUEST_METHOD']=='POST') {
    $input = filter_input_array(INPUT_POST);
  } else {
    $input = filter_input_array(INPUT_GET);
  };
$ProcessID =  $_SESSION['ProcessID'];

unset($_SESSION['ProcessID']);
$Update = $input['Update'];
unset($input['Update']);

foreach($input as $record=>$val){
   // if(isset($val) && $val !== ''){
       if($Update){
        $row = $DB->get_record_select('sr_study_places_status' , 'sr_process_id = ? AND sr_study_fields_id = ?' , array($ProcessID , $record));
        $DB->update_record('sr_study_places_status', array('id'=>$row->id,'study_places_available'=>(int)$val));
       
       }else  $DB->insert_record('sr_study_places_status', array('study_places_available'=>$val, 'sr_process_id'=> $ProcessID, 'sr_study_fields_id'=>$record, 'usermodified'=>$user));
}

redirect(new moodle_url('/local/student_registration/views/ST_process/ST_active_process_Ma_CP.php'));
