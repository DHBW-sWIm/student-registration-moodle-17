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

if(has_capability('local/management_dashbaord:view', $context)){

$PAGE->set_heading('DHBW Management Dashboard');
echo $OUTPUT->header();
$USER->id;

if(has_capability('local/management_dashbaord:edit', $context)){
    echo '<button class="btn pull-right" onclick="window.location.href=\'Dashboard_Settings.php\'"><i class="fa fa-cog"></i></button>';
    echo '</br>';echo '</br>';
}

function createTiles(){
    global $DB;
    $tileContainer = new TileContainer();
    $records = $DB->get_records_select('sr_management_tiles', 'plugin = ?' , array('Management Dashboard') ,'tile_order ASC');


    try{
        foreach($records as $record){

          $tile = new CreateTile($record ,context_system::instance());   

          if($tile->build == true){
            $tileContainer->addTile($tile);
        }
      };
      $tileContainer->render();
   
      }catch(Exception $e){

      }
  
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


