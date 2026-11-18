<?php
/**
 * Plugin Name: Astro Hide Unpublished Post Types From Menu
 * Description: Hides from menus all post_types that have a status other than published.
 * Version:           2.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Alian Schiavoncini
 * Author URI:        https://www.alian.it
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       astro-hide-unpublished-post-types-from-menu
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Prevents direct access to the file.
}

define( 'ASTRO_HUPT_VERSION', '2.0.0' );

add_filter( 'wp_nav_menu_objects', 'astro_hupt_filter_nav_menu_objects', 10, 2 );

/**
 * Removes from rendered navigation menus the items that point to content that is not published.
 *
 * Works with every post type (posts, pages, WooCommerce products, custom post types).
 * Custom links, taxonomy and archive items are never touched. The children of a hidden
 * item are moved up to the nearest visible ancestor, so published sub-pages stay reachable.
 *
 * Block themes do not need this: the core Navigation block already skips unpublished pages.
 *
 * @param array    $items Menu items, sorted and with their CSS classes already set.
 * @param stdClass $args  Arguments passed to wp_nav_menu().
 * @return array Menu items to display.
 */
function astro_hupt_filter_nav_menu_objects( $items, $args = null ) {
	if ( empty( $items ) || ! is_array( $items ) ) {
		return $items;
	}

	$hidden_parents = array();
	foreach ( $items as $item ) {
		if ( astro_hupt_is_item_hidden( $item ) ) {
			$hidden_parents[ (int) $item->ID ] = (int) $item->menu_item_parent;
		}
	}

	if ( empty( $hidden_parents ) ) {
		return $items;
	}

	$visible = array();
	foreach ( $items as $key => $item ) {
		if ( isset( $hidden_parents[ (int) $item->ID ] ) ) {
			continue;
		}

		// Moves the item up until its parent is a visible item (or the top level).
		$parent  = (int) $item->menu_item_parent;
		$guard   = 0;
		while ( $parent && isset( $hidden_parents[ $parent ] ) && $guard < 100 ) {
			$parent = $hidden_parents[ $parent ];
			++$guard;
		}
		$item->menu_item_parent = (string) $parent;

		$visible[ $key ] = $item;
	}

	// The "has children" class is set before this filter runs: fix it for parents that lost all their children.
	$parents = array();
	foreach ( $visible as $item ) {
		$parents[ (int) $item->menu_item_parent ] = true;
	}
	foreach ( $visible as $item ) {
		if ( ! isset( $parents[ (int) $item->ID ] ) && is_array( $item->classes ) ) {
			$item->classes = array_values( array_diff( $item->classes, array( 'menu-item-has-children' ) ) );
		}
	}

	return $visible;
}

/**
 * Tells whether a menu item points to content that is not published.
 *
 * @param WP_Post|stdClass $item Menu item.
 * @return bool True if the item must be hidden.
 */
function astro_hupt_is_item_hidden( $item ) {
	$hidden = false;

	if ( isset( $item->type, $item->object_id ) && 'post_type' === $item->type ) {
		// For attachments get_post_status() returns the status of the parent post.
		$hidden = 'publish' !== get_post_status( (int) $item->object_id );
	}

	/**
	 * Filters whether a navigation menu item is hidden.
	 *
	 * @since 2.0.0
	 *
	 * @param bool             $hidden True if the item points to content that is not published.
	 * @param WP_Post|stdClass $item   Menu item.
	 */
	return (bool) apply_filters( 'astro_hupt_is_item_hidden', $hidden, $item );
}

/**
 * Formerly limited the admin lists to published content for non-administrators.
 *
 * No longer hooked: it hid drafts and pending posts from their own authors and from editors,
 * and emptied the Media Library list view, without ever touching the menus.
 *
 * @deprecated 2.0.0
 *
 * @param WP_Query $query Query.
 */
function astro_hupt_parse_query( $query ) {
	_deprecated_function( __FUNCTION__, '2.0.0' );
}

/**
 * Formerly removed the Draft, Pending, Scheduled, Private and Trash views from the admin lists.
 *
 * @deprecated 2.0.0
 *
 * @param array $views Views.
 * @return array Views, unchanged.
 */
function astro_hupt_filter_views( $views ) {
	_deprecated_function( __FUNCTION__, '2.0.0' );
	return $views;
}
