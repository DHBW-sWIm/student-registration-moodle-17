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
$ProcessID = $input['ProcessID'];
$DSRecordID = $input['DSRecordID'];
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
 
 

global $DB, $USER;

$user =  $USER->id;


$DSRow = $DB->get_records_select('sr_study_places_status', 'id = ?', array($DSRecordID));//Demand status row 
$total = (int)$DSRow[$DSRecordID]->study_places_available;

($DSRow[$DSRecordID]->current_demand) ? $reserved = $DSRow[$DSRecordID]->current_demand : $reserved = 0 ;

$free = ($total - $reserved);

if($free == 0){
  
  $id = $DB->insert_record("sr_waiting_list", array('first_name'=>'Waiting list','last_name'=>'Study Place' ,'sr_company_representative_id'=>$user, 'sr_process_id'=>$ProcessID , 'sr_study_fields_id' =>$DSRow[$DSRecordID]->sr_study_fields_id , 'timecreated'=> date("Y-m-d H-m-s" , time())));
 // $DB->update_record('sr_study_places_status', array('id'=>$DSRecordID , 'current_demand'=>($reserved) ));
  
 
}


$records = $DB->get_records_select("sr_waiting_list",'sr_company_representative_id= ? AND sr_process_id = ? AND sr_study_fields_id = ? AND moved_to_rl = 0' ,array($USER->id , $ProcessID , $DSRow[$DSRecordID]->sr_study_fields_id) );
 
foreach ($records as $result=>$record) {
  $edata .= '<tr>
                 <td>' . $record->id. '</td>
                 <td>' . $record->first_name . '</td>
                 <td>' . $record->last_name . '</td>
                 <td>' . $record->date_of_birth . '</td>
                 <td>' . $record->private_email . '</td>
              </tr>
             ';
};
 
// RETURN OUTPUT
echo $edata;
 
?>