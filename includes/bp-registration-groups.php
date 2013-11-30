<?php

/**
* Enqueue plugin scripts and styles
*/

add_action( 'wp_enqueue_scripts', 'bp_registration_groups_enqueue_scripts' );
function bp_registration_groups_enqueue_scripts() {
	wp_register_style( 'bp_registration_groups_styles', plugins_url('/styles.css', __FILE__) );
 	wp_enqueue_style( 'bp_registration_groups_styles' );
}

if (is_multisite()) { add_filter( 'bp_signup_usermeta', 'bp_registration_groups_save' ); }
else { add_action( 'bp_core_signup_user', 'bp_registration_groups_save_s' ); }

if (is_multisite()) { add_action( 'bp_core_activated_user', 'bp_registration_groups_join', 10, 3 ); }
else { add_action( 'bp_core_activated_user', 'bp_registration_groups_join_s' ); }

/** 
* bp_registration_groups
* Add list of public groups to registration page. Display a message
* stating no groups are available if no public groups are found.
*/
add_action('bp_after_signup_profile_fields', 'bp_registration_groups');
function bp_registration_groups(){
	/* list groups */ ?>
		<div class="register-section" id="registration-groups-section">
			<h4 class="reg_groups_title">Groups:</h3>
			<p class="reg_groups_description">Check one or more areas of interest:</p>
			<ul class="reg_groups_list">
				<?php $i = 0; ?>
				<?php if ( bp_has_groups('type=alphabetical&per_page=99999') ) : while ( bp_groups() ) : bp_the_group(); ?>
					<?php if ( bp_get_group_status() == ('public')) { ?>
					<li class="reg_groups_item">
						<input type="checkbox" id="field_reg_groups_<?php echo $i; ?>" name="field_reg_groups[]" value="<?php bp_group_id(); ?>" /><?php bp_group_name(); ?>
					</li>
					<?php } ?>
				<?php $i++; ?>
				<?php endwhile; /* endif; */ ?>
				<?php else: ?>
				<p class="reg_groups_none">No groups are available at this time.</p>
				<?php endif; ?>
			</ul>
		</div>
<?php }

/**
* bp_registration_groups_save()
* Save groups selected during registration in a multisite environment
*/
function bp_registration_groups_save( $usermeta ) {
	
	$usermeta['field_reg_groups'] = $_POST['field_reg_groups'];
	
	return $usermeta;
	
}

/**
* bp_registration_groups_save_s()
* Save groups selected during registration in a non-multisite environment
*/
function bp_registration_groups_save_s( $user_id ) {
	
	update_user_meta( $user_id, 'field_reg_groups', $_POST['field_reg_groups'] );
	
	return $user_id;
	
}

/**
* bp_registration_groups_join()
* Join groups when account is activated in a multisite environment
*/
function bp_registration_groups_join( $user_id, $key, $user ) {
	global $bp, $wpdb;
	
	$reg_groups = $user['meta']['field_reg_groups'];
	
	//only join groups if field_reg_groups contains any groups
	if ($reg_groups != '') {
		foreach ($reg_groups as $group_id) {
			$bp->groups->current_group = groups_get_group(array('group_id' => $group_id));
			groups_join_group($group_id, $user_id);
		}
	}
		
}

/**
* bp_registration_groups_join_s()
* Join groups when account is activate in a non-multisite user environment
*/
function bp_registration_groups_join_s( $user_id ) {
	global $bp, $wpdb;

	$reg_groups = get_user_meta( $user_id, 'field_reg_groups', true );
	
	//only join groups if field_reg_groups contains any groups
	if ($reg_groups != '') {
		foreach ($reg_groups as $group_id) {
			$bp->groups->current_group = groups_get_group(array('group_id' => $group_id));
			groups_join_group($group_id, $user_id);
		}
	}
	
	return $user_id;
}