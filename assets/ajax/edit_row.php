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
 
 
// Php question
  if ($input['action'] === 'edit') {

  $attributes['id'] = (int)$input['id'];
  $attributes['first_name'] = $input['First_Name'];
  $attributes['last_name'] = $input['Last_Name'];
  $attributes['contract_status'] = $input['Contract_Status']; // must be correct type or insertion wont work
  $attributes['private_email'] = $input['Email'];

  $DB->update_record('sr_reservation_list',$attributes);
 
} else if ($input['action'] === 'delete') {

  $param = $input['id'];
  $RLrow = $DB->get_record_select('sr_reservation_list' , 'id = ?', array($param));

  $DSrow = $DB->get_record_select('sr_study_places_status' , 'sr_process_id = ? AND sr_study_fields_id = ?', array( $RLrow->sr_process_id ,  $RLrow->sr_study_fields_id));
  if($DSrow->study_places_available !== 0 && $DSrow->study_places_available == $DSrow->current_demand){


    $maxwl = $DB->get_records_select("sr_waiting_list",'sr_process_id = ? AND sr_study_fields_id = ? AND moved_to_rl = 0' ,array( $DSrow->sr_process_id , $DSrow->sr_study_fields_id) , '' , 'max(timecreated)');
    
    foreach($maxwl as $max=>$val){
      $maxtime = $max;
    }
    if($max !== ''){
    $record = $DB->get_records_select("sr_waiting_list",'sr_process_id = ? AND sr_study_fields_id = ? AND moved_to_rl = 0 AND timecreated = ?' ,array( $DSrow->sr_process_id , $DSrow->sr_study_fields_id , $maxtime  ) );
    $record = current($record);
    $DB->delete_records('sr_waiting_list',['id' =>(int)$record->id]);
    unset($record->id);
    $record->contract_status = 'Not Signed';
    unset($record->moved_to_rl);

    $DB->insert_record('sr_reservation_list',array('first_name'=>$record->first_name , 'last_name'=>$record->last_name  , 'date_of_birth'=>$record->date_of_birth , 'private_email'=>$record->private_email
    , 'sr_company_representative_id'=>$record->sr_company_representative_id , 'sr_process_id'=>$record->sr_process_id , 'sr_study_fields_id'=>$record->sr_study_fields_id , 'usermodified'=>$record->usermodified , 'contract_status'=>$record->contract_status));
    $DB->delete_records('sr_reservation_list',['id'=>$param]);
   }else{

  

      if($DSrow->current_demand){
        $current_demand = ($DSrow->current_demand -1);
      } 
      $DB->update_record('sr_study_places_status', array('id'=>$DSrow->id , 'current_demand'=>$current_demand ));
      $DB->delete_records('sr_reservation_list',['id'=>$param]);
    }

  
  }else{
    
  if($DSrow->current_demand){
    $current_demand = ($DSrow->current_demand -1);
  } 
  $DB->update_record('sr_study_places_status', array('id'=>$DSrow->id , 'current_demand'=>$current_demand ));
  $DB->delete_records('sr_reservation_list',['id'=>$param]);

  }
} else if ($input['action'] === 'restore') {
 
  // PHP code for edit restore
 
};
 
// Close connection to DB
 
/*  Your code for close connection to DB*/
 
// RETURN OUTPUT
echo json_encode($input);
 
?>