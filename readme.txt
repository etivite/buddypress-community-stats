=== Plugin Name ===
Contributors: nuprn1, etivite
Donate link: http://etivite.com/donate/
Tags: buddypress, member, stats, member stats, counts, community stats
Requires at least: PHP 5.2, WordPress 3.2.1, BuddyPress 1.5.1
Tested up to: PHP 5.2.x, WordPress 3.2.1, BuddyPress 1.5.1
Stable tag: 0.5.1

This plugin will display your buddypress community total counts for members, status updates, group forums topics, posts(bbPress 1.0), forums, groups, blogs

== Description ==

** IMPORTANT **
This plugin has been updated for BuddyPress 1.5.1


This plugin will display your buddypress community total counts for members, active members, status updates, group forums topics, posts(bbPress 1.0), forums, groups, blogs

Display in Footer or Widget - or various filters and templatetags can be used. A simple wp-admin dashboard widget is included (admin role only)

= Related Links: = 

* <a href="http://etivite.com" title="Plugin Demo Site">Author's Site</a>
* <a href="http://etivite.com/wordpress-plugins/buddypress-community-stats/">BuddyPress Community Stats - About Page</a>
* <a href="http://etivite.com/api-hooks/">BuddyPress and bbPress Developer Hook and Filter API Reference</a>

== Installation ==

1. Upload the full directory into your wp-content/plugins directory
2. Activate the plugin at the plugin administration page
3. Enable counts and widget via wp-admin page

== Frequently Asked Questions ==

= How do change the active members days? =

Set this constant in your wp-config `BP_COMMUNITY_STATS_ACTIVE_DAYS`

= The footer and widget is cool and all but i want to use this in my own theme =

template tags are available for each count - have a look within the file bp-community-stats.php. such as `bp_community_stats_members()`, `bp_community_stats_active()`, `bp_community_stats_status()` and so on.

= How do I include a certain count? =

The wp-admin page includes an option to select which count to display

= Deleting a Group with a forum does not change the forum count =

This is a bug with BuddyPress - a trac ticket has been filed

= My question isn't answered here =

Please contact me on http://etivite.com


== Changelog ==

= 0.5.1 =

* BUG: fix network admin settings page on multisite
* FEATURE: support for locale mo files

= 0.5.0 =

* WordPress 3.2.1/BP 1.5.1 only

= 0.3.1 =

* Bug: wrong action to delete cache data

= 0.3.0 =

* Feature: Added active members (default 30 days of last_activity)
* all counts are now wp_cached
* Wordpress 3.0

= 0.2.0 =

* Feature: Added simple wp-admin dashboard widget
* Bug: Fixed issue with widget not displaying

= 0.1.0 =

* First [BETA] version

== Upgrade Notice ==

= 0.5.0 =
* BuddyPress 1.5.1 and higher - required.

== Extra Configuration ==

The constant `BP_COMMUNITY_STATS_ACTIVE_DAYS` can be defined in your wp-config to override the default 30 days of last_activity for active members.

Several templatetags are available if you wish to roll your own layout instead of the default widget/footer options
