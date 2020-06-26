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

try{
require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/assets/PHPClasses/UI.php');

global $DB, $PAGE, $OUTPUT, $CFG, $USER;
require_login();

$context = context_system::instance();

if(has_capability('local/student_registration:manage', $context)){

$PAGE->set_heading('DHBW Management Dashboard');
echo $OUTPUT->header();


function createTiles(){
    global $DB;
    $tileContainer = new TileContainer();
    $records = $DB->get_records_select('sr_management_tiles', 'plugin = ?' , array('Management Dashboard') ,'tile_order ASC');
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


