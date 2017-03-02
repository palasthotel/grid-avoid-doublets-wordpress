<?php

/**
 * Plugin Name: Grid Avoid Doublets
 * Plugin URI: https://github.com/palasthotel/grid-avoid-doublets-wordpress
 * Description: Avoid doublets API while rendering grids
 * Version: 1.1
 * Author: Palasthotel <rezeption@palasthotel.de> (in person: Edward Bock, Enno Welbers)
 * Author URI: http://www.palasthotel.de
 * License: http://www.gnu.org/licenses/gpl GPLv3
 * @copyright Copyright (c) 2014, Palasthotel
 * @package Palasthotel\Grid-WordPress-Box-Social
 */

namespace GridAvoidDoublets;

class Plugin{
	
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
		if(defined('\Grid\Constants\Hook::WILL_RENDER_GRID')){
			add_action("grid_".\Grid\Constants\Hook::WILL_RENDER_GRID, array($this, "grid_render_before"));
		}
	}
	
	/**
	 * fired before a grid renders
	 * @param $args object
	 */
	public function grid_render_before($args){
		$grid_id = $args->grid->gridid;
		$editmode = $args->editmode;
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
	public function add_content_id( $content_id, $area_id = null){
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
	public function is_placed( $content_id, $area_id = null){
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
	
	/**
	 * @param null|string|integer $area_id
	 *
	 * @return array
	 */
	public function get_placed_ids($area_id = null){
		if($area_id != null) return (isset($this->areas[$area_id]))? $this->areas[$area_id]: array();
		$all = array();
		foreach ($this->areas as $area_id => $content_ids){
			$all = array_merge($all, $content_ids);
		}
		return $all;
	}
}

/**
 * initialize
 */
global $grid_avoid_doublets;
$grid_avoid_doublets = new Plugin();

require_once "public-functions.php";