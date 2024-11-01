<?php
    /* Start Accessed Directly */
		// Exit if accessed directly
		if(!defined('ABSPATH')){
			exit;
		}
	/* End Accessed Directly */
    
    if (isset($_POST['whichpage'])) {
        update_option( 'certificationclientpage', sanitize_text_field($_POST['page_id']));
    }

?>

<div class="dashboardcertificate_container">
    
   
    <div class="shortcodes wpsetupsection">
        <h5 class="maintitle form-group">Shortcodes</h5>
        <div class="wpsetupsection_left" >
            <div class="form-group">
                <p class="text">
                    <span class="shortcode">[wpcertificate_dashboarduser_page]</span> copy and paste this short code in the page that you want it to be the user dashboard page
                </p>
            </div>
            <div class="form-group">
                <p class="text">
                    <span class="shortcode">[wpusercertificate_login_form]</span> copy and paste this short code in the page that you want it to be the user login page
                </p>
            </div>
        </div>
    </div>

    <div class="wpsetupsection" >
        <h5 class="maintitle form-group">Choose Which page you want it to be the dashboard of the user</h5>
        <form method="post" action="" >
            <div class="wpsetupsection_left" >
                <div class="form-group">
                    <div class="wp_dropdown_pages" id="wp_dropdown_pages" >
                        <?php wp_dropdown_pages(); ?>
                    </div>
                    <input type="hidden" id="wp_dropdown_pages_id" value="<?php echo esc_html(get_option( 'certificationclientpage' ));?>" >

                    <script>
                        var wp_dropdown_pages = document.getElementById("wp_dropdown_pages").children[0],
                        wp_dropdown_pages_id = document.getElementById("wp_dropdown_pages_id");

                        for (let i = 0; i < wp_dropdown_pages.children.length; i = i + 1) {
                            if (wp_dropdown_pages.children[i].value == wp_dropdown_pages_id.value) {
                                wp_dropdown_pages.children[i].selected = true;
                                break;
                            }
                        }
                    </script>
                    <input type="submit" name="whichpage" class="btn btn-primary wpsubmitnicebutton"  >
                </div>
            </div>
        </form>
    </div>
</div>