<?php

add_action( 'init', 'wpuc_addNewUsers' );


function wpuc_addNewUsers () {
    wp_insert_user(array(
        'user_pass' => 'tahtaha',
        'user_login' => 'Taha Test',
        'user_nicename' => 'TahaTest',
        'user_email' => 'taha@gmail.com',
        'first_name' => 'Taha',
        'last_name' => 'Test',
        'role' => 'guest_get_certificate'
    ));
}
