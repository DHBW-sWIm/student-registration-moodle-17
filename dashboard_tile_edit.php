<?php 



require_once('../../config.php');
global $DB, $PAGE, $OUTPUT, $CFG , $USER;




if ($_SERVER['REQUEST_METHOD']=='POST') {
    $input = filter_input_array(INPUT_POST);
  } else {
    $input = filter_input_array(INPUT_GET);
  };
  
$user = $USER->id;
$rowID =   $input['rowID'];

$PAGE->set_heading('DHBW Student Registration');
$PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php'));
$PAGE->requires->jquery();

echo $OUTPUT->header();
echo $OUTPUT->heading('General settings');
echo '<div id="notification"></div>';

$pluginlist = $DB->get_records_select('sr_management_tiles' , '' ,array() ,'' , 'distinct plugin');
$plugin = $DB->get_records_select('sr_management_tiles' , 'id = ?' ,array($rowID));



echo '<form action="assets/ajax/update_tile.php">';
echo '<div id="myDIV">
<div class="form">
       <div class="row">
        <div class="col justify-content-center" >
    <div class ="row justify-content-center">


  <input type="text" id="newtitle" name="newtitle" class="form-control col-6" value ="'.$plugin[$rowID]->title.'" placeholder="Title">
 
  <input type="text" id="btnname"  name="btnname"  class="form-control col-6" value ="'.$plugin[$rowID]->button_name.'" placeholder="Button Name">
  
  </div>
  <div class ="row justify-content-center">
  <input type="text" id="element1" name="element1" class="form-control col-3" value ="'.$plugin[$rowID]->list_element_1.'"  placeholder="Element 1">
  <input type="text" id="element2" name="element2" class="form-control col-3" value ="'.$plugin[$rowID]->list_element_2.'" placeholder="Element 2">
  <input type="text" id="element3"  name="element3" class="form-control col-3" value ="'.$plugin[$rowID]->list_element_3.'" placeholder="Element 3">
  <input type="text" id="element4" name="element4" class="form-control col-3" value ="'.$plugin[$rowID]->list_element_4.'" placeholder="Element 4">
  </div>
  <div class ="row justify-content-center">
  <input type="text" id="btncolor" name="btncolor" class="form-control col-4" value ="'.$plugin[$rowID]->color.'" placeholder="Button Color e.g. danger">
  <input type="text" id="btnicon" name="btnicon" class="form-control col-4" value ="'.$plugin[$rowID]->button_icon.'" placeholder="Button Icon e.g. fa fa-user">
  <input type="text" id="btnurl"  name="btnurl" class="form-control col-4" value ="'.$plugin[$rowID]->button_url.'" placeholder="Button URL">
  </div> 

  <div class ="row justify-content-center">
  <input type="text"  id="functionName"  name="functionName" class="form-control col-4" value ="'.$plugin[$rowID]->function.'" placeholder="Function Name">
  <input type="number" id="tileorder" name="tileorder" class="form-control col-4" value ="'.$plugin[$rowID]->tile_order.'" placeholder="Order">
  <input type="text" list="pluginnames" list="pluginnames" name="pluginname" id="pluginname" class="form-control col-4" value ="'.$plugin[$rowID]->plugin.'" placeholder="Add new Plugin Name or Choose">
      </div>
      <div class ="row justify-content-center">
      <input type="text" id="moodle_capability"  name="moodle_capability" class="form-control col-6" value ="'.$plugin[$rowID]->moodle_capability.'" placeholder="Moodle Capability">
      <input type="text" id="taskpath" name="taskpath" class="form-control col-6" value ="'.$plugin[$rowID]->task_path.'" placeholder="Task Path">
      
      </div>
       </div>
     </div>
     </br>
<input id="rowID" type="input" name="rowID" value="'.$rowID.'" hidden>
<div class="form-check" >
<input class="form-check-input" type="checkbox" value="true" name ="defaultCheck1" id="defaultCheck1">
<label class="form-check-label" for="defaultCheck1">
  Delete
</label>

</div>
<hr/>
<button id="addnewtile" type = "submit" class="btn btn-danger ">Edit</button>   
<div>

</div>
<hr/>
</div>
</div>';

echo'</br>';
echo '
<datalist id="pluginnames">';
foreach($pluginlist as $plugin=>$val){
    
  echo '<option value="'.$plugin.'">';
}
echo'</datalist>';
echo '</form>';
?>

<script>


function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}



</script>

