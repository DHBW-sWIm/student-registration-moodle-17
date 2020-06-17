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


class formexample extends moodleform
{
    //Add elements to form
    public function definition()
    {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!


        $mform->addElement('text', 'test', 'Text Input Parameter:');
        $mform->setType('test', PARAM_TEXT);
        $mform->addRule('test', 'Please enter a text', 'required');
        
        
        // elements witout further needed configurations 
        $mform->addElement('group', 'test2', 'test group');
        $mform->addElement('checkbox', 'test3', 'test checkbox');
        $mform->addElement('image', 'test4', 'test image');
        $mform->addElement('password', 'test5', 'test password');
        $mform->addElement('radio', 'test6', 'test radio');
        $mform->addElement('button', 'test7', 'test button');
        $mform->addElement('submit', 'test8', 'test submit');
        $mform->addElement('select', 'test9', 'test select');
        $mform->addElement('hiddenselect', 'test10', 'test hiddenselect');
        $mform->addElement('textarea', 'test11', 'test textarea');
        $mform->addElement('link', 'test12', 'test link');
        $mform->addElement('advcheckbox', 'test13', 'test advcheckbox');
        $mform->addElement('static', 'test14', 'test static');
        $mform->addElement('header', 'test15', 'test header');
        $mform->addElement('html', 'test16', 'test html');
        $mform->addElement('hierselect', 'test17', 'test hierselect');
        $mform->addElement('autocomplete', 'test18', 'test autocomplete');
        

    }

    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}