<?php
//add new study program

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");

// Get value from ajax
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

global $DB, $USER;

$user =  $USER->id;
require_login();
if (isset($input['valid_from'])) {
}
$input['valid_from'] = '2000-01-01';
if (isset($input['valid_to'])) {
}
$input['valid_to'] = '2000-01-01';

$id = $DB->insert_record("sr_study_programs", array('study_program_name' => $input['study_program_name'], 'description' => $input['description'], 'valid_from' => $input['valid_from'], 'valid_to' => $input['valid_to'], 'old' => 0));

$records = $DB->get_records_select("sr_study_programs", 'old = ?', array(0));

foreach ($records as $result => $record) {
  $vf =  date("Y-m-d", strtotime($record->valid_from));
  $vt = date("Y-m-d", strtotime($record->valid_to));
  if ($record->valid_from > date("Y-m-d h-m-s", time())) {
    $padg = '<b><span class="badge badge-pill badge-info"> Valid From:  </span></b>';
  } else $padg = '<b><span class="badge badge-pill badge-success"> Valid From:  </span></b>';
  if ($record->valid_to > date("Y-m-d h-m-s", time())) {
    $padg2 = '<b><span class="badge badge-pill badge-warning"> Valid To:  </span></b>';
  } else $padg2 = '<b><span class="badge badge-pill badge-danger"> Valid To:  </span></b>';

  $vfvt = $padg . ' ' . $vf . ' ' . $padg2 . ' ' . $vt;

  $edata .= '<tr ProgramID = "' . $record->id . '">
                 <td style="text-align:center;"><h5><b><span class="badge badge-pill badge-light"> Add new study fields </span></b></h5></td>
                 <td style="text-align:center;">' . $record->study_program_name . '</td>
                 <td style="text-align:center;">' . $vfvt . '</td>
              </tr>
             ';
};

// RETURN OUTPUT
echo $edata;
