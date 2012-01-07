<?php
function etivite_bp_community_stats_dashboard_widget(){
	wp_add_dashboard_widget('etivite_bp_community_stats_dashboard_widget', __( 'Community Stats', 'bp-community-stats' ), 'etivite_bp_community_stats_get_dashboard_widget');
}

function etivite_bp_community_stats_get_dashboard_widget() {
		
    $data = (array) maybe_unserialize( get_option( 'bp_community_stats_display') );
	
	if ( in_array( 'members', $data ) )
		$wcounts[] = etivite_bp_community_stats_get_members();

	if ( in_array( 'active', $data ) )
		$wcounts[] = etivite_bp_community_stats_get_active();
		
	if ( in_array( 'status', $data ) )
		$wcounts[] = etivite_bp_community_stats_get_status();
			
	if ( in_array( 'groups', $data ) )
		$wcounts[] = etivite_bp_community_stats_get_groups();
			
	if ( in_array( 'forums', $data ) )
		$wcounts[] = etivite_bp_community_stats_get_forums('</li><li>');
			
	if ( in_array( 'blogs', $data ) )
		$wcounts[] = etivite_bp_community_stats_get_blogs();

	if ( in_array( 'posts', $data ) )
		$wcounts[] = etivite_bp_community_stats_get_posts();
			
	if ( in_array( 'comments', $data ) )
		$wcounts[] = etivite_bp_community_stats_get_comments();

	echo '<ul>';
	
	$i = 0;
	$l = count( $wcounts );
	
	foreach ( (array) $wcounts as $count) {
		$isLastItem = ( $i == ( $l - 1 ) );
		
		echo '<li>' . $count .'</li>';
		
		++$i;
	}
	
	echo '</ul>';

}
?>
