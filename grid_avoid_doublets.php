<?php

/**
 * Plugin Name: Grid Avoid Doublets
 * Plugin URI: https://github.com/palasthotel/grid-avoid-doublets-wordpress
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
	 * GridAvoidDoublets constructor.
	 */
	public function __construct() {
		$this->clear();
		add_action("plugins_loaded",array($this, "init"));
	}
	
	/**
	 * init plugin after plugins are loaded
	 */
	public function init(){
		if(class_exists("\\Grid\\Constants\\Hook")){
			add_action("grid_".\Grid\Constants\Hook::GRID_RENDER_BEFORE, array($this, "grid_render_before"));
		}
	}
	
	/**
	 * fired before a grid renders
	 * @param $args array
	 */
	public function grid_render_before($args){
		$grid_id = $args["grid"];
		$editmode = $args["editmode"];
		if(!$editmode){
			$this->clear($grid_id);
		}
	}
	
	/**
	 * clears the blacklist
	 * @param null|string|integer $grid_id
	 */
	public function clear($grid_id = null){
		if( $grid_id == null ){
			$this->areas = array();
			return;
		}
		$this->areas[$grid_id] = array();
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
	 * @param integer $content_id
	 * @param self::GLOBAL_KEY|integer|null $grid_id
	 *
	 * @return bool
	 */
	public function isPlaced( $content_id, $area_id = null){
		if( $area_id != null){
			/**
			 * have a look in the specific grid
			 */
			return ( isset($this->areas[$area_id]) && in_array( $content_id, $this->areas[$area_id]));
		}
		
		/**
		 * have a look on all placed posts in all grids
		 */
		foreach ($this->areas as $_area_id => $_content_id){
			if( $_content_id == $content_id) return true;
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
 * @param integer $content_id
 * @param string | integer $area_id
 */
function grid_avoid_doublets_add($content_id, $area_id = "global"){
	global $grid_avoid_doublets;
	$grid_avoid_doublets->addContentId($content_id, $area_id);
}

/**
 * @param integer $content_id
 * @param null | string | integer  $area_id
 */
function grid_avoid_doublets_is_placed($content_id, $area_id = null){
	global $grid_avoid_doublets;
	$grid_avoid_doublets->isPlaced($content_id, $area_id);
}