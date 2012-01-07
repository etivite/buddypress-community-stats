<?php
if ( !defined( 'ABSPATH' ) ) exit;

function etivite_bp_community_stats_footer() { 

	$data = (array) maybe_unserialize( get_option( 'bp_community_stats_display') );

	if ( in_array( 'members', $data ) )
		$counts[] = etivite_bp_community_stats_get_members();
		
	if ( in_array( 'active', $data ) )
		$counts[] = etivite_bp_community_stats_get_active();
		
	if ( in_array( 'status', $data ) )
		$counts[] = etivite_bp_community_stats_get_status();
			
	if ( in_array( 'groups', $data ) )
		$counts[] = etivite_bp_community_stats_get_groups();
			
	if ( in_array( 'forums', $data ) )
		$counts[] = etivite_bp_community_stats_get_forums();
			
	if ( in_array( 'blogs', $data ) )
		$counts[] = etivite_bp_community_stats_get_blogs();

	if ( in_array( 'posts', $data ) )
		$counts[] = etivite_bp_community_stats_get_posts();
			
	if ( in_array( 'comments', $data ) )
		$counts[] = etivite_bp_community_stats_get_comments();

	$i = 0;
	$l = count( $counts );
	echo '<div id="bp-community-stats-footer">';
	echo apply_filters( 'etivite_bp_community_stats_footer_intro', sprintf( __( '%s serving ', 'bp-community-stats' ), bloginfo('name') ) );
	
	foreach ( (array) $counts as $count) {
		$isLastItem = ( $i == ( $l - 1 ) );
		
		echo $count;
		if (!$isLastItem) 
			echo apply_filters( 'etivite_bp_community_stats_footer_spacer', ' &#124;' );
		
		++$i;
	}
	echo '</div>';

}


//simple templatetags


//
//member count (already cached)
//

function etivite_bp_community_stats_members() {
	echo etivite_bp_community_stats_get_members();
}
	function etivite_bp_community_stats_get_members() {
	
		$total_count = bp_core_get_total_member_count();
		
		if ( $total_count == 0 ) {
			$content = __( ' No Members', 'bp-community-stats' );
		} else if ( $total_count == 1 ) {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' member', 'bp-community-stats' );
		} else {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' members', 'bp-community-stats' );
		}
		
		return apply_filters( 'etivite_bp_community_stats_get_member_status', $content, $total_count );
	
	}


//
//activity_update status count
//

function etivite_bp_community_stats_status() {
	echo etivite_bp_community_stats_get_status();
}
	function etivite_bp_community_stats_get_status() {
	
		$total_count = etivite_bp_community_stats_status_count();
		
		if ( $total_count == 0 ) {
			$content = __( ' No updates', 'bp-community-stats' );
		} else if ( $total_count == 1 ) {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' update', 'bp-community-stats' );
		} else {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' updates', 'bp-community-stats' );
		}
		
		return apply_filters( 'etivite_bp_community_stats_get_status', $content, $total_count );
	
	}

function etivite_bp_community_stats_status_count() {
	global $bp, $wpdb;
	
	//if no cache is found
	if ( !$count = wp_cache_get( 'etivite_bp_community_stats_status_count', 'bp' ) ) {
		
		$count = $wpdb->get_var( $wpdb->prepare( "SELECT count(a.id) FROM {$bp->activity->table_name} a WHERE type = 'activity_update' AND a.component = '{$bp->activity->id}'" ) );
	
		if ( !$count )
			$count == 0;
		
		/* Cache the count */
		if ( !empty( $count ) )
			wp_cache_set( 'etivite_bp_community_stats_status_count', $count, 'bp' );
	}
	
	return $count;
}

//delete cache when removing status update
function etivite_bp_community_stats_status_count_delete_clear_cache( $args ) {
	if ( $args['type'] && $args['type'] == 'activity_update' )
		wp_cache_delete( 'etivite_bp_community_stats_status_count' );
}
add_action( 'bp_activity_delete', 'etivite_bp_community_stats_status_count_delete_clear_cache' );

//delete cache when adding status update
function etivite_bp_community_stats_status_count_posted_clear_cache() {
	wp_cache_delete( 'etivite_bp_community_stats_status_count' );
}
add_action( 'bp_activity_posted_update', 'etivite_bp_community_stats_status_count_posted_clear_cache' );


//
//member active
//

function etivite_bp_community_stats_active() {
	echo etivite_bp_community_stats_get_active();
}
	function etivite_bp_community_stats_get_active() {
	
		$total_count = etivite_bp_community_stats_active_count();
		
		if ( $total_count == 0 ) {
			$content = __( ' No recent active members', 'bp-community-stats' );
		} else if ( $total_count == 1 ) {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' active member', 'bp-community-stats' );
		} else {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' active members', 'bp-community-stats' );
		}
		
		return apply_filters( 'etivite_bp_community_stats_get_status', $content, $total_count );
	
	}

function etivite_bp_community_stats_active_count() {
	global $bp, $wpdb;
	
	//if no cache is found
	if ( !$count = wp_cache_get( 'etivite_bp_community_stats_active_count', 'bp' ) ) {
		
		$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(DISTINCT u.ID) FROM $wpdb->users u LEFT JOIN $wpdb->usermeta um ON um.user_id = u.ID WHERE " . bp_core_get_status_sql( 'u.' ) ." AND um.meta_key = 'last_activity' AND um.meta_value < NOW() AND um.meta_value > DATE_SUB(NOW(), INTERVAL %d DAY)", BP_COMMUNITY_STATS_ACTIVE_DAYS ) );
	
		if ( !$count )
			$count == 0;
		
		/* Cache the count */
		if ( !empty( $count ) )
			wp_cache_set( 'etivite_bp_community_stats_active_count', $count, 'bp' );
	}
	
	return $count;
}

//delete cache when adding status update
function etivite_bp_community_stats_active_count_clear_cache() {
	wp_cache_delete( 'etivite_bp_community_stats_active_count' );
}
add_action( 'wp_login', 'etivite_bp_community_stats_active_count_clear_cache' );
add_action( 'bp_core_activated_user', 'etivite_bp_community_stats_active_count_clear_cache' );

//
//forum counts (forums, topics, posts)
//

function etivite_bp_community_stats_forums() {
	echo etivite_bp_community_stats_get_forums();
}
	function etivite_bp_community_stats_get_forums( $spacer = ' &#124;' ) {
	
		if ( !bp_is_active( 'forums' ) )
			return;
		
		$total_count = etivite_bp_community_stats_get_forum_count();

		$total_count = $total_count[0];

		if ( $total_count->forums == 0 ) {
			$content .= __( ' No forums', 'bp-community-stats' );
		} else if ( $total_count->forums == 1 ) {
			$content .= ' <span class="community-count">'. $total_count->forums .'</span>' . __( ' forum', 'bp-community-stats' );
		} else {
			$content .= ' <span class="community-count">'. $total_count->forums .'</span>' . __( ' forums', 'bp-community-stats' );
		}
		
		if ($spacer) $content .= apply_filters( 'etivite_bp_community_stats_footer_spacer', $spacer );
		
		if ( $total_count->topics == 0 ) {
			$content .= __( ' No topics', 'bp-community-stats' );
		} else if ( $total_count->topics == 1 ) {
			$content .= ' <span class="community-count">'. $total_count->topics .'</span>' . __( ' topic', 'bp-community-stats' );
		} else {
			$content .= ' <span class="community-count">'. $total_count->topics .'</span>' . __( ' topics', 'bp-community-stats' );
		}
		
		if ($spacer) $content .= apply_filters( 'etivite_bp_community_stats_footer_spacer', $spacer );
		
		if ( $total_count->posts == 0 ) {
			$content .= __( ' No posts', 'bp-community-stats' );
		} else if ( $total_count->posts == 1 ) {
			$content .= ' <span class="community-count">'. $total_count->posts .'</span>' . __( ' post', 'bp-community-stats' );
		} else {
			$content .= ' <span class="community-count">'. $total_count->posts .'</span>' . __( ' posts', 'bp-community-stats' );
		}
		
		return apply_filters( 'etivite_bp_community_stats_get_forums', $content, $total_count );
	
	}

function etivite_bp_community_stats_get_forum_count() {
	global $bp, $wpdb, $bbdb;

	//if no cache is found
	if ( !$count = wp_cache_get( 'etivite_bp_community_stats_get_forum_count', 'bp' ) ) {
		
		do_action( 'bbpress_init' );
		
		$count = $wpdb->get_results( "SELECT COUNT(*) as forums, SUM(posts)  as posts, SUM(topics) as topics FROM {$bbdb->forums} WHERE forum_parent = ". BP_FORUMS_PARENT_FORUM_ID );
		
		/* Cache the count */
		if ( !empty( $count ) )
			wp_cache_set( 'etivite_bp_community_stats_get_forum_count', $count, 'bp' );
	}
	
	return $count;
	
}

//delete cache when adding/removing forums
function etivite_bp_community_stats_get_forum_count_clear_cache() {
	wp_cache_delete( 'etivite_bp_community_stats_get_forum_count' );
}
add_action( 'groups_delete_group', 'etivite_bp_community_stats_get_forum_count_clear_cache' );
add_action( 'groups_new_group_forum', 'etivite_bp_community_stats_get_forum_count_clear_cache' );
add_action( 'groups_delete_group_forum_post', 'etivite_bp_community_stats_get_forum_count_clear_cache' );
add_action( 'bp_forums_new_post', 'etivite_bp_community_stats_get_forum_count_clear_cache' );
add_action( 'groups_delete_group_forum_topic', 'etivite_bp_community_stats_get_forum_count_clear_cache' );
add_action( 'bp_forums_new_topic', 'etivite_bp_community_stats_get_forum_count_clear_cache' );



//
//blogs count (already cached)
//

function etivite_bp_community_stats_blogs() {
	echo etivite_bp_community_stats_get_blogs();
}
	function etivite_bp_community_stats_get_blogs() {
		
		$total_count = bp_blogs_total_blogs();
		
		if ( $total_count == 0 ) {
			$content = __( ' No blogs', 'bp-community-stats' );
		} else if ( $total_count == 1 ) {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' blog', 'bp-community-stats' );
		} else {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' blogs', 'bp-community-stats' );
		}
		
		return apply_filters( 'etivite_bp_community_stats_get_blogs', $content, $total_count );
	
	}


//
//comments count (needs wpms help)
//

function etivite_bp_community_stats_comments() {
	echo etivite_bp_community_stats_get_comments();
}
	function etivite_bp_community_stats_get_comments() {
		
		$total_count = etivite_bp_community_stats_get_comment_count();
		
		if ( $total_count == 0 ) {
			$content = __( ' No blog comments', 'bp-community-stats' );
		} else if ( $total_count == 1 ) {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' blog comment', 'bp-community-stats' );
		} else {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' blog comments', 'bp-community-stats' );
		}
		
		return apply_filters( 'etivite_bp_community_stats_get_comments', $content, $total_count );
	
	}

function etivite_bp_community_stats_get_comment_count() {
	global $bp, $wpdb;
	
	$count = wp_count_comments();
	
	$count = $count->approved;
	
	return $count;

}


//
//comments count (needs wpms help)
//

function etivite_bp_community_stats_posts() {
	echo etivite_bp_community_stats_get_posts();
}
	function etivite_bp_community_stats_get_posts() {
		
		$total_count = etivite_bp_community_stats_get_post_count();
		
		if ( $total_count == 0 ) {
			$content = __( ' No blog posts', 'bp-community-stats' );
		} else if ( $total_count == 1 ) {
			$content = ' <span class="community-post">'. $total_count .'</span>' . __( ' blog post', 'bp-community-stats' );
		} else {
			$content = ' <span class="community-post">'. $total_count .'</span>' . __( ' blog posts', 'bp-community-stats' );
		}
		
		return apply_filters( 'etivite_bp_community_stats_get_posts', $content, $total_count );
	
	}

function etivite_bp_community_stats_get_post_count() {
	global $bp;
	
	$count_posts = wp_count_posts();

	$published_posts = $count_posts->publish;
	
	return $published_posts;

}


//
//groups count (already cached)
//

function etivite_bp_community_stats_groups() {
	echo etivite_bp_community_stats_get_groups();
}
	function etivite_bp_community_stats_get_groups() {
		
		$total_count = groups_get_total_group_count();
		
		if ( $total_count == 0 ) {
			$content = __( ' No groups', 'bp-community-stats' );
		} else if ( $total_count == 1 ) {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' group', 'bp-community-stats' );
		} else {
			$content = ' <span class="community-count">'. $total_count .'</span>' . __( ' groups', 'bp-community-stats' );
		}
		
		return apply_filters( 'etivite_bp_community_stats_get_groups', $content, $total_count );
	
	}

?>
