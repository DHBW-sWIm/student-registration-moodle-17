<?php
// This file is part of Moodle - http://moodle.org/
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

defined ( 'MOODLE_INTERNAL' ) || die();

$capabilities = array(
    'local/student_registration:manage' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_USER,
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'manager' => CAP_ALLOW
        )),
        'local/management_dashbaord:edit' => array(
            'captype' => 'write',
            'contextlevel' => CONTEXT_USER,
            'contextlevel' => CONTEXT_SYSTEM),
        'local/management_dashbaord:view' => array(
            'captype' => 'read',
            'contextlevel' => CONTEXT_USER,
            'contextlevel' => CONTEXT_SYSTEM,
            'archetypes' => array(
                'manager' => CAP_ALLOW
            )),
        'local/student_registration:cr' => array(    
            'captype' => 'write',
            'contextlevel' => CONTEXT_SYSTEM)
);

?>