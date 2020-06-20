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


/* 
 *Live editable table (Ajax and JQuery)  
 */ 

GLOBAL $DB;

  class liveTable extends html_table {

    

    public function __construct(String $tableName,String $id, Array $attributes)
    {
        GLOBAL $DB;
        $records = $DB->get_records_select($tableName,'closed=0' ,$attributes );
        

        
        foreach($attributes as $attribute){

        }


        
    }


     

    

}