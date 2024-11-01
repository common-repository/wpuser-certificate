<?php

    
    /* Start Accessed Directly */
    // Exit if accessed directly
    if(!defined('ABSPATH')){
        exit;
    }
    /* End Accessed Directly */



add_shortcode( 'wpusercertificate_login_form', 'wpuc_certificateLoginForm' );


function wpuc_certificateLoginForm( $atts ) {

    $apiResponse = "";
    include plugin_dir_path(__FILE__) . '/../../../enviroment_variables.php';

    // Check If User Logged In
    $origin = $_SERVER['HTTP_HOST'];
    $serialkey = get_option( 'dashboardplugincertificate_serialkey' );

    $args = array(
        'body' => array(),
        'headers'     => array(
            'credentials' => 'true',
            'authorization' => $serialkey,
            'origin' => $origin
        ),
        'cookies'     => wpuc_filterSessionCookiesToUseThem(),
    );
    
    $getResponse = wp_remote_post($checkifloggedin, $args );
    $bodyApiResponse = wp_remote_retrieve_body($getResponse);

    if ($bodyApiResponse == "Already Logged In") {
        $pageid = get_option( 'certificationclientpage' );
        $pagelink = get_page_link($pageid);

        echo '<script>window.location.href = "' . esc_url($pagelink) . '";</script>';
    }

    // Submit Login
    if (isset( $_POST['submitlogin'] )) {
        
        $origin = $_SERVER['HTTP_HOST'];
        $serialkey = get_option( 'dashboardplugincertificate_serialkey' );

        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);

        if (empty($_POST["email"]) || empty($_POST["password"])) {
            $apiResponse = "In valid email or password";
        } else {
            $args = array(
                'body' => array(
                    'email' => $email,
                    'password' => $password
                ),
                'headers'     => array(
                    'credentials' => 'true',
                    'authorization' => $serialkey,
                    'origin' => $origin
                ),
                'cookies'     => wpuc_filterSessionCookiesToUseThem(),
            );
            
            $getResponse = wp_remote_post($userlogin, $args );
            $apiResponse = wp_remote_retrieve_body($getResponse);
            wpuc_filterCookies(wp_remote_retrieve_cookies($getResponse));
        }
    }

    // Submit Confirmation Code
    if (isset( $_POST['submitconfirmationcode'] )) {
        
        $origin = $_SERVER['HTTP_HOST'];
        $serialkey = get_option( 'dashboardplugincertificate_serialkey' );
        
        $confirmationcode = sanitize_text_field($_POST['confirmationcode']);

        if (empty($_POST["confirmationcode"])) {
            $apiResponse = "In valid confirm code";
        } else {
            $args = array(
                'body' => array(
                    'confirmationcode' => $confirmationcode
                ),
                'headers'     => array(
                    'credentials' => 'true',
                    'authorization' => $serialkey,
                    'origin' => $origin
                ),
                'cookies'     => wpuc_filterSessionCookiesToUseThem(),
            );
            
            $getResponse = wp_remote_post($checkconfirmationcode, $args );
            $apiResponse = wp_remote_retrieve_body($getResponse);
            wpuc_filterCookies(wp_remote_retrieve_cookies($getResponse));
        }
    }

    if ($apiResponse == "correct credentials" || $apiResponse == "the code is correct" || $apiResponse == "Already Logged In") {
        $pageid = get_option( 'certificationclientpage' );
        $pagelink = get_page_link($pageid);

        echo '<script>window.location.href = "' . esc_url($pagelink) . '";</script>';
        
    }
    ?>
    
    <form method="post" action="" class="wpdashboard_usersloginform" >        
        <?php if ($apiResponse == "email confirmation sent" || $apiResponse == "the code is wrong") { ?>
            <?php echo '<h6>' . esc_html($apiResponse) . '</h6>'; ?>
            <div class="form-group-nobootstrap">
                <input type="number" name="confirmationcode" required class="form-control" placeholder="Confirmation Code" autocomplete="off" >
            </div>
            <div class="form-group-nobootstrap">
                <input type="submit" name="submitconfirmationcode" value="Check" >
            </div>
        <?php } else { ?>
            <div class="form-group-nobootstrap">
                <input type="text" name="email" value="<?php if(isset($_POST['email'])) { echo esc_html($_POST['email']);} ?>" required class="form-control" placeholder="Email" autocomplete="off" >
            </div>
            <div class="form-group-nobootstrap">
                <input type="password" name="password" required class="form-control" placeholder="Password" >
            </div>
            <div class="form-group-nobootstrap">
                <input type="submit" name="submitlogin" value="Login" >
            </div>

            <?php echo '<h6>' . esc_html($apiResponse) . '</h6>'; ?>
        <?php } ?>
    </form>
    
    <?php
}
