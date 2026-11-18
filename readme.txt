=== Astro Hide Unpublished Post Types From Menu ===
Contributors: alian
Tags: post types, unpublished, hide, menu, menus
Requires at least: 5.2
Tested up to: 7.1
Stable tag: 2.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hides from menus all post_types that have a status other than published.

== Description ==
The "Astro Hide Unpublished Post Types From Menu" plugin automatically hides from WordPress navigation menus the items that point to content with a status other than "published": draft, pending, scheduled and private posts, pages and custom post types such as WooCommerce products. This prevents visitors from reaching "page not found" (404) errors and saves the site administrator from updating the menus every time a page is unpublished.

When a hidden item has published sub-items, they move up to the nearest visible level, so they stay reachable. Custom links, category and archive items are never hidden.

The plugin works with classic menus (Appearance > Menus and the Navigation Menu widget). The Navigation block of block themes already hides unpublished pages on its own.

== Installation ==
1. Upload the `astro-hide-unpublished-post-types-from-menu` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Unpublished content is hidden from the navigation menus automatically: there are no settings.

== Frequently Asked Questions ==
= Can I customize which items are hidden? =
Yes, with the astro_hupt_is_item_hidden filter. It receives true when the item points to unpublished content, and the menu item. For example, to keep private pages in the menu for logged-in users:

`add_filter( 'astro_hupt_is_item_hidden', function ( $hidden, $item ) { return $hidden && ! ( is_user_logged_in() && 'private' === get_post_status( $item->object_id ) ); }, 10, 2 );`

= Does the plugin change the admin lists? =
Not anymore. Up to version 1.0.4 it hid unpublished content from the admin lists for users other than administrators, which did not hide anything from the menus and prevented authors and editors from seeing their drafts and the posts awaiting review. Version 2.0.0 removed that behavior.

== Wordefence vendor verification key ==
gsphudo7by90lzwdlihyerqxbzj6jiln

== Changelog ==
= 2.0.0 =
* Fixed: unpublished content is now actually hidden from the navigation menus, as the plugin description states. Previous versions did not touch the menus at all.
* Added: published sub-items of a hidden item move up to the nearest visible level.
* Added: the astro_hupt_is_item_hidden filter, to decide which menu items are hidden.
* Changed: the plugin no longer limits the admin lists to published content for users other than administrators. That behavior hid their drafts from authors, the posts awaiting review from editors, and emptied the Media Library list view. The astro_hupt_parse_query() and astro_hupt_filter_views() functions are deprecated and no longer hooked.

= 1.0.4 =
* Changed: the text domain is now astro-hide-unpublished-post-types-from-menu, matching the plugin slug as required to receive translations from translate.wordpress.org.
* Fixed: the installation instructions referenced the wrong plugin folder name.

= 1.0.3 =
* Compatibility: tested with WordPress 7.1.

= 1.0.2 =
* Changed: the plugin author is now Alian Schiavoncini (https://www.alian.it). The previous AstroThemes website and email address are no longer active.
* Compatibility: tested with WordPress 7.0.
* Changed: added the ASTRO_HUPT_VERSION constant.

= 1.0.1 =
* Added full compatibility with WordPress version 6.8.3
* Added Wordefence vendor verification key

= 1.0.0 =
* Initial version

== Upgrade Notice ==
= 2.0.0 =
The plugin now really hides unpublished content from navigation menus and no longer filters the admin lists for non-administrators. Check your menus after updating.

== License ==
This plugin is licensed under the GPLv2 or later. For more information, see the [GPLv2 license](https://www.gnu.org/licenses/gpl-2.0.html).
