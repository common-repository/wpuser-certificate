<?php

    /* Start Accessed Directly */
		// Exit if accessed directly
		if(!defined('ABSPATH')){
			exit;
		}

        
    /* End Accessed Directly */
    include plugin_dir_path(__FILE__) . '/../../enviroment_variables.php';

    $apiResponse = ""; $bodyApiResponse = "";
    
    if (is_user_logged_in()) {
        if(isset( $_POST['submit'] ) )
        {
            $key = sanitize_text_field($_POST['key']);
            if (empty($key)) {
                $bodyApiResponse = "In valid email or password";
            } else {
                $origin = $_SERVER['HTTP_HOST'];

                $args = array(
                    'body' => array(
                        'key' => $key
                    ),
                    'headers'     => array(
                        'credentials' => 'true',
                        'origin' => $origin
                    )
                );
                
                $apiResponse = wp_remote_post($license_url, $args );
                $bodyApiResponse = sanitize_text_field(wp_remote_retrieve_body($apiResponse));
    
                if ($bodyApiResponse == "The plugin is now activated") {
                    update_option( 'dashboardplugincertificate_serialkey', $key);
                    update_option( 'dashboard_checklicense', sanitize_text_field($apiResponse) );
                }
            }
        }
    }
?>

<h5>
    <?php
        if (get_option('dashboard_checklicense') == "The plugin is now activated") {
            echo 'You Are Verified';
        } else {
            echo 'Enter your serial key to activate your plugin';
        }
    ?>
</h5>
<form class="checkmylicenseform" id="checkmylicense" method="POST"  >
    <div class="form-group">
        <input class="form-control" placeholder="Key" name="key" value="<?php echo esc_html(get_option( 'dashboardplugincertificate_serialkey' ));?>" >
    </div>
    <input type="hidden" value="checkmycredentials" >
    <div class="form-group">
        <input type="submit" name="submit" class="btn btn-primary" >
    </div>
    <?php
        echo '<h5>' . esc_html($bodyApiResponse) . '</h5>';
    ?>
</form>

