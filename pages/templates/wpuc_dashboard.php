


<?php 

    // Exit if accessed directly
    if(!defined('ABSPATH')){
        exit;
    }
    /* End Accessed Directly */
    
    session_start();

    if (isset($_SESSION["wpuc_cookie"]) == null) {
        $_SESSION["wpuc_cookie"] = '';
    }
?>

<?php get_header(); ?>

<div class="container" >
    <?php
    // TO SHOW THE PAGE CONTENTS
    while ( have_posts() ) : the_post(); ?> <!--Because the_content() works only inside a WP Loop -->
        <div class="entry-content-page">
            <?php the_content(); ?> <!-- Page Content -->
        </div><!-- .entry-content-page -->

    <?php
    endwhile; //resetting the page loop
    wp_reset_query(); //resetting the page query
    ?>
</div>

<?php get_footer(); ?>
