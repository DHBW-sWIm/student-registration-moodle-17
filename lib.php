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

defined('MOODLE_INTERNAL') || die();

function local_student_registration_extend_navigation(global_navigation $nav) {
    global $CFG, $DB;

    if (isloggedin()) {
        $context = context_system::instance();
        //require_capability('local/student_registration:view', $context, $USER);
        if (!has_capability('local/student_registration:view', $context)) {  
            $link = new moodle_url('/local/student_registration/views/main_view.php');

            $node = $nav->add('Student registration',$link,navigation_node::TYPE_CUSTOM,'/icon.png');

            $node->showinflatnavigation = true;
        }
    }
}
