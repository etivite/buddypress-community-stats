<?php 

function etivite_bp_community_stats_admin_counts( ) {
	return array( 'members','active','status','forums','blogs','groups','comments','posts' );
}

function etivite_bp_community_stats_admin_count_check( $type, $currenttypes ) {
	if ( is_multisite() && ( $type == 'comments' || $type == 'posts' ) ) {
		echo 'disabled';
		return;
	}

	if ( !is_multisite() && $type == 'blogs' ) {
		echo 'disabled';
		return;
	}
	
	if ( in_array( $type, $currenttypes) )
		echo 'checked';		
	
	return;
}

function etivite_bp_community_stats_admin() {
	global $bp;

	/* If the form has been submitted and the admin referrer checks out, save the settings */
	if ( isset( $_POST['submit'] ) && check_admin_referer('etivite_bp_community_stats_admin') ) {
	
		if( isset($_POST['ab_community_counts'] ) && !empty($_POST['ab_community_counts']) ) {
			update_option( 'bp_community_stats_display', $_POST['ab_community_counts'] );
		} else {
			update_option( 'bp_community_stats_display', false );
		}
		
		if( isset($_POST['ab_community_footer'] ) && !empty($_POST['ab_community_footer']) && (int)$_POST['ab_community_footer'] == 1 ) {
			update_option( 'bp_community_stats_display_footer', true );
		} else {
			update_option( 'bp_community_stats_display_footer', false );
		}
		
		$updated = true;
	}
?>	
	<div class="wrap">
		<h2><?php _e( 'Community Stats', 'bp-community-stats' ); ?></h2>

		<?php if ( isset($updated) ) : echo "<div id='message' class='updated fade'><p>" . __( 'Settings Updated.', 'bp-community-stats' ) . "</p></div>"; endif; ?>

		<form action="<?php echo network_admin_url('/admin.php?page=bp-community-stats-settings'); ?>" name="bp-community-stats-settings-form" id="bp-community-stats-settings-form" method="post">

			<h4><?php _e( 'Display total counts for:', 'bp-community-stats' ); ?></h4>

			<table class="form-table">
				<?php

				$enabledcounts = (array) get_option( 'bp_community_stats_display');
				$totalcounts = etivite_bp_community_stats_admin_counts();

				foreach ($totalcounts as $count) { ?>
					<tr>
						<th><label for="type-<?php echo $count ?>"><?php echo $count ?></label></th>
						<td><input id="type-<?php echo $count ?>" type="checkbox" <?php etivite_bp_community_stats_admin_count_check( $count, $enabledcounts ); ?> name="ab_community_counts[]" value="<?php echo $count ?>" /></td>
					</tr>
				<?php } ?>
			</table>
				
			<h4><?php _e( 'Display:', 'bp-community-stats' ); ?></h4>

			<table class="form-table">
				<tr>
					<th><label for="ab_community_footer"><?php _e('Display in Footer?','bp-community-stats') ?></label></th>
					<td><input type="checkbox" name="ab_community_footer" id="ab_community_footer" value="1"<?php if ( get_option( 'bp_community_stats_display_footer' ) ) { ?> checked="checked"<?php } ?> /></td>
				</tr>
				
			</table>
			
			<?php wp_nonce_field( 'etivite_bp_community_stats_admin' ); ?>
			
			<p class="submit"><input type="submit" name="submit" value="Save Settings"/></p>
			
			<p>A theme and admin dashboard widget are available.</p>
			
			<?php if ( is_multisite() ) echo '<p class="description">Please note: Total Posts and Comments not support in multisite/network mode</p>'; ?>
		</form>
		
		<h3>About:</h3>
		<div id="plugin-about" style="margin-left:15px;">
			
			<p>
			<a href="http://etivite.com/wordpress-plugins/buddypress-community-stats/">BuddyPress Community Stats - About Page</a><br/> 
			</p>
		
			<div class="plugin-author">
				<strong>Author:</strong> <a href="http://profiles.wordpress.org/users/etivite/"><img style="height: 24px; width: 24px;" class="photo avatar avatar-24" src="http://www.gravatar.com/avatar/9411db5fee0d772ddb8c5d16a92e44e0?s=24&amp;d=monsterid&amp;r=g" alt=""> rich @etivite</a><br/>
				<a href="http://twitter.com/etivite">@etivite</a>
			</div>
		
			<p>
			<a href="http://etivite.com">Author's site</a><br/>
			<a href="http://etivite.com/api-hooks/">Developer Hook and Filter API Reference</a><br/>
			<a href="http://etivite.com/wordpress-plugins/">WordPress Plugins</a><br/>
			</p>
		</div>
		
	</div>
<?php
}

?>
