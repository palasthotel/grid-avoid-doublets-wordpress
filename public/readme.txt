=== Grid Avoid Doublets ===
Contributors: palasthotel, janaeggebrecht, edwardbock, mkernel
Donate link: http://palasthotel.de/
Tags: grid, landingpage, editor, page, extension
Requires at least: 4.0
Requires PHP: 8.0
Tested up to: 7.1
Stable tag: 1.1.2
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Prevents the same post from showing up twice when several Grid boxes list posts on one page.

== Description ==

Pages built with the Grid plugin often contain several boxes that list recent posts. Without coordination, the same post can appear in more than one of these boxes. This plugin keeps track of the posts that have already been placed while a grid renders and removes them from the queries of the post list boxes that follow.

Themes and custom grid boxes can use the same API to register and check their own posts:

* `grid_avoid_doublets_add( $post_id, $area_id )` marks a post as placed.
* `grid_avoid_doublets_is_placed( $post_id, $area_id )` checks whether a post has already been placed.
* `grid_avoid_doublets_get_placed( $area_id )` returns the IDs of all placed posts.


== Installation ==

1. Upload Plugin zip file to the `/wp-content/plugins/` directory
1. Extract the Plugin to a `grid-avoid-doublets` Folder
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the API Calls in your theme and grid boxes

== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 1.1.2 =
* use grid hook for modifying posts list box query args

= 1.1.1 =
* Not avoiding doublets fix

= 1.1 =
* Compatible to Grid Update 1.6.12

= 1.0 =
* first release

