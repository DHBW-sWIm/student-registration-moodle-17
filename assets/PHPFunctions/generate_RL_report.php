<?php

// exproting reservation list of a study field to excel
require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
global $CFG, $DB;
require_once("$CFG->libdir/phpspreadsheet/vendor/autoload.php");
require_login();

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = filter_input_array(INPUT_POST);
} else {
    $input = filter_input_array(INPUT_GET);
};


$temporary_html_file = __DIR__ . '\tmp_html\\' . time() . '.html';
touch($temporary_html_file);
$processID = $input['processID'];
$FID = $input['SFID'];
// records on RL of each company for a spcific study field of an active process  
$where = "WHERE rl.sr_process_id = $processID AND rl.sr_study_fields_id = $FID";
$sort = "ORDER BY company_name ASC";
$sql = "SELECT rl.id, company_name , rl.first_name, rl.last_name , rl.date_of_birth, rl.private_email , rl.contract_status, rl.timecreated 
                FROM {dg_company} AS com
                INNER JOIN {dg_company_representative} AS cr ON com.id = cr.compnay_id
                INNER JOIN {sr_reservation_list} AS rl ON cr.mdl_user_id = rl.sr_company_representative_id      
                {$where}
                GROUP BY rl.id, company_name , rl.first_name, rl.last_name , rl.date_of_birth, rl.private_email , rl.contract_status, rl.timecreated 
                {$sort}";

$recordsjoinRL = $DB->get_records_sql($sql);
$process = $DB->get_record_select('sr_process', 'id = ?', array($processID));
$SFName  = $DB->get_record_select('sr_study_fields', 'id = ?', array($FID));
$process_info = 'Seats Reservation Reporting for ' . $process->program_name . ' ' . $SFName->study_field_name . '-' . $process->semester . ' ' . (new DateTime($process->end_date))->format('Y');
$table = '<h3>' . $process_info . '</h3><table><thead><tr><th>Company Name</th><th>Student First Name</th><th>Student Last Name</th>
<th>Date of Birth</th><th>Email</th><th>Contract Status</th><th>Reservation Time</th></tr></thead><tbody>';
foreach ($recordsjoinRL as $record) {
    $table .= '<tr>
                <td>' . $record->company_name . '</td>
                <td>' . $record->first_name . '</td>
                <td>' . $record->last_name . '</td>
                <td>' . $record->date_of_birth . '</td>
                <td>' . $record->private_email . '</td>
                <td>' . $record->contract_status . '</td>
                <td>' . $record->timecreated . '</td>
              </tr>';
}
$table .= '</tbody></table>';
file_put_contents($temporary_html_file, $table, FILE_APPEND);
$reader = IOFactory::createReader('Html');

try {
    $spreadsheet = $reader->load($temporary_html_file);
} catch (Exception $e) {
    unlink($temporary_html_file);
    exit;
}


$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

$filename = time() . '.xlsx';

$writer->save($filename);

header('Content-Type: application/x-www-form-urlencoded');

header('Content-Transfer-Encoding: Binary');

header("Content-disposition: attachment; filename=\"" . $filename . "\"");

readfile($filename);

unlink($temporary_html_file);

unlink($filename);

exit;
