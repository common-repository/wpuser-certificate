<?php

add_action( 'admin_enqueue_scripts', 'wpuc_handleUsersPanel' );

function wpuc_handleUsersPanel() {

   // JavaScript
   wp_enqueue_script( 'addusers-js', plugin_dir_url(__FILE__) . '../assets/js/adminpages/courses/addusers.js',array('jquery'));

   // Pass ajax_url to script.js
   wp_localize_script( 'addusers-js', 'plugin_ajax_object',
   array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_ajax_getstudentsdata', 'wpuc_AddStudents' );

function wpuc_AddStudents() {
    if ( current_user_can( 'manage_options') ) {

        global $wpdb;

        if (count($_POST['studentsData']) == 0) {
            echo json_encode('empty');
            wp_die();
        } else {
            $data = array();
            for ($i = 0; $i < count($_POST['studentsData']); $i = $i + 1) {
                for ($t = 0; $t < count($_POST['studentsData'][$i]); $t = $t + 1) {
                    $data[$i][$t] = sanitize_text_field($_POST['studentsData'][$i][$t]);
                }
            }
    
            
            $students = json_encode($data);
            
            include plugin_dir_path(__FILE__) . '/../enviroment_variables.php';

            $origin = $_SERVER['HTTP_HOST'];
            $serialkey = get_option( 'dashboardplugincertificate_serialkey' );

            $args = array(
                'body' => array(
                    'students' => $students
                ),
                'headers'     => array(
                    'credentials' => 'true',
                    'authorization' => $serialkey,
                    'origin' => $origin
                )
            );
            
            $apiResponse = wp_remote_post($addnewusers, $args );
            $bodyApiResponse = wp_remote_retrieve_body($apiResponse);

            echo json_encode('All Users Added');
            wp_die(); 
        }
        
    } else {
        echo 'not';
        wp_die();
    }
}
 

?>