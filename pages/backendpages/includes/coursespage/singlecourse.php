<div class="dashboardcertificate_container">
    <div class="row">
        <div class="col-md-9">
            <h5 class="maintitle">Course Name: <?php echo esc_html($_GET['coursename']); ?></h5>
        </div>
        <div class="col-md-3">
        
        </div>
    </div>
    <nav>
    <div class="nav nav-tabs coursestabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" sid="nav-users" data-mdb-toggle="tab" href="#nav-users" role="tab" aria-controls="nav-users" aria-selected="true">Users</a>
        <a class="nav-item nav-link" sid="nav-certification" data-mdb-toggle="tab" href="#nav-certification" role="tab" aria-controls="nav-certification" aria-selected="false">Certifications</a>
    </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-users" role="tabpanel" aria-labelledby="nav-users">
            <?php require_once dirname(__DIR__) . "/coursespage/users.php"; ?>
        </div>
        <div class="tab-pane fade" id="nav-certification" role="tabpanel" aria-labelledby="nav-certification">
            <?php require_once dirname(__DIR__) . "/coursespage/certificatedetails.php"; ?>
        </div>
    </div>
    <script>
        jQuery('#nav-tab a').click(function (e) {
            e.preventDefault();
            jQuery(this).tab('show');

            jQuery(".tab-content").find("#" + jQuery(this).attr("sid")).tab('show');
        });
    </script>
</div>