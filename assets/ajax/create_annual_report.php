<?php

// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

global $DB, $USER;

$count = count($input['selectedreprot']);
if ($input) {
    $FID = $input['selectedreprot'][0][1]; // first study field id
}

try {
    $edata = '<thead style="text-align:center;"><tr>';
    $edata .= '<td>Company Name</td>';


    $yearnew = (new DateTime(($DB->get_record_select('sr_process', 'id=?', array($input['selectedreprot'][0][0]))->end_date)))->format('Y');
    $yearnold = (new DateTime(($DB->get_record_select('sr_process', 'id=?', array($input['selectedreprot'][1][0]))->end_date)))->format('Y');
    $edata .= '<td>Total Demand ' . $yearnew  . '</td>';
    $edata .= '<td>Total Reservation ' . $yearnew . '</td>';
    $edata .= '<td>Total Demand ' . $yearnold  . '</td>';
    $edata .= '<td>Total Reservation ' . $yearnold . '</td>';
    $edata .= '</tr></thead>';
    $edata .= '<tbody style="text-align:center;">';



    $processIDnew = $input['selectedreprot'][0][0];
    $FIDnew = $input['selectedreprot'][0][1];
    $processIDOld = $input['selectedreprot'][1][0];
    $FIDnewOld = $input['selectedreprot'][1][1];
    // count records on RL of each company for a spcific study field of an active process  
    if ($FIDnew == 0) {
        $where = "WHERE rl.sr_process_id = $processIDnew";
    } else {
        $where = "WHERE rl.sr_process_id = $processIDnew AND rl.sr_study_fields_id = $FIDnew";
    }

    $sort = "ORDER BY count(*)";
    $sql = "SELECT company_name, com.id AS ID , count(*) AS total
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_reservation_list} AS rl ON cr.mdl_user_id = rl.sr_company_representative_id      
                {$where}
                GROUP BY company_name
                {$sort}";

    $recordsjoinRLNew = $DB->get_records_sql($sql);
    // count records of the demand for each company for a spcific study field of an active process  

    if ($FIDnew == 0) {
        $where = "WHERE cp.sr_process_id = $processIDnew";
    } else {
        $where = "WHERE cp.sr_process_id = $processIDnew AND cp.sr_study_fields_id = $FIDnew";
    }
    $sql = "SELECT company_name, com.id AS ID , sum(initial_demand) AS totaldemand
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_capacity_planning} cp ON cp.sr_company_representative_id = cr.mdl_user_id
                {$where}
                GROUP BY company_name
                {$sort}";

    $companydemandNew = $DB->get_records_sql($sql);

    if ($FIDnewOld == 0) {
        $where = "WHERE rl.sr_process_id = $processIDOld";
    } else {
        $where = "WHERE rl.sr_process_id = $processIDOld AND rl.sr_study_fields_id = $FIDnewOld";
    }

    $sort = "ORDER BY count(*)";
    $sql = "SELECT company_name, com.id AS ID , count(*) AS total
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_reservation_list} AS rl ON cr.mdl_user_id = rl.sr_company_representative_id      
                {$where}
                GROUP BY company_name
                {$sort}";

    $recordsjoinRLOld = $DB->get_records_sql($sql);
    // count records of the demand for each company for a spcific study field of an active process  

    if ($FIDnewOld == 0) {
        $where = "WHERE cp.sr_process_id = $processIDOld";
    } else {
        $where = "WHERE cp.sr_process_id = $processIDOld AND cp.sr_study_fields_id = $FIDnewOld";
    }
    $sql = "SELECT company_name, com.id AS ID , sum(initial_demand) AS totaldemand
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_capacity_planning} cp ON cp.sr_company_representative_id = cr.mdl_user_id
                {$where}
                GROUP BY company_name
                {$sort}";

    $companydemandOld = $DB->get_records_sql($sql);

    // loop on the array with highest number of keys 
    if (count($recordsjoinRLNew) >= count($companydemandNew && count($recordsjoinRLNew) >= count($recordsjoinRLOld)
        && count($recordsjoinRLNew) >= count($companydemandOld))) {

        foreach ($recordsjoinRLNew as $item) {
            $edata .= '<tr>';
            $edata .= '<td>' . $item->company_name . '</td>';
            $edata .= '<td>' . $companydemandNew[$item->company_name]->totaldemand . '</td>';
            $edata .= '<td>' . $item->total . '</td>';
            $edata .= '<td>' . $companydemandOld[$item->company_name]->totaldemand . '</td>';
            $edata .= '<td>' . $recordsjoinRLOld[$item->company_name]->total . '</td>';
            $edata .= '</tr>';
        }
    } else {

        if (count($companydemandNew) >= count($recordsjoinRLOld) && count($companydemandNew) >= count($companydemandOld)) {
            foreach ($companydemandNew as $item) {
                $edata .= '<tr>';
                $edata .= '<td>' . $item->company_name . '</td>';
                $edata .= '<td>' . $item->totaldemand . '</td>';
                $edata .= '<td>' . $recordsjoinRLNew[$item->company_name]->total . '</td>';
                $edata .= '<td>' . $companydemandOld[$item->company_name]->totaldemand . '</td>';
                $edata .= '<td>' . $recordsjoinRLOld[$item->company_name]->total . '</td>';
                $edata .= '</tr>';
            }
        } else {
            if (count($recordsjoinRLOld) >= count($companydemandOld)) {
                foreach ($recordsjoinRLOld as $item) {
                    $edata .= '<tr>';
                    $edata .= '<td>' . $item->company_name . '</td>';
                    $edata .= '<td>' . $companydemandNew[$item->company_name]->totaldemand . '</td>';
                    $edata .= '<td>' . $recordsjoinRLNew[$item->company_name]->total . '</td>';
                    $edata .= '<td>' . $companydemandOld[$item->company_name]->totaldemand . '</td>';
                    $edata .= '<td>' . $item->total . '</td>';
                    $edata .= '</tr>';
                }
            } else {
                foreach ($companydemandOld as $item) {
                    $edata .= '<tr>';
                    $edata .= '<td>' . $item->company_name . '</td>';
                    $edata .= '<td>' . $companydemandNew[$item->company_name]->totaldemand . '</td>';
                    $edata .= '<td>' . $recordsjoinRLNew[$item->company_name]->total . '</td>';
                    $edata .= '<td>' . $item->totaldemand . '</td>';
                    $edata .= '<td>' . $recordsjoinRLOld[$item->company_name]->total . '</td>';
                    $edata .= '</tr>';
                }
            }
        }
    }

    $edata .= '</tbody>';
} catch (dml_exception $e) {
    echo $e->getMessage();
}
// RETURN OUTPUT
echo $edata;
