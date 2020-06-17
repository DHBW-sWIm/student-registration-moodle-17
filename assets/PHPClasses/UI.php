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
//



/* For tile group output
 * Tiles must be created in advance and then can be passed as objects to this class using addTile function  
*/
class TileContainer {
    
    public $tiles =[];
       
    public function addTile( CreateTile $tile) {
        $this->tiles[] = $tile;
    }
    public function render() {
        $tiles = $this->tiles;
        echo '<div class="d-flex flex-wrap p-2 bd-highlight"></div>
              <div class="container ">
              <div class="row  justify-content-left">
              <div class="card-deck col-sm-12 text-center">';        
                foreach ($tiles as $tile=>$val){                
                    echo '<div class="card mb-4  shadow-sm text-dark bg-light" style="min-width: 23%; height : 270px;">';
                    echo '<div class="card-header btn-outline-'.$val->color.'">';
                    echo '<h4 class="my-0 font-weight-normal">'.$val->title.'</h4> </div>';                   
                    echo '<div class="card-body ">
                        <ul class="list-unstyled mt-3 mb-4 h-50">';
                        foreach($val->listElement as $element){
                            echo '<li>'.$element.'</li>';
                        }
                    echo'</ul>
                        <form action="'.$val->buttonURL.'">
                        <button type="submit"  class="btn btn-lg btn-block btn-'.$val->color.'"> <i class="'.$val->buttonIcon.'"></i> '.$val->buttonName.' </button>
                        </form>
                    </div>
                    </div>';
                }    
        echo '</div></div></div> ';
    }
}

/* This class creates Bootstrap Cards (Tiles)*/

class CreateTile {
    
    public $title;
    public $buttonName;
    public $buttonURL;
    public $buttonIcon = 'fa fa-user';
    public array $listElement;
    public $color = 'danger';
    
    /*This function sets a title */
    public function setTitle($param) {
       $this->title = $param;
    }
    /*This function sets button name */
    public function setButtonName($param) {
        $this->buttonName = $param;
    }
    /*This function sets the URL*/
    public function setButtonURL($param) {
        $this->buttonURL = $param;
    }
    /* Bootstrap Icon default is fa fa-user */
    public function setButtonIcon($param) {
        $this->buttonIcon = $param;
    }
    /*This function  adds an HTML unordered list element*/
    public function addListElement($param) {
        $this->listElement[] = $param;
    }
    /*This function sets the color of header and button of the tile. Default color is danger 'Bootstrap' */
    public function setColor($param){
        $this->color = $param;
    }
}