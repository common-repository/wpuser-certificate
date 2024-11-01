<?php

/* Start Accessed Directly */
	// Exit if accessed directly
	if(!defined('ABSPATH')){
		exit;
	}
/* End Accessed Directly */

// Save attachment ID
if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['certificate_attachment_id'] ) ) :
    update_option( sanitize_text_field($_GET['coursename']) . 'certification_img_id', absint( sanitize_text_field($_POST['certificate_attachment_id']) ) );
endif;

wp_enqueue_media();

?><form method='post' class="uploadcertificate">
	<h6 class="title" >Upload Your Certificate Here</h6>
    <div class='image-preview-wrapper'>
        <img id='certificate-preview' src='<?php echo esc_html(wp_get_attachment_url( get_option( sanitize_text_field($_GET['coursename']) . 'certification_img_id' ) )); ?>' height='100'>
    </div>
    <input id="upload_certificate_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
    <input type='hidden' name='certificate_attachment_id' id='certificate_attachment_id' value='<?php echo esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_img_id' )); ?>'>
    <input type="submit" name="submit_image_selector" value="Save" class="button-primary">
</form>

<?php

add_action( 'admin_footer', 'wpuc_mediaSelectorPrintScriptsCertificationDetails' );

function wpuc_mediaSelectorPrintScriptsCertificationDetails() {

	$my_saved_attachment_post_id = get_option( sanitize_text_field($_GET['coursename']) . 'certification_img_id', 0 );

	?><script type='text/javascript'>

		jQuery( document ).ready( function( $ ) {

			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo esc_html($my_saved_attachment_post_id); ?>; // Set this

			jQuery('#upload_certificate_button').on('click', function( event ){

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});

				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
                    console.log(attachment)
					// Do something with attachment.id and/or attachment.url here
					$( '#certificate-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#certificate_attachment_id' ).val(attachment.id);

					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});

					// Finally, open the modal
					file_frame.open();
			});

			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});

	</script><?php

}

?>


<?php
	if (isset( $_POST['certificatedetail'] )) {

		if (isset($_POST['centertopbottom']) == false) {
			$_POST['centertopbottom'] = NULL;
		}

		if (isset($_POST['centerleftright']) == false) {
			$_POST['centerleftright'] = NULL;
		}

		if (isset($_POST['textcolor']) == false) {
			$_POST['textcolor'] = NULL;
		}

		if (isset($_POST['textsize']) == false) {
			$_POST['textsize'] = NULL;
		}


		$centertopbottom = sanitize_text_field($_POST['centertopbottom']);
		$centerleftright = sanitize_text_field($_POST['centerleftright']);
		$top = sanitize_text_field($_POST['top']);
		$left = sanitize_text_field($_POST['left']);
		$textcolor = sanitize_text_field($_POST['textcolor']);
		$textsize = sanitize_text_field($_POST['textsize']);
		$certificate_src = sanitize_text_field($_POST['certificate_src']);
		$courseid = sanitize_text_field($_POST['courseid']);

		update_option( sanitize_text_field($_GET['coursename']) . 'certification_y', $top );
		update_option( sanitize_text_field($_GET['coursename']) . 'certification_x', $left );
		update_option( sanitize_text_field($_GET['coursename']) . 'certification_centertopbottom', $centertopbottom );
		update_option( sanitize_text_field($_GET['coursename']) . 'certification_centerleftright', $centerleftright );
		update_option( sanitize_text_field($_GET['coursename']) . 'certification_textcolor', $textcolor );
		update_option( sanitize_text_field($_GET['coursename']) . 'certification_textsize', $textsize );

		include plugin_dir_path(__FILE__) . '/../../../../enviroment_variables.php';

		$body = array(
			'centertopbottom' => $centertopbottom,
			'centerleftright' => $centerleftright,
			'textcolor' => $textcolor,
			'textsize' => $textsize,
			'certificate_src' => $certificate_src,
			'courseid' => $courseid
		);

		$postTop = '';
		$postLeft = '';

		if ($top != '') {
			//$postTop = 'top=' . $_POST['top'] . '&';
			$body['top'] = $top;
		}

		if ($left != '') {
			//$postLeft = 'left=' . $_POST['left'] . '&';
			$body['left'] = $left;
		}


		$origin = $_SERVER['HTTP_HOST'];
        $serialkey = get_option( 'dashboardplugincertificate_serialkey' );
        
        $args = array(
            'body' => $body,
            'headers'     => array(
                'credentials' => 'true',
                'authorization' => $serialkey,
                'origin' => $origin
            )
        );
        
        $getResponse = wp_remote_post($saveUserImge, $args );
        $apiResponse = wp_remote_retrieve_body($getResponse);

		echo '<h3>' . esc_html($apiResponse) . '</h3>';
	}

?>

<?php
    if (wp_get_attachment_url( get_option( sanitize_text_field($_GET['coursename']) . 'certification_img_id' ) ) != "") {
        ?>

        <div class="certification-viewer">
            <div class="header">
                <h6 class="title from-group">Edit Text On Certificate</h6>
                <div class="form-group">
					<form method="post" id="certificationdetailsform">
						<div class="form-group">
							<span>Top</span>
							<input type="number" name="top" placeholder="px" class="text-viewer-position" data-position="top" id="text-viewer-top" value="<?php echo esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_y' ));?>" >
						</div>
						<div class="form-group">
							<span>Left</span>
							<input type="number" name="left" placeholder="px" class="text-viewer-position" data-position="left" id="text-viewer-left" value="<?php echo esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_x' ));?>" >
						</div>
						
						<div class="form-group">
							<input type="checkbox" name="centertopbottom" id="centertopbottom" class="center-text-viewer" data-class="text-viewer-center-topbottom" data-class-same="text-viewer-center-inall" value="true" <?php if (get_option( sanitize_text_field($_GET['coursename']) . 'certification_centertopbottom' ) == 'true') {echo 'checked';} ?> >
							<span>Center Top Bottom</span>
						</div>
						<div class="form-group">
							<input type="checkbox" name="centerleftright" id="centerleftright" class="center-text-viewer" data-class="text-viewer-center-leftright" data-class-same="text-viewer-center-inall" value="true" <?php if (get_option( sanitize_text_field($_GET['coursename']) . 'certification_centerleftright' ) == 'true') {echo 'checked';} ?> >
							<span>Center Left Right</span>
						</div>
						
						<div class="form-group">
							<span>Text Color</span>
							<input type="color" name="textcolor" id="textcolorinput" value="<?php echo esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_textcolor' ));?>">
						</div>
						<div class="form-group">
							<span>Text Size</span>
							<input type="number" name="textsize" id="textsizeinput" value=<?php echo esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_textsize' ));?> >
						</div>

						<input type="hidden" name="certificatedetail" value="certificate" >
						<input type="hidden" name="courseid" value=<?php echo esc_html($_GET['courseid']); ?> >

						<input type="hidden" name="certificate_src" id="certificate_imageblob" value="<?php echo esc_html(wp_get_attachment_url( get_option( sanitize_text_field($_GET['coursename']) . 'certification_img_id' ) )); ?>" >

						<input type="submit" value="Submit" class="btn btn-primary" name="certificationsubmit" >
					</form>
                </div>
            </div>
            <div class="certification-viewer-body">
                <img class="certification-viewer-image" id="certification-viewer-image" src='<?php echo esc_html(wp_get_attachment_url( get_option( sanitize_text_field($_GET['coursename']) . 'certification_img_id' ) )); ?>'>
                
				<?php 
					if (get_option( sanitize_text_field($_GET['coursename']) . 'certification_centertopbottom' ) == 'true' && get_option( sanitize_text_field($_GET['coursename']) . 'certification_centerleftright' ) == 'true') {
						echo '<span class="text-viewer text-viewer-center-inall" id="text-viewer" style="color: ' . esc_html(get_option( sanitize_text_field($_GET['coursename'])) . 'certification_textcolor' ) . '; font-size: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_textsize' )) . 'px;" >Student Name Here</span>';
					} else if (get_option( sanitize_text_field($_GET['coursename']) . 'certification_centertopbottom' ) == '' && get_option( sanitize_text_field($_GET['coursename']) . 'certification_centerleftright' ) == 'true') {
						echo '<span class="text-viewer text-viewer-center-leftright" id="text-viewer" style="top: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_y' )) . 'px; color: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_textcolor' )) . '; font-size: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_textsize' )) . 'px;" >Student Name Here</span>';
					} else if (get_option( sanitize_text_field($_GET['coursename']) . 'certification_centertopbottom' ) == 'true' && get_option( sanitize_text_field($_GET['coursename']) . 'certification_centerleftright' ) == '') {
						echo '<span class="text-viewer text-viewer-center-topbottom" id="text-viewer" style="left: ' .esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_x' )) . 'px; color: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_textcolor' )) . '; font-size: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_textsize' )) . 'px;" >Student Name Here</span>';
					} else {
						echo '<span class="text-viewer" style="top: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_y' )) . 'px; left: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_x' )) . 'px; color: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_textcolor' )) . '; font-size: ' . esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'certification_textsize' )) . 'px;" id="text-viewer">Student Name Here</span>';
					}
				?>
				
				<!-- <span class="text-viewer" id="text-viewer">Student Name Here</span> -->
            </div>
        </div>

        <?php
    }
?>



