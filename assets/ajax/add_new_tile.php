<?php
 
// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.
 
if (!isset($_SERVER['HTTP_X_REQUESTED_WITH'])) die("Nothing to see here");
 

if ($_SERVER['REQUEST_METHOD']=='POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

require_once(dirname(dirname(dirname(dirname(__DIR__)))) . '/config.php');
 
// PHP QUESTION TO MYSQL DB
 
// Connect to DB
 
  /*  Your code for new connection to DB*/
 
// Php question
 
  /*  Your code for insert data to DB */


global $DB, $USER;



$row = array('title'=>'' , 'user_id' => $USER->id);


try {
  $id = $DB->insert_record("sr_management_tiles", $row);

 // $DB->execute("INSERT INTO {sr_management_tiles} ($columnamequoted) VALUES (?)", [$content]);


 
$results =$DB->get_records_select("sr_management_tiles",'',null,'id ASC');
$edata='';
$i=0;
foreach ($results as $result=>$record) {
  $i++;
  if($id == $result ) {$j = ' <b><span class="badge badge-pill badge-warning">' .$i . '</span></b>';} else $j = $i;
  $edata .= '<tr rowID ="'.$record->id.'">
                 <td style="text-align:center;"> '.$j.'</td>
                 <td style="text-align:center;">' . $record->plugin . '</td>
                 <td style="text-align:center;">' . $record->title. '</td>                 
              </tr>
             ';
};

}catch( dml_exception $e) {
  echo $e->getMessage();
}
// RETURN OUTPUT
echo $edata;
 
?>
