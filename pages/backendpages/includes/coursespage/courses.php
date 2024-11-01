<script>
    function wpuc_createTheCourses () {
        var coursesData = document.getElementById("coursesData")
        coursesData = JSON.parse(coursesData.innerHTML)
        console.log(coursesData);

        var coursesParent = document.getElementById("courses");

        for (let i = 0; i < coursesData.length; i = i + 1) {
            var createCourse = `
                <div class="col-md-3">
                    <div class="singlecourse form-group">
                        <h6 class="title" id="title" >${coursesData[i]['name']}</h6>
                        <input type="hidden" id="courseid" name="courseid" value="${coursesData[i]['id']}" >
                        <div class="bottomcontent" >
                            <a href=
                            "<?php echo esc_url($_SERVER['REQUEST_URI']); ?>&viewcourse=yes&coursename=${coursesData[i]['name']}&courseid=${coursesData[i]['id']}"
                            class="btn btn-info viewcoursebtn" >View</a>
                            <button class="btn btn-primary editcourse" data-bs-toggle="modal" data-bs-target="#editcoursemodal" >Edit</button>
                            <form method="post" action="" class="deletecourse">
                                <input type="hidden" name="coursename" value="${coursesData[i]['name']}" >
                                <input type="hidden" name="courseid" value="${coursesData[i]['id']}" >
                                <input type="hidden" name="deletecourse" value="delete" >
                                <button type="submit" class="btn btn-danger deletecourse" >Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            `;

            coursesParent.innerHTML += createCourse;
        }

        jQuery("#deletecourse").submit(function(e){
            
            
            var check = confirm("This will delete the certificate of this course, Are you sure?");

            if (check == true) {
                
            } else {
                e.preventDefault();
            }
        })
    
    }
    
</script>

<?php

    include plugin_dir_path(__FILE__) . '/../../../../enviroment_variables.php';
    
    // Add
    if ( isset( $_POST['addcourse'] ) ) :

        $coursename = sanitize_text_field($_POST['coursename']);

        if (empty($coursename)) {
            $apiResponse = "Empty Course Name";

            echo '<h4>' . esc_html($apiResponse) . '</h4>';
        } else {

            $origin = $_SERVER['HTTP_HOST'];
            $serialkey = get_option( 'dashboardplugincertificate_serialkey' );
            
            $args = array(
                'body' => array(
                    'coursename' => $coursename
                ),
                'headers'     => array(
                    'credentials' => 'true',
                    'authorization' => $serialkey,
                    'origin' => $origin
                )
            );
            
            $getResponse = wp_remote_post($addcourse, $args );
            $apiResponse = wp_remote_retrieve_body($getResponse);
    
            if ($apiResponse == "course created") {
                update_option( $coursename, $coursename );
                echo '<h4>' . esc_html($apiResponse) . '</h4>';
            } else if ($apiResponse == "err") {
                echo '<h4>' . esc_html($apiResponse) . '</h4>';
            } else {
                echo '<h4>' . esc_html($apiResponse) . '</h4>';
            }
        }
    endif;

    // Delete deletecourse
    if ( isset( $_POST['deletecourse'] ) ) :
        $courseid = sanitize_text_field($_POST['courseid']);
        if (empty($courseid)) {
            $apiResponse = "Empty Course Id";

            echo '<h4>' . esc_html($apiResponse) . '</h4>';
        } else {
            $origin = $_SERVER['HTTP_HOST'];
            $serialkey = get_option( 'dashboardplugincertificate_serialkey' );

            $args = array(
                'body' => array(
                    'courseid' => $courseid
                ),
                'headers'     => array(
                    'credentials' => 'true',
                    'authorization' => $serialkey,
                    'origin' => $origin
                )
            );
            
            $getResponse = wp_remote_post($deletecourse, $args );
            $apiResponse = wp_remote_retrieve_body($getResponse);

            if ($apiResponse == "course deleted") {
                delete_option( sanitize_text_field($_POST['coursename']) );
            }
            
            echo '<h4>' . esc_html($apiResponse) . '</h4>';
        }
    endif;

    // Edit Course
    if ( isset( $_POST['editcourse'] ) ) :
        $courseid = sanitize_text_field($_POST['courseid']);
        $coursename = sanitize_text_field($_POST['coursename']);
        
        if (empty($courseid) || empty($coursename)) {
            $apiResponse = "Empty Course Id or Course Name";

            echo '<h4>' . esc_html($apiResponse) . '</h4>';
        } else {
            $origin = $_SERVER['HTTP_HOST'];
            $serialkey = get_option( 'dashboardplugincertificate_serialkey' );

            $args = array(
                'body' => array(
                    'courseid' => $courseid,
                    'coursename' => $coursename
                ),
                'headers'     => array(
                    'credentials' => 'true',
                    'authorization' => $serialkey,
                    'origin' => $origin
                )
            );
            
            $getResponse = wp_remote_post($editcourse, $args );
            $apiResponse = wp_remote_retrieve_body($getResponse);
            
            if ($apiResponse == "course edited") {
                delete_option( sanitize_text_field($_POST['oldcoursename']) );
                update_option( $coursename, $coursename );
            }

            echo '<h4>' . esc_html($apiResponse) . '</h4>';
        }
    endif;
?>


<div class="dashboardcertificate_container">
    <h5 class="maintitle">Add A Course</h5>
    <form method="post" action="" >
        <div class="form-group">
            <input type="text" class="form-control" placeholder="Course Name" name="coursename" autocomplete="off" >
        </div>
        <input type="hidden" name="oldcoursename" >
        <input type="hidden" name="addcourse" >
        <div class="form-group">
            <input type="submit" class="form-control btn-success"  >
        </div>
    </form>

    <div class="allcourses" >
        <div class="row" id="courses">

        </div>
    </div>
</div>

<?php
    // Select All User Courses
    $origin = $_SERVER['HTTP_HOST'];
    $serialkey = get_option( 'dashboardplugincertificate_serialkey' );
    
    $args = array(
        'body' => array(),
        'headers'     => array(
            'credentials' => 'true',
            'authorization' => $serialkey,
            'origin' => $origin
        )
    );
    
    $getResponse = wp_remote_get($getMyCourses, $args );
    $mycoursesdata = wp_remote_retrieve_body($getResponse);
    
    echo "<span id='coursesData' style='display: none'>" . esc_html($mycoursesdata) . "</span> <script>wpuc_createTheCourses()</script>";

?>

<script>
// Handle Delete Form

jQuery(document).on("submit", ".deletecourse", function(e) {
    var check = confirm("Are You Sure");

    if (check == false) {
        e.preventDefault();
    }
})

// editcourse

jQuery(document).on("click", ".editcourse", function(e) {
    jQuery("#coursename").val(jQuery(this).parent().parent().find(".title").eq(0).text());

    jQuery("#oldcoursename").val(jQuery(this).parent().parent().find(".title").eq(0).text());

    jQuery("#editcourseid").val(jQuery(this).parent().parent().find("#courseid").val());
})
</script>

<!-- Modal -->
<div class="modal fade" id="editcoursemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Edit Course</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="" >
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" id="coursename" class="form-control" placeholder="Course Name" name="coursename" >
                </div>
                <input type="hidden" name="oldcoursename" id="oldcoursename" >
                <input type="hidden" name="editcourse" >
                <input type="hidden" name="courseid" id="editcourseid" >
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Edit</button>
            </div>
        </form>
    </div>
  </div>
</div>