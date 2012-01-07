<?php

/* Register widgets for the core component */
function etivite_bp_community_stats_widget_init() {
	add_action('widgets_init', create_function('', 'return register_widget("etivite_bp_community_stats_Widget");') );
}
add_action( 'bp_register_widgets', 'etivite_bp_community_stats_widget_init', 15 );

class etivite_bp_community_stats_Widget extends WP_Widget {
	
	function etivite_bp_community_stats_widget() {
		parent::WP_Widget( false, $name = __( 'Community Stats', 'bp-community-stats' ) );
	}

	function widget($args, $instance) {
		
		//don't care to load on these pages
		if ( bp_is_register_page() || bp_is_activation_page() )
			return;
		
	    extract( $args );
	    
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
		
		echo $before_widget;
		echo $before_title
		   . $widget_name 
		   . $after_title;


echo '<ul>';
		$i = 0;
		$l = count( $wcounts );
		
		foreach ( (array) $wcounts as $count) {
			$isLastItem = ( $i == ( $l - 1 ) );
			
			echo '<li>' . $count .'</li>';
			
			++$i;
		}
echo '</ul>';
			
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {

	}

	function form( $instance ) {
		
	}
}

?>
