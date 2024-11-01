<?php



    // Exit if accessed directly
    if(!defined('ABSPATH')){
        exit;
    }
    /* End Accessed Directly */

add_shortcode( 'wpcertificate_dashboarduser_page', 'wpuc_certificateViewPage' );

function wpuc_certificateViewPage( $atts ) {

    ?>
    <script>
        function wpuc_createTheCourses() {
            let coursesDataMain = document.getElementById("coursesData");

            console.log(coursesData)

            if (coursesData.innerHTML == "Not logged In") {

                document.getElementById("wpuserstatus").innerHTML = "Not Logged In";
                return;
            }

            coursesData = JSON.parse(coursesDataMain.innerHTML);

            var table = `
            
            <table class="table">
                <thead>
                    <th>#</th>
                    <th>Course Name</th>
                    <th>Download</th>
                </thead>
                <tbody id="mycoursestable">
                
                
            `;
            
            var mycoursestable = document.getElementById("mycoursestable");
            for (let i = 0; i < coursesData.length; i = i + 1) {
                var createCourse = `
                    <tr>
                        <td>${i+1}</td>
                        <td>${coursesData[i]['coursename']}</td>
                        <td>
                            <a href="${coursesDataMain.getAttribute("link")}/createusercertificate/user?email=${coursesData[i]['email']}&key=${coursesData[i]['randomnumber']}"
                            download="${coursesData[i]['coursename']}.pdf" target="_blank" >
                            Download
                            </a>
                        </td>
                    </tr>
                `;

                table += createCourse;
            }


            if (coursesData.length == 0) {
                var createCourse = `
                    <tr>
                        <td colspan='3'>No Courses Found</td>
                    </tr>
                `;

                table += createCourse;
            }

            table += `</tbody>
            </table>`;

            document.getElementById("displaydata").innerHTML = table;
        }
    </script>

    <?php

    include plugin_dir_path(__FILE__) . '/../../../enviroment_variables.php';

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

    
    $getResponse = wp_remote_post($createUserCertificate, $args );
    $apiResponse = wp_remote_retrieve_body($getResponse);

    ?>

    <h4 id="wpuserstatus" ></h4>

    <div id="displaydata" ></div>

    <?php

    echo "<span id='coursesData' style='display: none' link='" . esc_url($url) . "'>" . esc_html($apiResponse) . "</span> <script>wpuc_createTheCourses()</script>";

    ?>

    <?php


}