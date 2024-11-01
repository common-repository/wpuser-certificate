<?php
/*
    Plugin Name: WPUser Certificate
    Plugin URI: https://wpusercertificate.com/
    Description: Plugin for generating a certificate for every user.
    Version: 1.0
    Author: Taha Halabi
    Author URI: https://tahahalabi.com
    Text Domain: wpuser-certificate
    Domain Path: /languages
*/


/* Start Accessed Directly */
require_once(plugin_dir_path(__FILE__).'/functions/accessed_directly.php');
/* End Accessed Directly */


/* Start Add Client Role */
include 'functions/addclientrole.php';
/* End Add Client Role */

/* Start Add New User */
// include 'functions/addnewusers.php';
/* End Add New User */

/* Start Front End Pages */
include 'pages/frontendpages/pages.php';
/* End Front End Pages */
function wpuc_styles () {
    wp_enqueue_style('wpuc-bootstrapcss', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css' );
    wp_enqueue_style('wpuc-admincss', plugin_dir_url(__FILE__) . 'assets/css/certificationadminpage.css' );
}
add_action('admin_head', 'wpuc_styles');
add_action('wp_enqueue_scripts', 'wpuc_styles');

function wpuc_scripts () {
    wp_enqueue_script('wpuc-bootstrapjs', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js' );
    wp_enqueue_script('wpuc-textviewer', plugin_dir_url(__FILE__) . 'assets/js/adminpages/certificationpage/textviewer.js' );
    wp_enqueue_script('wpuc-submitcertificatedata', plugin_dir_url(__FILE__) . 'assets/js/adminpages/certificationpage/submitcertificatedata.js' );
    wp_enqueue_script('wpuc-excelfile', plugin_dir_url(__FILE__) . 'assets/js/adminpages/read-excel-file.min.js' );
}
add_action('admin_footer', 'wpuc_scripts');

add_action('admin_menu', 'wpuc_setup_menu');
 
/*
add_menu_page( string $page_title, string $menu_title, string $capability, 
               string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
 */
function wpuc_setup_menu(){
    add_menu_page( 'Certificate Dashboard Plugin', 'WPUC', 'manage_options', 'dashboard-setups', 'wpuc_setups_init', plugin_dir_url(__FILE__) . 'images/dashboardcertificateicon.png', 4);
    add_submenu_page('dashboard-setups', 'Courses Edit', 'Courses', 'manage_options', 'dashboard-courses', 'wpuc_courses' );
    add_submenu_page('dashboard-setups', 'License Key', 'License', 'manage_options', 'dashboard-license', 'wpuc_license' );
}

function wpuc_license() {
    include 'pages/backendpages/license.php';   
}

function wpuc_courses() {
    include 'functions/plugins_options.php';

    if ($checkIfBought == 1) {
        include 'pages/backendpages/courses.php';
    } else {
        wpuc_not_bought();
    }
    
}

function wpuc_setups_init () {
    include 'functions/plugins_options.php';

    if ($checkIfBought == 1) {
        include 'pages/backendpages/setups.php';
    } else {
        wpuc_not_bought();
    }
    
}

function wpuc_not_bought () {
    echo '<h2 class="notbought">Please go to license page first and activate your plugin in order to use it</h2>';
}

// Add New Users
include 'functions/addnewusersapi.php';


// WPUC Login Page
function wpuc_loginAddPage ($templates) {
    $templates['wpuc_login.php'] = 'WPUC Login Template';
    return $templates;
}
add_filter ('theme_page_templates', 'wpuc_loginAddPage');

function wpuc_loginRedirectAddPage ($template) {
    $post = get_post();
    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ('wpuc_login.php' == basename ($page_template))
        $template = plugin_dir_path(__FILE__) . '/pages/templates/wpuc_login.php';
    return $template;
}
add_filter ('page_template', 'wpuc_loginRedirectAddPage');


// WPUC Dashboard Page
function wpuc_dashboardAddPage ($templates) {
    $templates['wpuc_dashboard.php'] = 'WPUC Dashboard Template';
    return $templates;
}
add_filter ('theme_page_templates', 'wpuc_dashboardAddPage');

function wpuc_dashboardRedirectAddPage ($template) {
    $post = get_post();
    $page_template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ('wpuc_dashboard.php' == basename ($page_template))
        $template = plugin_dir_path(__FILE__) . '/pages/templates/wpuc_dashboard.php';
    return $template;
}

add_filter ('page_template', 'wpuc_dashboardRedirectAddPage');