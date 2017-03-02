<?php

/**
 * @return \GridAvoidDoublets\Plugin
 */
function grid_avoid_doublets_get_plugin(){
	global $grid_avoid_doublets;
	return $grid_avoid_doublets;
}

/**
 * @param integer $content_id
 * @param string | integer $area_id
 */
function grid_avoid_doublets_add($content_id, $area_id = "global"){
	grid_avoid_doublets_get_plugin()->add_content_id($content_id, $area_id);
}

/**
 * @param integer $content_id
 * @param null | string | integer  $area_id
 */
function grid_avoid_doublets_is_placed($content_id, $area_id = null){
	grid_avoid_doublets_get_plugin()->is_placed($content_id, $area_id);
}

/**
 * return array of post ids that are already placed
 * @return array
 */
function grid_avoid_doublets_get_placed($area_id = null){
	grid_avoid_doublets_get_plugin()->get_placed_ids($area_id);
}