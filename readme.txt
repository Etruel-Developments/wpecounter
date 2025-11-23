=== WP Views Counter ===
Contributors: etruel, khaztiel, gerarjos14
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7267TH4PT3GSW
Tags: post views, views counter, popular posts, ajax counter, analytics
Requires at least: 3.1
Tested up to: 6.8.2
Requires PHP: 5.6
Stable tag: 2.1.2
License: GPLv2

Fast, lightweight post views counter. Display views in admin, blocks or shortcodes — no tracking scripts required.

== Description ==

**WP Views Counter** is a lightweight, high-performance plugin that accurately tracks and displays post, page, and custom post type views — directly in the WordPress admin, via shortcode, or with a Gutenberg block.

Built for bloggers, marketers, store owners, and developers, it works seamlessly across all post types — including WooCommerce and Easy Digital Downloads — with minimal impact on your site’s speed. No external scripts. No unnecessary bloat.

This plugin does one job and does it exceptionally well: it tells you which content is getting the most attention.

= Key Benefits =

✅ **Accurate view counts** in admin columns, shortcode, or block  
✅ **Metabox per post** with real-time views and reset button  
✅ **Exclude views from logged-in users or specific roles**  
✅ **Fully AJAX-powered** — no page reloads or slowdowns  
✅ **Works with all post types**, including EDD and WooCommerce  
✅ **Block to display popular posts** — no legacy widgets required  
✅ **Developer-friendly and fully translatable**
✅ **Import views from other plugins**

Whether you're optimizing your content strategy or simply want to know what's working, **WP Views Counter** is the simple and effective alternative to bloated analytics plugins.

📦 Start tracking your most popular content today — with clarity, speed and control.

💡 Developer-friendly: [Contribute on GitHub](https://github.com/Etruel-Developments/wpecounter/issues) — forks and pull requests welcome.

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

= 2.1.2 – Nov 19, 2025 =
* Add security to reset function

= 2.1.1 – Sep 25, 2025 =
* Fixed PHP Warnings displayed in DEBUG mode for Undefined array keys of non-public post-types.

= 2.1 – Jun 13, 2025 =
* Added a post metabox showing view count with a "Reset" button per post.(Deprecated Legacy Widget will be deleted on future release.)
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
Fully compatible with WP 6.8.2. Fixed PHP Warnings displayed in DEBUG mode