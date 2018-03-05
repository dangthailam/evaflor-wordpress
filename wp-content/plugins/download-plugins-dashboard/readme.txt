=== Download Plugins and Themes from Dashboard ===
Contributors: algoritmika,anbinder
Donate link: https://www.paypal.me/anbinder
Tags: download,plugin,theme,zip,dashboard
Requires at least: 3.1
Tested up to: 4.8
Stable tag: 1.2.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Download installed plugins and themes ZIP files directly from your admin dashboard without using FTP.

== Description ==

**Download Plugins and Themes from Dashboard** plugin lets you download installed plugins and themes ZIP files directly from your admin dashboard without using FTP.

There are no required settings in plugin - after installation 'Download ZIP' links will be automatically added to all:

* plugins to your *Plugins > Installed Plugins* menu, and
* themes to your *Appearance > Themes* menu.

Additionally in "Settings > Download Plugins and Themes" you can set if you want to append version number to ZIP filename, and/or if you want main plugin's or theme's directory to be included in ZIP.

= Feedback =
* We are open to your suggestions and feedback. Thank you for using or trying out one of our plugins!

== Installation ==

1. Upload the entire 'download-plugins-from-dashboard' folder to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. 'Download ZIP' links will be automatically added to all plugins to your 'Plugins > Installed Plugins' menu and to all themes to your 'Appearance > Themes' menu.

== Screenshots ==

1. Plugin 'Download ZIP' links in 'Plugins > Installed Plugins'.
2. Theme 'Download ZIP' links in 'Appearance > Themes'.
3. Settings.

== Changelog ==

= 1.2.0 - 11/06/2017 =
* Dev - "Add main directory to ZIP" options added.
* Dev - "Append version number to ZIP filename" options added.
* Dev - Code refactoring.

= 1.1.3 - 09/06/2017 =
* Dev - Zip files deletion from temporary folder after successful download added.

= 1.1.2 - 25/03/2017 =
* Fix - `load_plugin_textdomain` moved from `init` hook to constructor.
* Dev - System requirements error message updated.
* Dev - Language (POT) file updated.
* Tweak - Plugin header info ("Text Domain" etc.) updated.
* Tweak - Donate link updated.

= 1.1.1 - 10/11/2016 =
* Fix - For portability now only forward slashes (/) are used as directory separator in ZIP filenames.

= 1.1.0 - 05/10/2016 =
* Dev - Themes download functionality added (and plugin renamed).
* Dev - Plugins download action moved to from `plugins_loaded` to `admin_init` hook.
* Dev - More validation added to plugins download action.
* Dev - Using single `plugin_action_links` hook to add download action links, instead of using separate hook for each plugin.
* Dev - Checking for `ZipArchive` and `RecursiveIteratorIterator` classes to exist.
* Dev - Language (POT) file added.
* Dev - Screenshots added.
* Dev - Icons and banners added.

= 1.0.0 - 28/09/2016 =
* Initial Release.

== Upgrade Notice ==

= 1.0.0 =
This is the first release of the plugin.
