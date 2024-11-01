<?php

    $url = "https://wpusercertificate.com";
    $license_url = $url . "/checkmycredentials";
    $saveUserImge = $url . "/saveuserimage";
    $createUserCertificate = $url . "/createusercertificate";

    $userlogin = $url . "/users/login";
    $checkifloggedin = $url . "/users/checkuserloggedin";
    $checkconfirmationcode = $url . "/users/checkuserconfirmationcode";


    $addcourse = $url . "/courses/addcourse";
    $getMyCourses = $url . "/courses/getmycourses";
    $deletecourse = $url . "/courses/deletecourse";
    $editcourse = $url . "/courses/editcourse";

    $addnewusers = $url . "/users/addusers";

    function wpuc_filterCookies ($resCookies) {
        $cookies      = array();

        foreach ( $resCookies as $value ) {
            foreach($value as $name => $valueOfValue) {
                $valueOfValue = sanitize_text_field($valueOfValue);
                $cookies[sanitize_text_field($name)] = $valueOfValue;
            }
        }

        if (isset($cookies['name']) != false && isset($cookies['value']) != false && isset($cookies['path']) != false && isset($cookies['domain']) != false && isset($cookies['host_only']) != false) {
            $newCookies = new WP_Http_Cookie( array( 'name' => $cookies['name'], 'value' => $cookies['value'], 'path' => $cookies['path'], 'domain' => $cookies['domain'], 'host_only' => $cookies['host_only'] ) );
    
            $arrayOfNewCookies = array($newCookies);
    
            $_SESSION["wpuc_cookie"] = $arrayOfNewCookies;
        } else {
            $_SESSION["wpuc_cookie"] = '';
        }
        
    }

    function wpuc_filterSessionCookiesToUseThem () {
        $cookies      = array();

        if ($_SESSION["wpuc_cookie"] != '') {
            foreach ( $_SESSION["wpuc_cookie"] as $value ) {
                foreach($value as $name => $valueOfValue) {
                    $valueOfValue = sanitize_text_field($valueOfValue);
                    $cookies[sanitize_text_field($name)] = $valueOfValue;
                }
            }
    
            if (isset($cookies['name']) != false && isset($cookies['value']) != false && isset($cookies['path']) != false && isset($cookies['domain']) != false && isset($cookies['host_only']) != false) {
                $newCookies = new WP_Http_Cookie( array( 'name' => $cookies['name'], 'value' => $cookies['value'], 'path' => $cookies['path'], 'domain' => $cookies['domain'], 'host_only' => $cookies['host_only'] ) );
        
                $arrayOfNewCookies = array($newCookies);
        
                return $arrayOfNewCookies;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }