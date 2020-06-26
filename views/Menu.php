<?php


// This file is part of the Local Analytics plugin for Moodle
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Information about the version of the plugin.
 *
 * @package   local_student_registration
 * @copyright 2020 "DHBW Mannheim" 
 * @license   https://moodle.dhbw-mannheim.de/ 
 */


 /* This document should contain the Student registration main menu 
  * from here we navigate to Student Registration porcess creation tile & to Status Overview tile 
  * Settings tile is optional 
  */
try{
  
require_once(dirname(dirname(dirname(__DIR__))) . '/config.php');
require_once(dirname(__DIR__) . '/assets/PHPClasses/UI.php');

global $PAGE, $OUTPUT, $CFG, $USER;
require_login();
$user = $USER->id;
$context = context_system::instance();

if(has_capability('local/student_registration:manage', $context)){

  $PAGE->set_heading('Student Registration');
  $PAGE->navbar->add('Management Dashboard',new moodle_url('/local/student_registration/index.php', array('id' => $user)));  
  echo $OUTPUT->header();

  function createTiles(){
    global $DB;
    $tileContainer = new TileContainer();
    $records = $DB->get_records_select('sr_management_tiles', 'plugin = ?' , array('Student Registration') ,'tile_order ASC');
    foreach($records as $record){
        $tile = new CreateTile();
        $tile->setTitle($record->title);
        $tile->setButtonName($record->button_name);
        $tile->setButtonURL($record->button_url);
        $tile->setButtonIcon($record->button_icon);
        $tile->setColor($record->color);
        $tile->setTaskListItem($record->list_element_1 , 'task_1');
        $tile->setTaskListItem($record->list_element_2, 'task_2');
        $tile->setTaskListItem($record->list_element_3, 'task_3');
        $tile->setTaskListItem($record->list_element_4, 'task_4');
        $tileContainer->addTile($tile);   
    };
    $tileContainer->render();
}
createTiles();




  echo $OUTPUT->footer();

}elseif (has_capability('local/student_registration:cr', $context)){

  $PAGE->set_heading('Student Registration');

  echo $OUTPUT->header();

  $tiledp = new CreateTile();
  $tiledp->setTitle('Demand Planning');
  $tiledp->setButtonName('Manage');
  $tiledp->setButtonURL(new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_DP.php?#FetchMode=DP&CR', array('FetchMode'=>'DP'),'helloll'));
  $tiledp->addListElement('Start your Demand Planning');

  $tilesr = new CreateTile();
  $tilesr->setTitle('Student Registration');
  $tilesr->setButtonName('Manage');
  $tilesr->setButtonURL(new moodle_url('/local/student_registration/views/ST_process/ST_active_process_CR_ST.php?#FetchMode=ST&CR', array('FetchMode'=>'ST'),'Hellp'));
  $tilesr->addListElement('Start registering your students');

  $tileContainer = new TileContainer();
  $tileContainer->addTile($tiledp);
  $tileContainer->addTile($tilesr);

  $tileContainer->render();

  echo $OUTPUT->footer();

}else {
  redirect($CFG->wwwroot);
};
}
catch (Exception $e) {
    echo $e->getMessage();
}
catch (InvalidArgumentException $e) {
    echo $e->getMessage();
};

?>