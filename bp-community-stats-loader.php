<?php
/*
Plugin Name: BuddyPress Community Stats
Plugin URI: http://wordpress.org/extend/plugins/buddypress-community-stats/
Description: Display a basic community stats (and widget)
Author: rich @etiviti
Author URI: http://etivite.com
License: GNU GENERAL PUBLIC LICENSE 3.0 http://www.gnu.org/licenses/gpl.txt
Version: 0.5.1
Text Domain: bp-community-stats
Network: true
*/

//TODO
//need blog posts and ms blog posts count (use activity table - but then missing pre-install data) (use wpcron to loop blogs and count?!?)

//override default 30 days of last active check
if ( !defined( 'BP_COMMUNITY_STATS_ACTIVE_DAYS' ) )
	define ( 'BP_COMMUNITY_STATS_ACTIVE_DAYS', 30 );

/* Only load code that needs BuddyPress to run once BP is loaded and initialized. */
function etivite_bp_community_stats_init() {

	if ( file_exists( dirname( __FILE__ ) . '/languages/' . get_locale() . '.mo' ) )
		load_textdomain( 'bp-community-stats', dirname( __FILE__ ) . '/languages/' . get_locale() . '.mo' );

    require( dirname( __FILE__ ) . '/bp-community-stats.php' );

	if ( get_option( 'bp_community_stats_display_footer' ) )
		add_action( 'bp_footer', 'etivite_bp_community_stats_footer' );
		
	add_action( bp_core_admin_hook(), 'etivite_bp_community_stats_admin_add_admin_menu' );
	
}
add_action( 'bp_include', 'etivite_bp_community_stats_init', 88 );
//add_action( 'bp_init', 'etivite_bp_community_stats_init', 88 );

//add admin_menu page
function etivite_bp_community_stats_admin_add_admin_menu() {
	global $bp;
	
	if ( !is_super_admin() )
		return false;

	//Add the component's administration tab under the "BuddyPress" menu for site administrators
	require ( dirname( __FILE__ ) . '/admin/bp-community-stats-admin.php' );
	require ( dirname( __FILE__ ) . '/admin/bp-community-stats-widget-dashboard.php' );

	add_submenu_page( 'bp-general-settings', __( 'Community Stats', 'bp-community-stats' ), __( 'Community Stats', 'bp-community-stats' ), 'manage_options', 'bp-community-stats-settings', 'etivite_bp_community_stats_admin' );	

	//set up defaults
	
	//dashboard widget
	add_action('wp_dashboard_setup', 'etivite_bp_community_stats_dashboard_widget');
}

/* Stolen from Welcome Pack - thanks, Paul! then stolen from boone*/
function etivite_bp_community_stats_admin_add_action_link( $links, $file ) {
	if ( 'buddypress-community-stats/bp-community-stats-loader.php' != $file )
		return $links;

	if ( function_exists( 'bp_core_do_network_admin' ) ) {
		$settings_url = add_query_arg( 'page', 'bp-community-stats-settings', bp_core_do_network_admin() ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' ) );
	} else {
		$settings_url = add_query_arg( 'page', 'bp-community-stats-settings', is_multisite() ? network_admin_url( 'admin.php' ) : admin_url( 'admin.php' ) );
	}

	$settings_link = '<a href="' . $settings_url . '">' . __( 'Settings', 'bp-community-stats' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}
add_filter( 'plugin_action_links', 'etivite_bp_community_stats_admin_add_action_link', 10, 2 );

//widget reference for all
require( dirname( __FILE__ ) . '/bp-community-stats-widget.php' );
?>
