=== WP Views Counter ===
Contributors: etruel, khaztiel
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7267TH4PT3GSW
Tags: views, count visits, post views, post visits, ajax counter
Requires at least: 3.1
Tested up to: 6.5.4
Requires PHP: 5.6
Stable tag: 2.0.2
License: GPLv2

Visits Post(types) counter. Shown the number of visits on lists of posts, pages and/or custom post types and shortcode.

== Description ==

Knows how much views has every post, page or custom post type, just on wordpress list. 
You can select on plugin settings, which types must count.  Also you can print using shortcode [WPeCounter] in widgets, post or pages. 

Works with Easy Digital Downloads Products to see how many views has every Download, also with other plugins that work with public custom post types.

Is extremely lightweight because it works with ajax.  

Developer and bugtracker on github repository: https://github.com/Etruel-Developments/wpecounter/issues

Feel free to fork it and propose new enhancements or Pull Requests.

= Features =

* Widget with list of most popular posts (types).
* Configurable visit counter by Custom Post Types.
* Allow to "Order By Visits" on all selected Post (types) lists.
* Compatibility with Easy Digital Downloads.
* Allow to import meta-fields from other counters.
* Multilanguage Ready.

= Features Comming Soon =
1. Options to doesn't count logged in users, or per role selected in settings.
1. Select the column order to display the Views column in every post type list.

At initial versions we used "Entry views" script created by Justin Tadlock with GPLv2. 
There is little unmodified code left from those early days, but we are very grateful to him for his work.
Hope you can also enjoy this plugin.

== Frequently Asked Questions ==

= Can I upgrade to version 2.0 without losing the counters from previous versions? =

Yes, the 2.0 major version will import the previous settings and counters. You can also import other custom fields. However, make a backup copy!

== Installation ==

You can either install it automatically from the WordPress admin, or do it manually:

1. Unzip "WP Views Counter" archive and put the folder into your plugins folder (/wp-content/plugins/).
1. Activate the plugin through the 'Plugins' menu in WordPress
1. You'll see a new column with Views on WordPress pages/posts/types list screen.
1. For print use shortcode [WPeCounter] in widgets, post or pages.

== Screenshots ==

1. Admin settings page.
1. Showing on posts list.
1. Legacy Widget to Display Popular Posts (types).

== Changelog ==

= 2.0.2 - Jun 01, 2024 =
* Bump to WP 6.5.4
* Fixes on loading language files.

= 2.0.1 =
* Added Danger Zone in Settings allowing to fix failed imports, duplicated metafields and empty fields processes.
* Improved column order query in all post types lists.
* Fixes the import process that if it uses same Views metafield the duplicate the metafields in each post.
* Updated POT and Spanish language files.

= 2.0 =
1. New name! WP Views Counter. Make it more realistic with its uses.
1. We made a brand new and coded from scratch. More solid. more robust, more lightweight.
1. Added Legacy Widget! With list of most visited posts (types).
1. Fixes the preview count when it only had to count visits at frontend.
1. Language POT and es_ES files updated.
1. Tested on Wordpress 6.0.2

= 1.2 =
1. Added Compatibility with Easy Digital Downloads. 
1. Fixes for many other Custom Post Types. 
1. Moved the Views column to the end.
1. Tested on Wordpress 4.5

= 1.1.2 =
1. Compatible with php 5.4. Lot of Strict Standars PHP Warning and notices fixed.
1. Fix another notice on Entry views script on php>=5.4

= 1.1.1 =
1. Fix some cases of "Call to undefined function get_plugin_data()".
1. Language POT and es_ES files updated.
1. Preparing for popular posts(types) widget. ;)

= 1.1 =
1. Added "Order By Visits" feature on post(types) list.
1. Changed order of Views column to appear left side of comments column in post(type) list.
1. Added Feature Import meta-fields from other counters.
1. Added Serbo-Croatian Language thanks to [Borisa Djuraskovic](http://www.webhostinghub.com)
1. Minnor fix on selecting columns to view in Wordpress display settings.

= 1.0 =
First Release

== Upgrade Notice ==
1. Major Upgrade. 