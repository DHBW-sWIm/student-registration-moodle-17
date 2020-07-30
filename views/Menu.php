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

try {


  require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
  require_once(dirname(__DIR__) . '/assets/PHPClasses/UI.php');

  global $PAGE, $OUTPUT, $CFG, $USER;
  require_login();
  $user = $USER->id;
  $context = context_system::instance();


  if (has_capability('local/student_registration:manage', $context)) {

    $PAGE->set_heading('Student Registration');
    $PAGE->navbar->add('Management Dashboard', new moodle_url('/local/student_registration/index.php', array('id' => $user)));
    echo $OUTPUT->header();

    function createTiles()
    {
      global $DB;
      $tileContainer = new TileContainer();
      $records = $DB->get_records_select('sr_management_tiles', 'plugin = ?', array('Student Registration'), 'tile_order ASC');

      try {
        foreach ($records as $record) {

          $tile = new CreateTile($record, context_system::instance());

          if ($tile->build == true) {
            $tileContainer->addTile($tile);
          }
        };
        $tileContainer->render();
      } catch (Exception $e) {
      }
    }
    createTiles();




    echo $OUTPUT->footer();
  } elseif (has_capability('local/student_registration:cr', $context)) {

    $PAGE->set_heading('Student Registration');

    echo $OUTPUT->header();

    $tileContainer = new TileContainer();
    $records = $DB->get_records_select('sr_management_tiles', 'plugin = ?', array('Student Registration'), 'tile_order ASC');
    try {
      foreach ($records as $record) {

        $tile = new CreateTile($record, context_system::instance());

        if ($tile->build == true) {
          $tileContainer->addTile($tile);
        }
      };
      $tileContainer->render();
    } catch (Exception $e) {
    }

    echo $OUTPUT->footer();
  } else {
    redirect($CFG->wwwroot);
  };
} catch (Exception $e) {
  echo $e->getMessage();
} catch (InvalidArgumentException $e) {
  echo $e->getMessage();
};
