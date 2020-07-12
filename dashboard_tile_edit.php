<?php



require_once('../../config.php');
global $DB, $PAGE, $OUTPUT, $CFG, $USER;




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = filter_input_array(INPUT_POST);
} else {
  $input = filter_input_array(INPUT_GET);
};

$user = $USER->id;
$rowID =   $input['rowID'];

$PAGE->set_heading('DHBW Student Registration');
$PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php'));
$PAGE->requires->jquery();

echo $OUTPUT->header();
echo $OUTPUT->heading('General settings');
echo '<div id="notification"></div>';

$pluginlist = $DB->get_records_select('sr_management_tiles', '', array(), '', 'distinct plugin');
$plugin = $DB->get_records_select('sr_management_tiles', 'id = ?', array($rowID));



echo '<form action="assets/ajax/update_tile.php">';
echo '<div id="myDIV">

<div class="form-row py-3">
  <div class ="form-group col-md-6">
    <label for="newtitle" class="font-weight-bold">Title </label>
    <input type="text" id="newtitle" name="newtitle" class="form-control" value ="' . $plugin[$rowID]->title . '" placeholder="Title">
  </div>
  <div class ="form-group col-md-6">
    <label for="btnname" class="font-weight-bold">Button Name </label>
    <input type="text" id="btnname"  name="btnname"  class="form-control" value ="' . $plugin[$rowID]->button_name . '" placeholder="Button Name"> 
  </div>
  <div class ="form-group col-md-3">
    <label for="element1" class="font-weight-bold">Element 1 </label>  
    <input type="text" id="element1" name="element1" class="form-control" value ="' . $plugin[$rowID]->list_element_1 . '"  placeholder="Element 1">
  </div>
  <div class = "form-group col-md-3">
    <label for="element2" class="font-weight-bold">Element 2 </label>    
    <input type="text" id="element2" name="element2" class="form-control" value ="' . $plugin[$rowID]->list_element_2 . '" placeholder="Element 2">
  </div>
  <div class = "form-group col-md-3">
    <label for="element3" class="font-weight-bold">Element 3 </label>    
    <input type="text" id="element3"  name="element3" class="form-control" value ="' . $plugin[$rowID]->list_element_3 . '" placeholder="Element 3">
  </div>
  <div class = "form-group col-md-3">
    <label for="element4" class="font-weight-bold">Element 4 </label>   
    <input type="text" id="element4" name="element4" class="form-control" value ="' . $plugin[$rowID]->list_element_4 . '" placeholder="Element 4">
  </div>
    <div class ="form-group col-md-3">
    <label for="element_1_link" class="font-weight-bold">Link for Element 1 </label>  
    <input type="text" id="element_1_link" name="element_1_link" class="form-control" value ="' . $plugin[$rowID]->element_1_link . '"  placeholder="Link for Element 1">
  </div>
  <div class = "form-group col-md-3">
    <label for="element_2_link" class="font-weight-bold">Link for Element 2 </label>    
    <input type="text" id="element_2_link" name="element_2_link" class="form-control" value ="' . $plugin[$rowID]->element_2_link . '" placeholder="Link for Element 2">
  </div>
  <div class = "form-group col-md-3">
    <label for="element_3_link" class="font-weight-bold">Link for Element 3 </label>    
    <input type="text" id="element_3_link"  name="element_3_link" class="form-control" value ="' . $plugin[$rowID]->element_3_link . '" placeholder="Link for Element 3">
  </div>
  <div class = "form-group col-md-3">
    <label for="element_4_link" class="font-weight-bold">Link for Element 4 </label>   
    <input type="text" id="element_4_link" name="element_4_link" class="form-control" value ="' . $plugin[$rowID]->element_4_link . '" placeholder="Link for Element 4">
  </div>
  <div class ="form-group col-md-4">
    <label for="btncolor" class="font-weight-bold">Button Color (e.g. danger) </label>   
    <input type="text" id="btncolor" name="btncolor" class="form-control" value ="' . $plugin[$rowID]->color . '" placeholder="Button Color e.g. danger">
  </div>
  <div class = "form-group col-md-4">
    <label for="btnicon" class="font-weight-bold">Button Icon (e.g. fa fa-user) </label>   
    <input type="text" id="btnicon" name="btnicon" class="form-control" value ="' . $plugin[$rowID]->button_icon . '" placeholder="Button Icon e.g. fa fa-user">
  </div>
  <div class = "form-group col-md-4">
    <label for="btnurl" class="font-weight-bold">Button URL</label>    
    <input type="text" id="btnurl"  name="btnurl" class="form-control" value ="' . $plugin[$rowID]->button_url . '" placeholder="Button URL">
  </div> 
  <div class ="form-group col-md-4">
    <label for="functionName" class="font-weight-bold">Function Name</label>      
    <input type="text"  id="functionName"  name="functionName" class="form-control" value ="' . $plugin[$rowID]->function . '" placeholder="Function Name">
  </div>
  <div class ="form-group col-md-4">
    <label for="tileorder" class="font-weight-bold">Order</label>        
    <input type="number" id="tileorder" name="tileorder" class="form-control" value ="' . $plugin[$rowID]->tile_order . '" placeholder="Order">
  </div>
  <div class = "form-group col-md-4">
    <label for="pluginnames" class="font-weight-bold">Add new Plugin Name or Choose</label>          
    <input type="text" list="pluginnames" list="pluginnames" name="pluginname" id="pluginname" class="form-control" value ="' . $plugin[$rowID]->plugin . '" placeholder="Add new Plugin Name or Choose">
  </div>
  <div class = "form-group col-md-6">
    <label for="moodle_capability" class="font-weight-bold">Moodle Capability</label>            
    <input type="text" id="moodle_capability"  name="moodle_capability" class="form-control" value ="' . $plugin[$rowID]->moodle_capability . '" placeholder="Moodle Capability">
  </div>
  <div class = "form-group col-md-6">
    <label for="taskpath" class="font-weight-bold">Task Path</label>              
    <input type="text" id="taskpath" name="taskpath" class="form-control" value ="' . $plugin[$rowID]->task_path . '" placeholder="Task Path">
  </div>
</br>
<input id="rowID" type="input" name="rowID" value="' . $rowID . '" hidden>
<div class= "align-items-center">
  <div class = "col-auto my-1 py-3">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="true" name ="defaultCheck1" id="defaultCheck1">
      <label class="form-check-label" for="defaultCheck1">Delete</label>
    </div>
  </div>
  <div class = "col-auto my-1">
    <button id="addnewtile" type = "submit" class="btn btn-danger ">Submit</button>   
  </div>
</div>

<div>

</div>
<hr/>
</div>
</div>';

echo '</br>';
echo '
<datalist id="pluginnames">';
foreach ($pluginlist as $plugin => $val) {

  echo '<option value="' . $plugin . '">';
}
echo '</datalist>';
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