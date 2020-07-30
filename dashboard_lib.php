<?php
// Camunda Send Email to CR 

function  notify_cr(string $cr)
{
    try {
        $data = array(
            'variables' =>
            array(
                'firstname' =>
                array(
                    'value' => $cr,
                    'type' => 'String',
                ),
            ),
            'businessKey' => 'myBusinessKey',
        );
        $data_string = json_encode($data);
        $ch = curl_init('https://camunda.student-registration.swimdhbw.de/engine-rest/process-definition/key/notify_cr/start');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));

        $result = curl_exec($ch);
        var_dump($result);
    } catch (\Exception $e) {
        echo $e;
    }
}
global $DB, $USER;
function get_tasks_md()
{


    global $DB, $USER;
    $User = $USER->id;


    // get active processes 
    $record1 = $DB->get_records_select('sr_process', 'closed = 0 AND director_id=? ', array($User), '', 'count(*) AS count');
    if (array_key_first($record1) > 1) {
        $msg = 'Active Processes';
    } else {
        $msg = 'Active Process';
    };
    $tasks['#1'] = array('count' => array_key_first($record1), 'task' => $msg, 'notification' => 'success');
    // get open processes of demand planning
    $record2 = $DB->get_records_select('sr_process', 'closed = 0 AND director_id=? AND start_date_for_a > CURRENT_TIMESTAMP', array($User), '', 'count(*) AS count');
    if (array_key_first($record2) > 1) {
        $msg = 'Processes are opend for Demand';
    } else {
        $msg = 'Process is opend for Demand';
    };
    $tasks['#2'] = array('count' => array_key_first($record2), 'task' => $msg, 'notification' => 'success');
    //get processes open for seats reservation
    $record3 = $DB->get_records_select('sr_process', 'closed = 0 AND director_id=? AND start_date_for_a < CURRENT_TIMESTAMP', array($User), '', 'count(*) AS count');
    if (array_key_first($record3) > 1) {
        $msg = 'Processes are opend Seats Reservation';
    } else {
        $msg = 'Process is opend for Seats Reservation';
    };
    $tasks['#3'] = array('count' => array_key_first($record3), 'task' => $msg, 'notification' => 'success');
    //count porcesses about ot end within 15 days
    $record4 = $DB->get_records_select('sr_process', 'closed = 0 AND director_id=? AND DATE_ADD(end_date, INTERVAL -1 MONTH) < CURRENT_TIMESTAMP AND end_date > CURRENT_TIMESTAMP', array($User), '', 'count(*) AS count');
    if (array_key_first($record4) > 1) {
        $msg = 'Processes will end within a month';
    } else {
        $msg = 'Process will end within a month';
    };
    $tasks['#4'] = array('count' => array_key_first($record4), 'task' => $msg, 'notification' => 'warning'); // Bootstrap danger / warning / info ...
    // get processes that should be closed 
    $record5 = $DB->get_records_select('sr_process', 'closed = 0 AND director_id=? AND end_date < CURRENT_TIMESTAMP', array($User), '', 'count(*) AS count');
    if (array_key_first($record5) > 1) {
        $msg = 'Processes passed the deadline';
        $color = 'danger';
    } elseif (array_key_first($record5) == 1) {
        $msg = 'Process passed the deadline';
        $color = 'danger';
    } else {
    };
    $tasks['#5'] = array('count' => array_key_first($record5), 'task' => $msg, 'notification' => $color); // Bootstrap danger / warning / info ...
    // get processes that should be closed 
    $record6 = $DB->get_records_select('sr_process', 'closed = 0 AND director_id=? AND end_date < CURRENT_TIMESTAMP', array($User), '', 'count(*) AS count');
    if (array_key_first($record6) > 1) {
        $msg = 'Processes passed the deadline';
        $color = 'danger';
    } elseif (array_key_first($record6) == 1) {
        $msg = 'Process passed the deadline';
        $color = 'danger';
    } else {
    };
    $tasks['#6'] = array('count' => array_key_first($record6), 'task' => $msg, 'notification' => $color); // Bootstrap danger / warning / info ...

    return $tasks;
}
