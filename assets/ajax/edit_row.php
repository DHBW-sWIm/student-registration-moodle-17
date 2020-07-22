<?php

// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.

define('AJAX_SCRIPT', true);

require(__DIR__ . '/../../../../config.php');
require_login();

global $USER, $DB;

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

  $attributes['id'] = (int) $input['id'];
  $attributes['first_name'] = $input['First_Name'];
  $attributes['last_name'] = $input['Last_Name'];
  $attributes['contract_status'] = $input['Contract_Status']; // must be correct type or insertion wont work
  $attributes['private_email'] = $input['Email'];
  $course = $DB->get_record_select('sr_active_study_course', 'id = ?', array($input['Study_Course']));
  $count = $DB->count_records('sr_reservation_list', array('sr_active_study_course_id' => $course->id));
  if ($course->course_capacity > $count) {
    $attributes['sr_active_study_course_id'] = $input['Study_Course'];
  }
  $DB->update_record('sr_reservation_list', $attributes);
} else if ($input['action'] === 'delete') {

  // manage deleted records in the reservation list
  // if maximum available study places has not been reserved, deleting a record would reduce the counter of 
  // current demand in sr_study_places_status table.
  // if the maximum has been reached, there is two different possibilities 
  // 1- managing Waiting list is automatic: This would mean that when deleting a record in reservation list, the first record 
  //    in waiting list would be moved into the relevant reservation list. 
  // 2- manaing waiting list is manual: This means that when a record in reservation list is deleted, neither the counter is reduces
  //    nor the records in WL is moved. A study manager should see in this case a new View which enables him/her to move chosen records manually 
  $param = $input['id'];
  // get process ID and study field ID
  $RLrow = $DB->get_record_select('sr_reservation_list', 'id = ?', array($param));
  // get total available study places && current damand info based on process ID and study field ID
  $DSrow = $DB->get_record_select('sr_study_places_status', 'sr_process_id = ? AND sr_study_fields_id = ?', array($RLrow->sr_process_id,  $RLrow->sr_study_fields_id));
  // if capacity has been set  !== 0 AND maximum capacity has been reached
  if ($DSrow->study_places_available !== 0 && $DSrow->study_places_available == $DSrow->current_demand) {
    // check waiting list settings for the user who created this process !!!!! not for the current user....
    // if the user has not changed the settings for waiting list then there will be no record in the settings table,
    // therefore, check if the status is null then manual waiting list is off
    $waitingliststatus = $DB->get_records_select('sr_process_settings', 'user_id = ?', array($DSrow->usermodified), '', 'manual_waiting_list');
    $waitingliststatus = current($waitingliststatus);
    ($waitingliststatus->manual_waiting_list == '0' || !isset($waitingliststatus->manual_waiting_list)) ? $waitingliststatus = 'off' : $waitingliststatus = 'on';
    // if automatic(manual WL is set to 0 (false)), then --> 
    if ($waitingliststatus === 'off') {
      // get the first row in WL: The record that was firstly inserted from time perspective for a specific process and study field 
      // e.g. Business Informatics Sales & Consulting
      $maxwl = $DB->get_records_select("sr_waiting_list", 'sr_process_id = ? AND sr_study_fields_id = ? AND moved_to_rl = 0', array($DSrow->sr_process_id, $DSrow->sr_study_fields_id), '', 'min(timecreated)');
      // Move record from WL into RL 
      foreach ($maxwl as $max => $val) {
        $maxtime = $max;
      }
      if ($max !== '') {
        $record = $DB->get_records_select("sr_waiting_list", 'sr_process_id = ? AND sr_study_fields_id = ? AND moved_to_rl = 0 AND timecreated = ?', array($DSrow->sr_process_id, $DSrow->sr_study_fields_id, $maxtime));
        $record = current($record);
        $DB->delete_records('sr_waiting_list', ['id' => (int) $record->id]);
        unset($record->id);
        $record->contract_status = 'Not Signed';
        unset($record->moved_to_rl);
        $time_posted = date("Y-m-d H:i:s", time());
        $DB->insert_record('sr_reservation_list', array(
          'first_name' => $record->first_name, 'last_name' => $record->last_name, 'date_of_birth' => $record->date_of_birth, 'private_email' => $record->private_email, 'sr_company_representative_id' => $record->sr_company_representative_id, 'sr_process_id' => $record->sr_process_id, 'sr_study_fields_id' => $record->sr_study_fields_id, 'usermodified' => $record->usermodified, 'contract_status' => $record->contract_status, 'timecreated' => $time_posted
        ));
        $DB->delete_records('sr_reservation_list', ['id' => $param]);
        include_once('../../dashboard_lib.php');
        $new = notify_cr('Email to Company');
      } else {
        if ($DSrow->current_demand) {
          $current_demand = ($DSrow->current_demand - 1);
        }
        $DB->update_record('sr_study_places_status', array('id' => $DSrow->id, 'current_demand' => $current_demand));
        $DB->delete_records('sr_reservation_list', ['id' => $param]);
      }
      // if WL settings is on (Manual)
    } else {
      $DB->delete_records('sr_reservation_list', ['id' => $param]);
    }
  } else {
    // if there is current demand, reduces counter
    if ($DSrow->current_demand) {
      $current_demand = ($DSrow->current_demand - 1);
    }
    $DB->update_record('sr_study_places_status', array('id' => $DSrow->id, 'current_demand' => $current_demand));
    $DB->delete_records('sr_reservation_list', ['id' => $param]);
  }
}
else if ($input['action'] === 'restore') {

  // PHP code for edit restore
};
// Close connection to DB
/*  Your code for close connection to DB*/
// RETURN OUTPUT
echo json_encode($input);
