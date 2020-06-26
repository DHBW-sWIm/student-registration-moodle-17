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

$title = $input['newtitle'];
$btnname = $input['btnname'];
$btnurl = $input['btnurl'];
$btnicon = $input['btnicon'];
$element1 = $input['element1'];
$element2 = $input['element2'];
$element3 = $input['element3'];
$element4 = $input['element4'];
$btncolor = $input['btncolor'];
$tile_order = strval($input['tileorder']);
$plugin = $input['plugin'];

$row = array('title'=>$title ,'button_name'=>$btnname,'button_url'=>$btnurl,'button_icon'=>$btnicon,'list_element_1'=>$element1 ,'list_element_2'=>$element2, 'list_element_3'=>$element3 ,'list_element_4'=>$element4 ,'color'=>$btncolor,'tile_order'=>$tile_order, 'plugin' =>$plugin);


try {
  $id = $DB->insert_record("sr_management_tiles", $row);

 // $DB->execute("INSERT INTO {sr_management_tiles} ($columnamequoted) VALUES (?)", [$content]);


 
$results =$DB->get_records_select("sr_management_tiles",'',null,'tile_order ASC');
$edata='';
foreach ($results as $result=>$record) {
  $edata .= '<tr>
                 <td>' . $record->id. '</td>
                 <td>' . $record->title. '</td>
                 <td>' . $record->button_name . '</td>
                 <td>' . $record->button_url . '</td>
                 <td>' . $record->button_icon . '</td>
                 <td>' . $record->list_element_1 . '</td>
                 <td>' . $record->list_element_2 . '</td>
                 <td>' . $record->list_element_3 . '</td>
                 <td>' . $record->list_element_4 . '</td>
                 <td>' . $record->color . '</td>
                 <td>' . $record->tile_order . '</td>
                 <td>' . $record->plugin . '</td>
              </tr>
             ';
};

}catch( dml_exception $e) {
  echo $e->getMessage();
}
// RETURN OUTPUT
echo $edata;
 
?>
