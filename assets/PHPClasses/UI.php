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
    
    public $tiles;
       
    public function addTile( CreateTile $tile) {
        $this->tiles[] = $tile;
    }
    public function render() {
        $tiles = $this->tiles;
        $count = count($tiles);
        $iterations = (int)($count/3) + 1;
        $counter = 0;
        $countdown = $count;
        
        for($i=0 ; $i < $iterations ; $i++){

        
        echo '</br>
              
              <div class=" justify-content-center">
              <div class="row text-center  justify-content-center">';        
                for ($j = 0 ; $j < 3 ; $j++){   
                    if(!isset($tiles[$counter])){
                    break;
                    }  
                    echo '<div class="col-lg-4">';             
                    echo '<div class="card shadow mb-4 text-dark bg-light" style="min-width: 320px; height : 260px;">';
                    echo '<div class="card-header btn-outline-'.$tiles[$counter]->color.'">';
                    echo '<h4 class="my-0 font-weight-normal">'.$tiles[$counter]->title.'</h4> </div>';                   
                    echo '<div class="card-body">
                        <ul class="list-unstyled mt-2 mb-4 h-50">';
                        foreach($tiles[$counter]->listElement as $element){
                            echo '<li>'.$element.'</li>';
                        }
                    echo'</ul>
                        <form action="'.$tiles[$counter]->buttonURL.'">
                        <button type="submit"  class="btn btn-lg btn-block btn-'.$tiles[$counter]->color.'"> <i class="'.$tiles[$counter]->buttonIcon.'"></i> '.$tiles[$counter]->buttonName.' </button>
                        </form>
                    </div>
                    </div></div>';
                    $counter++;
                    
                }    
        echo '</div></div> ';
    }
    
    }
};

/* This class creates Bootstrap Cards (Tiles)*/

class CreateTile {
    
    public $title;
    public $buttonName;
    public $buttonURL;
    public $buttonIcon = 'fa fa-user';
    public $listElement;
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
        $this->buttonURL = $this->adjustLink($param);
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
    public function adjustLink(string $param){
        $link = new moodle_url($param);
        return $link;
    }
    public function setTaskListItem($link , $taskName){
        
        try{
            if(is_file($link)){
              if(strpos($link , '.')){
                $link = new moodle_url($link);
                 include_once($link);
                 $link = get_tasks();
                $link = $link[$taskName];
                if(!isset($link['count'])){
                    $link['count'] = '';
                }elseif(!isset($link['notification'])){
                    $link['notification'] = 'warning';
                }
                $link = '<h6><b><span class="badge badge-pill badge-'.$link['notification'].'">'.$link['count'].'</span> '.$link['task'].'</b></h6>';
              }
            }
            
        }catch(\Exception $e){
        $this->listElement[]= 'Path is invalid';
        }
        $this->listElement[] = $link;
    }
};

?>

