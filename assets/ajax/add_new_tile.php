<?php

// add new tile (Management dashboard)

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');

global $DB, $USER;
require_login();

$row = array('title' => '', 'user_id' => $USER->id);


try {
  $id = $DB->insert_record("sr_management_tiles", $row);

  $results = $DB->get_records_select("sr_management_tiles", '', null, 'id ASC');
  $edata = '';
  $i = 0;
  foreach ($results as $result => $record) {
    $i++;
    if ($id == $result) {
      $j = ' <b><span class="badge badge-pill badge-warning">' . $i . '</span></b>';
    } else $j = $i;
    $edata .= '<tr rowID ="' . $record->id . '">
                 <td> ' . $j . '</td>
                 <td>' . $record->plugin . '</td>
                 <td>' . $record->title . '</td>                 
              </tr>
             ';
  };
} catch (dml_exception $e) {
  echo $e->getMessage();
}
// RETURN OUTPUT
echo $edata;
