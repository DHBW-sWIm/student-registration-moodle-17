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

global  $CFG;

require_once("$CFG->libdir/formslib.php");


class ST_process_creation extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('select', 'sp', 'Study Program');
        $mform->addElement('select', 'semester', 'Semester');
        $mform->addElement('date', 'stpsd', 'Process start date');
        $mform->addElement('date', 'sd', 'Demand Planning start Date');
        $mform->addElement('date', 'ed', 'Demand Planning End Date');
        $mform->addElement('date', 'srda', 'Seat reservation start date for A');
        $mform->addElement('date', 'sedb', 'Seat reservation start date for B');
        $mform->addElement('date', 'rdl', 'Process end date');
        $mform->addElement('text', 'rdl1', 'Process Name');

         
        $mform->addElement('submit', 'submit', 'Submit');
        $mform->addElement('submit', 'update', 'Update');

    }

    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
};

?>