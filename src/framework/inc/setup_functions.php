<?php

/*
                                                                           .d888 d8b 888          
                                                                          d88P"  Y8P 888          
                                                                          888        888          
888  888 .d8888b   .d88b.  888d888 .d8888b       88888b.  888d888 .d88b.  888888 888 888  .d88b.  
888  888 88K      d8P  Y8b 888P"   88K           888 "88b 888P"  d88""88b 888    888 888 d8P  Y8b 
888  888 "Y8888b. 88888888 888     "Y8888b.      888  888 888    888  888 888    888 888 88888888 
Y88b 888      X88 Y8b.     888          X88      888 d88P 888    Y88..88P 888    888 888 Y8b.     
 "Y88888  88888P'  "Y8888  888      88888P'      88888P"  888     "Y88P"  888    888 888  "Y8888  
                                                 888                                              
                                                 888                                              
                                                 888                                              
*/


remove_filter('pre_user_description', 'wp_filter_kses'); // Allow HTML in Author Bio

/** USAGE
 * // Default:
 * This will override the WordPress get_avatar hook
 *
 * // Custom placement:
 * <?php $imgURL = get_frmw_meta( $user_id, $size ); ?>
 * or
 * <img src="<?php echo get_frmw_meta( $user_id, $size ); ?>">
 *
 * @param WP_User|int $user_id Default: $post->post_author. Will accept any valid user ID passed into this parameter.
 * @param string      $size    Default: 'thumbnail'. Accepts all default WordPress sizes and any custom sizes made by
 *                             the add_image_size() function.
 *
 * @return {url}      Use this inside the src attribute of an image tag or where you need to call the image url.
 */

/**
 * Enqueue scripts and styles
 */
function frmw_enqueue_scripts_styles() {
	// Register.
	wp_register_style( 'frmw_admin_css', get_stylesheet_directory_uri().'/framework/assets/css/user_profile.css' , false, '1.0.0', 'all' );
	wp_register_script( 'frmw_admin_js', get_stylesheet_directory_uri().'/framework/assets/js/user_profile.js' , array( 'jquery' ), '1.0.0', true );

	// Enqueue.
	wp_enqueue_style( 'frmw_admin_css' );
	wp_enqueue_script( 'frmw_admin_js' );
}

add_action( 'admin_enqueue_scripts', 'frmw_enqueue_scripts_styles' );


/**
 * Show the new image field in the user profile page.
 *
 * @param object $user User object.
 */
function frmw_profile_img_fields( $user ) {
	if ( ! current_user_can( 'upload_files' ) ) {
		return;
	}

	// vars
	$url             = get_the_author_meta( 'frmw_meta', $user->ID );
	$upload_url      = get_the_author_meta( 'frmw_upload_meta', $user->ID );
	$upload_edit_url = get_the_author_meta( 'frmw_upload_edit_meta', $user->ID );
	$button_text     = $upload_url ? 'Change Current Image' : 'Upload New Image';

	if ( $upload_url ) {
		$upload_edit_url = get_site_url() . $upload_edit_url;
	}
	?>

	<div id="frmw_container">
		<h3><?php _e( 'Custom User Profile Photo', 'custom-user-profile-photo' ); ?></h3>

		<table class="form-table">
			<tr>
				<th><label for="frmw_meta"><?php _e( 'Profile Photo', 'custom-user-profile-photo' ); ?></label></th>
				<td>
					<!-- Outputs the image after save -->
					<div id="current_img">
						<?php if ( $upload_url ): ?>
							<img class="frmw-current-img" src="<?php echo esc_url( $upload_url ); ?>"/>

							<div class="edit_options uploaded">
								<a class="remove_img">
									<span><?php _e( 'Remove', 'custom-user-profile-photo' ); ?></span>
								</a>

								<a class="edit_img" href="<?php echo esc_url( $upload_edit_url ); ?>" target="_blank">
									<span><?php _e( 'Edit', 'custom-user-profile-photo' ); ?></span>
								</a>
							</div>
						<?php elseif ( $url ) : ?>
							<img class="frmw-current-img" src="<?php echo esc_url( $url ); ?>"/>
							<div class="edit_options single">
								<a class="remove_img">
									<span><?php _e( 'Remove', 'custom-user-profile-photo' ); ?></span>
								</a>
							</div>
						<?php else : ?>
							<img class="frmw-current-img placeholder"
							     src="<?php echo esc_url( get_stylesheet_directory_uri().'/framework/assets/img/placeholder.gif' ); ?>"/>
						<?php endif; ?>
					</div>

					<!-- Select an option: Upload to WPMU or External URL -->
					<div id="frmw_options">
						<input type="radio" id="upload_option" name="img_option" value="upload" class="tog" checked>
						<label
								for="upload_option"><?php _e( 'Upload New Image', 'custom-user-profile-photo' ); ?></label><br>

						<input type="radio" id="external_option" name="img_option" value="external" class="tog">
						<label
								for="external_option"><?php _e( 'Use External URL', 'custom-user-profile-photo' ); ?></label><br>
					</div>

					<!-- Hold the value here if this is a WPMU image -->
					<div id="frmw_upload">
						<input class="hidden" type="hidden" name="frmw_placeholder_meta" id="frmw_placeholder_meta"
						       value="<?php echo esc_url( plugins_url( 'custom-user-profile-photo/img/placeholder.gif' ) ); ?>"/>
						<input class="hidden" type="hidden" name="frmw_upload_meta" id="frmw_upload_meta"
						       value="<?php echo esc_url_raw( $upload_url ); ?>"/>
						<input class="hidden" type="hidden" name="frmw_upload_edit_meta" id="frmw_upload_edit_meta"
						       value="<?php echo esc_url_raw( $upload_edit_url ); ?>"/>
						<input id="uploadimage" type='button' class="frmw_wpmu_button button-primary"
						       value="<?php _e( esc_attr( $button_text ), 'custom-user-profile-photo' ); ?>"/>
						<br/>
					</div>

					<!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
					<div id="frmw_external">
						<input class="regular-text" type="text" name="frmw_meta" id="frmw_meta"
						       value="<?php echo esc_url_raw( $url ); ?>"/>
					</div>

					<!-- Outputs the save button -->
					<span class="description">
						<?php
						_e(
							'Upload a custom photo for your user profile or use a URL to a pre-existing photo.',
							'custom-user-profile-photo'
						);
						?>
					</span>
					<p class="description">
						<?php _e( 'Update Profile to save your changes.', 'custom-user-profile-photo' ); ?>
					</p>
				</td>
			</tr>
		</table><!-- end form-table -->
	</div> <!-- end #frmw_container -->

	<?php
	// Enqueue the WordPress Media Uploader.
	wp_enqueue_media();
}

add_action( 'show_user_profile', 'frmw_profile_img_fields' );
add_action( 'edit_user_profile', 'frmw_profile_img_fields' );


/**
 * Save the new user FRMW url.
 *
 * @param int $user_id ID of the user's profile being saved.
 */
function frmw_save_img_meta( $user_id ) {
	if ( ! current_user_can( 'upload_files', $user_id ) ) {
		return;
	}

	$values = array(
		// String value. Empty in this case.
		'frmw_meta'             => filter_input( INPUT_POST, 'frmw_meta', FILTER_SANITIZE_STRING ),

		// File path, e.g., http://3five.dev/wp-content/plugins/custom-user-profile-photo/img/placeholder.gif.
		'frmw_upload_meta'      => filter_input( INPUT_POST, 'frmw_upload_meta', FILTER_SANITIZE_URL ),

		// Edit path, e.g., /wp-admin/post.php?post=32&action=edit&image-editor.
		'frmw_upload_edit_meta' => filter_input( INPUT_POST, 'frmw_upload_edit_meta', FILTER_SANITIZE_URL ),
	);

	foreach ( $values as $key => $value ) {
		update_user_meta( $user_id, $key, $value );
	}
}

add_action( 'personal_options_update', 'frmw_save_img_meta' );
add_action( 'edit_user_profile_update', 'frmw_save_img_meta' );

/**
 * Retrieve the appropriate image size
 *
 * @param int    $user_id      Default: $post->post_author. Will accept any valid user ID passed into this parameter.
 * @param string $size         Default: 'thumbnail'. Accepts all default WordPress sizes and any custom sizes made by
 *                             the add_image_size() function.
 *
 * @return string      (Url) Use this inside the src attribute of an image tag or where you need to call the image url.
 */
function get_frmw_meta( $user_id, $size = 'thumbnail' ) {
	global $post;

	if ( ! $user_id || ! is_numeric( $user_id ) ) {
		/*
		 * Here we're assuming that the avatar being called is the author of the post.
		 * The theory is that when a number is not supplied, this function is being used to
		 * get the avatar of a post author using get_avatar() and an email address is supplied
		 * for the $id_or_email parameter. We need an integer to get the custom image so we force that here.
		 * Also, many themes use get_avatar on the single post pages and pass it the author email address so this
		 * acts as a fall back.
		 */
		$user_id = $post->post_author;
	}

	// Check first for a custom uploaded image.
	$attachment_upload_url = esc_url( get_the_author_meta( 'frmw_upload_meta', $user_id ) );

	if ( $attachment_upload_url ) {
		// Grabs the id from the URL using the WordPress function attachment_url_to_postid @since 4.0.0.
		$attachment_id = attachment_url_to_postid( $attachment_upload_url );

		// Retrieve the thumbnail size of our image. Should return an array with first index value containing the URL.
		$image_thumb = wp_get_attachment_image_src( $attachment_id, $size );

		return isset( $image_thumb[0] ) ? $image_thumb[0] : '';
	}

	// Finally, check for image from an external URL. If none exists, return an empty string.
	$attachment_ext_url = esc_url( get_the_author_meta( 'frmw_meta', $user_id ) );

	return $attachment_ext_url ? $attachment_ext_url : '';
}


/**
 * WordPress Avatar Filter
 *
 * Replaces the WordPress avatar with your custom photo using the get_avatar hook.
 *
 * @param string            $avatar     Image tag for the user's avatar.
 * @param int|object|string $identifier User object, UD or email address.
 * @param string            $size       Image size.
 * @param string            $alt        Alt text for the image tag.
 *
 * @return string
 */
function frmw_avatar( $avatar, $identifier, $size, $alt ) {
	if ( $user = frmw_get_user_by_id_or_email( $identifier ) ) {
		if ( $custom_avatar = get_frmw_meta( $user->ID, 'thumbnail' ) ) {
			return "<img alt='{$alt}' src='{$custom_avatar}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
		}
	}

	return $avatar;
}

add_filter( 'get_avatar', 'frmw_avatar', 1, 5 );

/**
 * Get a WordPress User by ID or email
 *
 * @param int|object|string $identifier User object, ID or email address.
 *
 * @return WP_User
 */
function frmw_get_user_by_id_or_email( $identifier ) {
	// If an integer is passed.
	if ( is_numeric( $identifier ) ) {
		return get_user_by( 'id', (int) $identifier );
	}

	// If the WP_User object is passed.
	if ( is_object( $identifier ) && property_exists( $identifier, 'ID' ) ) {
		return get_user_by( 'id', (int) $identifier->ID );
	}

	// If the WP_Comment object is passed.
	if ( is_object( $identifier ) && property_exists( $identifier, 'user_id' ) ) {
		return get_user_by( 'id', (int) $identifier->user_id );
	}

	return get_user_by( 'email', $identifier );
}


/**
 * EXTRA FIELDS
 */

add_action( 'show_user_profile', 'frmw_show_extra_profile_fields', 5 );
add_action( 'edit_user_profile', 'frmw_show_extra_profile_fields', 5 );

function frmw_show_extra_profile_fields( $user ) {
    $profession = get_the_author_meta( 'profession', $user->ID );
    $twitter_url = get_the_author_meta( 'twitter_url', $user->ID );
    $facebook_url = get_the_author_meta( 'facebook_url', $user->ID );
    $youtube_url = get_the_author_meta( 'youtube_url', $user->ID );
    $instagram_url = get_the_author_meta( 'instagram_url', $user->ID );
    $linkedin_url = get_the_author_meta( 'linkedin_url', $user->ID );
    $google_url = get_the_author_meta( 'google_url', $user->ID );
	?>
	<h3><?php esc_html_e( 'Personal Information', 'frmw' ); ?></h3>

	<table class="form-table">
		<tr>
			<td>
                <label for="profession"><?php esc_html_e( 'Profession', 'frmw' ); ?></label>
                <input type="text" id="profession" name="profession" value="<?php echo esc_attr( $profession ); ?>" />
            </td>
		</tr>

        <tr>
            <td>
                <h3><?php esc_html_e( 'Social Links', 'frmw' ); ?></h3>
            </td>
        </tr>

        <tr>
            <td>
                <label for="twitter_url"><?php esc_html_e( 'Twitter URL', 'frmw' ); ?></label>
                <input type="url" id="twitter_url" name="twitter_url" value="<?php echo esc_attr( $twitter_url ); ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="youtube_url"><?php esc_html_e( 'Facebook URL', 'frmw' ); ?></label>
                <input type="url" id="facebook_url" name="facebook_url" value="<?php echo esc_attr( $facebook_url ); ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="facebook_url"><?php esc_html_e( 'Youtube URL', 'frmw' ); ?></label>
                <input type="url" id="youtube_url" name="youtube_url" value="<?php echo esc_attr( $youtube_url ); ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="instagram_url"><?php esc_html_e( 'Instagram URL', 'frmw' ); ?></label>
                <input type="url" id="instagram_url" name="instagram_url" value="<?php echo esc_attr( $instagram_url ); ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="linkedin_url"><?php esc_html_e( 'LinkedIn URL', 'frmw' ); ?></label>
                <input type="url" id="linkedin_url" name="linkedin_url" value="<?php echo esc_attr( $linkedin_url ); ?>" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="google_url"><?php esc_html_e( 'Google Plus (G+) URL', 'frmw' ); ?></label>
                <input type="url" id="google_url" name="google_url" value="<?php echo esc_attr( $google_url ); ?>" />
            </td>
        </tr>
</table>
	<?php
}

add_action( 'personal_options_update', 'frmw_update_profile_fields' );
add_action( 'edit_user_profile_update', 'frmw_update_profile_fields' );

function frmw_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if ( ! empty( $_POST['profession'] ) ) {
		update_user_meta( $user_id, 'profession',  $_POST['profession']  );
    }

    if ( ! empty( $_POST['twitter_url'] ) ) {
		update_user_meta( $user_id, 'twitter_url', esc_url_raw( $_POST['twitter_url'])  );
    }
    
    if ( ! empty( $_POST['facebook_url'] ) ) {
		update_user_meta( $user_id, 'facebook_url', esc_url_raw($_POST['facebook_url'])  );
    }
    if ( ! empty( $_POST['youtube_url'] ) ) {
		update_user_meta( $user_id, 'youtube_url', esc_url_raw( $_POST['youtube_url'])  );
    }
    if ( ! empty( $_POST['instagram_url'] ) ) {
		update_user_meta( $user_id, 'instagram_url', esc_url_raw( $_POST['instagram_url'] ) );
    }
    if ( ! empty( $_POST['linkedin_url'] ) ) {
		update_user_meta( $user_id, 'linkedin_url', esc_url_raw( $_POST['linkedin_url'] ) );
    }
    if ( ! empty( $_POST['google_url'] ) ) {
		update_user_meta( $user_id, 'google_url', esc_url_raw( $_POST['google_url'] )  );
    }
}