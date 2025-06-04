=== WP Views Counter ===
Contributors: etruel, khaztiel, gerarjos14  
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7267TH4PT3GSW  
Tags: views, count visits, post views, post visits, ajax counter  
Requires at least: 3.1  
Tested up to: 6.8  
Requires PHP: 5.6  
Stable tag: 2.0.4  
License: GPLv2  

A lightweight and powerful post views counter. Displays visits in post lists and via shortcode or widget. Ideal for tracking popularity across all post types.

== Description ==

**WP Views Counter** lets you see how many views each post, page or custom post type entry has directly in the WordPress admin list table or with a shortcode.

Perfect for bloggers, marketers and eCommerce site owners, this plugin is designed for speed and simplicity. Whether you're running a WooCommerce shop, an Easy Digital Downloads store, or a content-heavy blog — you'll benefit from knowing what content your users engage with the most.

The counter is AJAX-based, making it efficient and non-intrusive, even on high-traffic sites.

= Why choose WP Views Counter? =

✅ **Fast and lightweight** — built for performance  
✅ **Seamless integration** with all custom post types  
✅ **Easy Digital Downloads compatible**  
✅ **Popular posts widget included**  
✅ **Counts views via shortcode or in admin columns**  
✅ **Multilingual ready**

Unlike bloated analytics plugins, WP Views Counter focuses only on what matters: showing you the view count where you need it.

Developer-friendly on GitHub: https://github.com/Etruel-Developments/wpecounter/issues  
We welcome forks, feedback and pull requests.

== Features ==

* Display post views in the admin post list columns.
* Count views on any custom post type.
* Shortcode `[WPeCounter]` to display views anywhere.
* AJAX-based counting — no page reloads needed.
* Order admin lists by view count.
* Easy Digital Downloads (EDD) integration: track views for Downloads.
* Legacy widget to show most visited posts or products.
* Import views from other counters.
* Fully translatable — multilingual support out of the box.

== Coming Soon ==

* Option to ignore visits from logged-in users or specific roles.
* Choose display position of Views column per post type.

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
= 2.0.4 =
Recommended security and stability update. Compatible with WordPress 6.8.
