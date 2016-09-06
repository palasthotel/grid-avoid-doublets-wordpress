<?php

/**
 * Plugin Name: Grid Avoid Doublets
 * Plugin URI: https://github.com/palasthotel/grid-avoid-doubletts-wordpress
 * Description: Avoid doublets API while rendering grids
 * Version: 1.0
 * Author: Palasthotel <rezeption@palasthotel.de> (in person: Edward Bock, Enno Welbers)
 * Author URI: http://www.palasthotel.de
 * License: http://www.gnu.org/licenses/gpl GPLv3
 * @copyright Copyright (c) 2014, Palasthotel
 * @package Palasthotel\Grid-WordPress-Box-Social
 */

class GridAvoidDoublets{
	
	/**
	 * array key for not grid specific post ids
	 */
	const GLOBAL_KEY = "__global__";
	
	/**
	 * @var array
	 */
	private $areas;
	
	/**
	 * GridAvoidDoubletts constructor.
	 */
	public function __construct() {
		$this->areas = array();
		
		// TODO: on action start render clear
		
	}
	
	/**
	 * add an placed contents for example and post id in a grid id
	 *
	 * @param integer $content_id unique for area
	 * @param null|integer|string $area_id id for area
	 *
	 */
	public function addContentId( $content_id, $area_id = null){
		/**
		 * if no grid id set to global index
		 */
		if( $area_id == null) $area_id = self::GLOBAL_KEY;
		
		/**
		 * create new entry for grid id
		 */
		if(!isset($this->areas[$area_id])){
			$this->areas[$area_id] = array();
		}
		
		/**
		 * add post id if not already set
		 */
		if(!in_array( $content_id, $this->areas[$area_id])){
			$this->areas[$area_id][] = $content_id;
		}
	}
	
	/**
	 * check if post is already placed
	 * @param integer $post_id
	 * @param self::GLOBAL_KEY|integer|null $grid_id
	 *
	 * @return bool
	 */
	public function isPlaced($post_id, $grid_id = null){
		if($grid_id != null){
			/**
			 * have a look in the specific grid
			 */
			return ( isset($this->areas[$grid_id]) && in_array($post_id, $this->areas[$grid_id]));
		}
		
		/**
		 * have a look on all placed posts in all grids
		 */
		foreach ($this->areas as $_grid_id => $_post_id){
			if($_post_id == $post_id) return true;
		}
		
		/**
		 * if nothing found its not placed
		 */
		return false;
	}
	
}

/**
 * initialize
 */
global $grid_avoid_doublets;
$grid_avoid_doublets = new GridAvoidDoublets();

/**
 * @param integer $post_id
 * @param string | integer $grid_id
 */
function grid_avoid_doublets_add($post_id, $grid_id = "global"){
	global $grid_avoid_doublets;
	$grid_avoid_doublets->addContentId($post_id, $grid_id);
}

/**
 * @param integer $post_id
 * @param null | string | integer  $grid_id
 */
function grid_avoid_doublets_is_placed($post_id, $grid_id = null){
	global $grid_avoid_doublets;
	$grid_avoid_doublets->isPlaced($post_id, $grid_id);
}