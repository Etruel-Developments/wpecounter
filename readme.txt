=== WP Views Counter ===
Contributors: etruel, khaztiel, gerarjos14
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7267TH4PT3GSW
Tags: views, count visits, post views, post visits, ajax counter
Requires at least: 3.1
Tested up to: 6.8.2
Requires PHP: 5.6
Stable tag: 2.1
License: GPLv2

Track your content’s popularity with a fast, reliable post views counter. Smarter and lighter than other bloated plugins. 

== Description ==

== Description ==

**WP Views Counter** is a lightweight yet powerful plugin to track how many times each post, page, or custom post type is viewed — from inside the WordPress admin or via shortcode or block.

Designed for performance and precision, it's ideal for blogs, WooCommerce stores, or Easy Digital Downloads shops where knowing what content attracts the most attention is critical.

Unlike bloated analytics solutions, WP Views Counter focuses on just one thing — accurate view tracking — without slowing down your site.

= Why choose WP Views Counter over other counters? =

✅ **Accurate post view counts** shown in the admin list, shortcodes, or blocks
✅ **Metabox in each post** with manual reset option
✅ **Exclude views by user role** or logged-in users
✅ **Works with all post types** and EDD
✅ **Lightweight AJAX-based tracking** — no page reloads
✅ **Gutenberg block included** to display most viewed content
✅ **Import views from other plugins**
✅ **Multilingual and developer-friendly**

Track content performance at a glance and optimize your content strategy with a tool that just works — no tracking scripts, no third-party APIs, and no clutter.

Developer-friendly: [GitHub Repository](https://github.com/Etruel-Developments/wpecounter/issues)
Contributions, forks and feedback welcome.

== Frequently Asked Questions ==

= Can I upgrade from an older version without losing data? =
Yes. Version 2.0+ automatically imports your previous data and settings. You can also manually import custom view fields. Always make a backup first.

== Installation ==

1. Upload the plugin to `/wp-content/plugins/` and unzip it.
2. Activate via the **Plugins** menu in WordPress.
3. Configure settings under **Settings → WP Views Counter**.
4. Use `[WPeCounter]` in content or widgets to display views.

== Screenshots ==

1. Admin settings page.
2. Views displayed in post list.
3. Widget showing popular posts by views.

== Changelog ==

= 2.1 – Jun 11, 2025 =
* Added a post metabox showing view count with a "Reset" button per post.
* Introduced a Gutenberg block to replace the legacy popular posts widget.
* New option to choose the Views column position in each post type list.
* Added feature to exclude logged-in users (or by role) from the view count.
* New tools to reset all view counters or by post type.

= 2.0.4 – Jun 4, 2025 =
* Improved output sanitization and escaping for enhanced security.
* Fixed minor issues reported in compatibility scans.
* General code quality and performance improvements.
* Fully compatible with WordPress 6.8.

= 2.0.3 – Apr 22, 2025 =
* Fixed incorrect posts order when clicking on the Views column header.
* Fixed a fatal error occurring during plugin uninstallation.
* Bump to WP 6.8

= 2.0.2 – Jun 01, 2024 =
* Bump to WP 6.5.4
* Fixes on loading language files.

= 2.0.1 =
* Added Danger Zone in Settings allowing to fix failed imports, duplicated metafields and empty fields processes.
* Improved column order query in all post types lists.
* Fixes the import process that if it uses same Views metafield the duplicate the metafields in each post.
* Updated POT and Spanish language files.

= 2.0 =
* New name: WP Views Counter — better reflects its functionality.
* Rewritten from scratch — faster, more robust, more extensible.
* New Legacy Widget with list of most visited posts.
* Fixes frontend-only view counting behavior.
* Updated language files and tested with WordPress 6.0.2.

= 1.2 =
* Added compatibility with Easy Digital Downloads.
* Fixes for other Custom Post Types.
* Adjusted Views column position.

= 1.1.2 =
* Fixed PHP warnings and strict standard issues (5.4+).
* Improved compatibility.

= 1.1.1 =
* Fixed "Call to undefined function get_plugin_data()" error.
* Updated translations.

= 1.1 =
* New: “Order by Visits” on admin post list.
* Views column position changed next to Comments column.
* Meta-field importer for other counter plugins.
* Added Serbo-Croatian translation.

= 1.0 =
Initial Release.

== Upgrade Notice ==
= 2.1 =
Major Update. New block. Compatible with WordPress 6.8.2
