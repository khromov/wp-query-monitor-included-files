=== Query Monitor: Included files ===
Contributors: khromov
Tags: debugging, query monitor
Requires at least: 3.5
Tested up to: 4.2
Stable tag: 1.3
License: GPL2

Shows number of included files in admin bar and in the footer of each page load. Requires Query Monitor.

== Description ==
This plugin lets you see the number of included files for each page load in the admin bar. You can also see the full list of included files and the order in which they were included.

**Usage**

*Basic usage*

Install this plugin, install Query Monitor. Check the admin bar.

== Requirements ==
* PHP 5.3 or higher

== Translations ==
* None

== Installation ==
1. Upload the `query-monitor-included-files` folder to `/wp-content/plugins/`
2. Activate the plugin (Query Monitor: Included files) through the 'Plugins' menu in WordPress
3. Use the functionality via the admin bar

== Screenshots ==

1. None yet

== Changelog ==

= 1.3 =
* Added aggregated totals
* Added OPCache memory consumption per file/totals

= 1.2 =
* Added support for showing total number of files included by component
* Added support for showing total filesize of included files by component

= 1.1 =
* Now shows which component loaded each file (Core, plugins, theme)
* File names are now escaped before output

= 1.0 =
* Initial release
