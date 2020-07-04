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
 *
 * @package   local_student_registration
 * @copyright 2020 "DHBW Mannheim" 
 * @license   https://moodle.dhbw-mannheim.de/ 
 */

defined('MOODLE_INTERNAL') || die();

function local_student_registration_extend_navigation(global_navigation $nav) {
    $context = context_system::instance();
    
    if(isloggedin()) {
        if(has_capability('local/management_dashbaord:view', $context)){
        $link = new moodle_url('/local/student_registration/index.php');
        $node = $nav->add('Management Dashboard',$link,navigation_node::TYPE_CUSTOM,
        null,
        'student_registration',
        new pix_icon('icon3', 'local_student_registration', 'local_student_registration')
        );
        $node->showinflatnavigation = true;
        }elseif (has_capability('local/student_registration:cr', $context)){
        $link = new moodle_url('/local/student_registration/views/Menu.php');
        $node = $nav->add('Student Registration',$link,navigation_node::TYPE_CUSTOM,
        null,
        'student_registration',
        new pix_icon('icon3', 'local_student_registration', 'local_student_registration')
        );
        $node->showinflatnavigation = true;
        }
    };
}; 

function local_student_registration_extends_navigation(global_navigation $nav){
    local_student_registration_extend_navigation($nav);
}

?>