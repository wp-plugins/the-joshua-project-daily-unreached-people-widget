=== The Joshua Project, Daily Unreached People Widget ===
Contributors: topher1kenobe
Tags: widget
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates a widget highlighting a people group unreached with the Gospel of Christ.

== Description ==

Creates a widget highlighting a people group unreached with the Gospel of Christ.  Data is provided by <a href="http://joshuaproject.net/">The Joshua Project</a>.

== Installation ==

1. Upload the `/joshua-project-daily-unreached/` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Visit Appearance -> Widgets in the admin and place the widget in a sidebar

== Screenshots ==

1. Showing an unreashed people group


== Usage ==

Some basic CSS is included.  If you'd like to turn it off, drop this code into your theme functions.php file or a plugin of your choosing.

`function remove-t1k-jp-unreached-people-styles() {
    return false;
}
add_filter( 't1k-jp-unreached-people-styles', 'remove-t1k-jp-unreached-people-styles' );`

== Changelog ==

= 1.0 =
* Initial release
