<?php

use core_customfield\data;

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");

// Get value from ajax
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
require_once('../PHPMailer/waiting_list_record_moved.php');
global $DB, $USER;
require_login();
$user =  $USER->id;
// inset new user settings or update old record
$status = 0;
if ($input['settings'] === 'Manual') {
  $status = 1; // if manual then set status true
  $record = $DB->get_records_select("sr_process_settings", 'user_id = ?', array($user));
  if ($record) {
    $record = current($record);
    $DB->update_record('sr_process_settings', array('id' => $record->id, 'manual_waiting_list' => $status));
    $data = 'record was update';
  } else {
    $id = $DB->insert_record("sr_process_settings", array('user_id' => $user, 'manual_waiting_list' => $status));
    $data = 'record was added';
  }
} else {
  // when the settings is set to manual the counter of current demand will not be reduced when the maximun is reached and a record in the RL was deleted
  // Additionally, moving records from waiting list is done manually. 
  // if the status of WL is set to automatic. There could be active process which is open for seats reservation. 
  // which means that there could be records on waiting list were supposed to be moved manually to the Reservation list 
  // when a record in RL is deleted. 

  // check if there is active process in opened for seats reservation 
  $activeprocesses = $DB->get_records_select('sr_process', 'director_id = ? AND start_date_for_b < CURRENT_TIMESTAMP AND closed = 0', array($user));
  if ($activeprocesses) {

    foreach ($activeprocesses as $activeprocess) {

      // check how many records are actually in the reservation list vs. how many are supposed to be there on study field bases
      $activestudyfields = $DB->get_records_select('sr_study_places_status', 'sr_process_id = ?', array($activeprocess->id));
      foreach ($activestudyfields as $activestudyfield) {
        // count of records in RL for an active study field
        $countRL = $DB->count_records('sr_reservation_list', array('sr_process_id' =>  $activestudyfield->sr_process_id, 'sr_study_fields_id' => $activestudyfield->sr_study_fields_id));
        if ($countRL == 0 || empty($countRL)) {
          continue;
        } elseif ($countRL == $activestudyfield->current_demand) {
          continue;
        } elseif ($countRL < $activestudyfield->current_demand) {
          $diff = (int) $activestudyfield->current_demand -   $countRL;
          for ($i = 0; $i <  $diff; $i++) {
            // get the first row in WL: The record that was firstly inserted from time perspective for a specific process and study field 
            // e.g. Business Informatics Sales & Consulting
            $maxwl = $DB->get_records_select("sr_waiting_list", 'sr_process_id = ? AND sr_study_fields_id = ? AND moved_to_rl = 0', array($activestudyfield->sr_process_id, $activestudyfield->sr_study_fields_id), '', 'min(timecreated)');
            // Move record from WL into RL 
            foreach ($maxwl as $max => $val) {
              $maxtime = $max;
            }
            if ($max !== '') {
              $record = $DB->get_records_select("sr_waiting_list", 'sr_process_id = ? AND sr_study_fields_id = ? AND moved_to_rl = 0 AND timecreated = ?', array($activestudyfield->sr_process_id, $activestudyfield->sr_study_fields_id, $maxtime));
              $record = current($record);
              $DB->delete_records('sr_waiting_list', ['id' => (int) $record->id]);
              unset($record->id);
              $record->contract_status = 'Not Signed';
              unset($record->moved_to_rl);
              $DB->insert_record('sr_reservation_list', array(
                'first_name' => $record->first_name, 'last_name' => $record->last_name, 'date_of_birth' => $record->date_of_birth, 'private_email' => $record->private_email, 'sr_company_representative_id' => $record->sr_company_representative_id, 'sr_process_id' => $record->sr_process_id, 'sr_study_fields_id' => $record->sr_study_fields_id, 'usermodified' => $record->usermodified, 'contract_status' => $record->contract_status
              ));
              notify_cr_wl_to_rl('', $record->sr_process_id, $record->sr_study_fields_id, $record->sr_company_representative_id);
            } else {
              if ($activestudyfield->current_demand) {
                $current_demand = ($activestudyfield->current_demand - 1);
              }
              $DB->update_record('sr_study_places_status', array('id' => $activestudyfield->id, 'current_demand' => $current_demand));
            }
          }
        }
      }
    }


    // update settings after moving records from WL into RL 
    $record = $DB->get_records_select("sr_process_settings", 'user_id = ?', array($user));
    if ($record) {
      $record = current($record);
      $DB->update_record('sr_process_settings', array('id' => $record->id, 'manual_waiting_list' => $status));
      $data = 'record was update';
    } else {
      $id = $DB->insert_record("sr_process_settings", array('user_id' => $user, 'manual_waiting_list' => $status));
      $data = 'record was added';
    }
  } else {
    // if there is no active processes opened for seats reservation just update settings 
    $record = $DB->get_records_select("sr_process_settings", 'user_id = ?', array($user));
    if ($record) {
      $record = current($record);
      $DB->update_record('sr_process_settings', array('id' => $record->id, 'manual_waiting_list' => $status));
      $data = 'record was update';
    } else {
      $id = $DB->insert_record("sr_process_settings", array('user_id' => $user, 'manual_waiting_list' => $status));
      $data = 'record was added';
    }
  }
}



echo $input['settings'];
