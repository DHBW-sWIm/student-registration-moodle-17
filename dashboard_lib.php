<?php

function get_hardware_rental_tasklisk() {
    $content = [];
    $method = 'GET';
    $url = 'https://camunda.hardware-rental.swimdhbw.de/engine-rest/task';
    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json',   
    );
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $responses = json_decode(curl_exec($curl), true);
    curl_close($curl);
    
    foreach ($responses as $response) {
        $content[] = array($response['name'] , $response['due']);
        //$content .= "<br/>";
    }

    return $content;
}

function get_tasks_md(){


    global $DB , $USER;
    $User = $USER->id;



    $record1 = $DB->get_records_select('sr_process','closed = 0 AND director_id=? ' ,array($User) , '','count(*) AS count');
    if(array_key_first($record1) > 1 ){$msg = 'Active Processes';}else {$msg = 'Active Process';};
    $tasks['#1'] = array('count'=> array_key_first($record1) , 'task'=> $msg , 'notification'=> 'success');

    $record2 = $DB->get_records_select('sr_process','closed = 0 AND director_id=? AND start_date_for_a > CURRENT_TIMESTAMP' ,array($User) , '','count(*) AS count');
    if(array_key_first($record2) > 1 ){$msg = 'Processes are opend for Demand';}else {$msg = 'Process is opend for Demand';};
    $tasks['#2'] = array('count'=> array_key_first($record2) , 'task'=> $msg , 'notification'=>'success');

    $record3 = $DB->get_records_select('sr_process','closed = 0 AND director_id=? AND start_date_for_a < CURRENT_TIMESTAMP' ,array($User) , '','count(*) AS count');
    if(array_key_first($record3) > 1 ){$msg = 'Processes are opend Seats Reservation';}else {$msg = 'Process is opend for Seats Reservation';};
    $tasks['#3'] = array('count'=> array_key_first($record3) , 'task'=> $msg , 'notification'=>'success');

    $record4 = $DB->get_records_select('sr_process','closed = 0 AND director_id=? AND DATE_ADD(end_date, INTERVAL -1 MONTH) < CURRENT_TIMESTAMP AND end_date > CURRENT_TIMESTAMP' ,array($User) , '','count(*) AS count');
    if(array_key_first($record4) > 1 ){$msg = 'Processes will end within a month';}else {$msg = 'Process will end within a month';};
    $tasks['#4'] = array('count'=> array_key_first($record4) , 'task'=> $msg , 'notification'=>'warning'); // Bootstrap danger / warning / info ...
    $record5 = $DB->get_records_select('sr_process','closed = 0 AND director_id=? AND end_date < CURRENT_TIMESTAMP' ,array($User) , '','count(*) AS count');
    if(array_key_first($record5) > 1 ) { $msg = 'Processes passed the deadline';  
        $color = 'danger';
    }elseif (array_key_first($record5) == 1 ){ $msg = 'Process passed the deadline';  $color = 'danger' ; }
    else {};
    $tasks['#5'] = array('count'=> array_key_first($record5) , 'task'=> $msg , 'notification'=> $color); // Bootstrap danger / warning / info ...

    $record6 = $DB->get_records_select('sr_process','closed = 0 AND director_id=? AND end_date < CURRENT_TIMESTAMP' ,array($User) , '','count(*) AS count');
    if(array_key_first($record6) > 1 ) { $msg = 'Processes passed the deadline';  
        $color = 'danger';
    }elseif (array_key_first($record6) == 1 ){ $msg = 'Process passed the deadline';  $color = 'danger' ; }
    else {};
    $tasks['#6'] = array('count'=> array_key_first($record6) , 'task'=> $msg , 'notification'=> $color); // Bootstrap danger / warning / info ...
        
    return $tasks ;
}

?>