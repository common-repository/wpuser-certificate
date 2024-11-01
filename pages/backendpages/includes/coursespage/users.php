<?php
	
	/* Start Accessed Directly */
		// Exit if accessed directly
		if(!defined('ABSPATH')){
			exit;
		}
	/* End Accessed Directly */

	$urlfile;
// Save attachment ID
if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['image_attachment_id'] ) ) :
    update_option( sanitize_text_field($_GET['coursename']) . 'media_selector_attachment_id', absint( sanitize_text_field($_POST['image_attachment_id']) ) );
endif;

wp_enqueue_media();

?><form method='post' class="form-group" >
    <div class='image-preview-wrapper'>			
        <p id="excelfileurl" ><?php echo esc_html(wp_get_attachment_url( get_option( sanitize_text_field($_GET['coursename']) . 'media_selector_attachment_id' ) )); ?></p>
    </div>
    <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload Excel File' ); ?>" />
    <input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo esc_html(get_option( sanitize_text_field($_GET['coursename']) . 'media_selector_attachment_id' )); ?>'>
    <input type="submit" name="submit_image_selector" value="Save" class="button-primary">
</form>

<?php

add_action( 'admin_footer', 'wpuc_mediaSelectorPrintScriptsCertification' );

function wpuc_mediaSelectorPrintScriptsCertification() {

	$my_saved_attachment_post_id = get_option( sanitize_text_field($_GET['coursename']) . 'media_selector_attachment_id', 0 );

	?><script type='text/javascript'>

		jQuery( document ).ready( function( $ ) {

			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo esc_html($my_saved_attachment_post_id); ?>; // Set this

			jQuery('#upload_image_button').on('click', function( event ){

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
					title: 'Select Excel File to upload',
					button: {
						text: 'Use this File',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});

				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
                    console.log(attachment)
					// Do something with attachment.id and/or attachment.url here
					//$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                    $('#excelfileurl').html(attachment.url);
					$( '#image_attachment_id' ).val(attachment.id);

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

<table class="table" width="100%" border="1">
	<thead>
		<th>Name On Certificate</th>
		<th>Email</th>
	</thead>
	<tbody id="datafromexceltable">

	</tbody>
</table>


<?php if (wp_get_attachment_url( get_option( sanitize_text_field($_GET['coursename']) . 'certification_img_id' ) ) != "") { ?>
<div class="form-group">
	<button class="sendusersdata btn btn-danger" id="sendusersdata">Add Users To The DataBase To Let Them Login To Download Their Certificate</button>
</div>
<?php } ?>
<h4 id="usersresult"></h4>

<script>
var studentsArray;

(async function wpuc_readDataFromExcel () {

	var fileurl = document.getElementById("excelfileurl");

	var makeblobfromurl = await 
	fetch(fileurl.innerHTML)
	.then(r => r.blob())
	.catch((error) => {
		console.log(error)
	});

	console.log(makeblobfromurl);

	

	readXlsxFile(makeblobfromurl).then(function(rows) {
		var datafromexceltable = document.getElementById("datafromexceltable");

		for (let i = 1; i < rows.length; i = i + 1) {
			var html = `
				<tr>
					<td>${rows[i][0]}</td>
					<td>${rows[i][1]}</td>
				</tr>
			`;

			datafromexceltable.innerHTML = datafromexceltable.innerHTML + html;
		}
		
		studentsArray = rows;

		
	  	
	}).catch((error) => {
		console.log("There is an error")
	});
	
	
})()

</script>


<?php 