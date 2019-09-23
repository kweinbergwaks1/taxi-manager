<?php
/**
 * Template Name: Drive Taxi Template
 **/

get_header();

if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) &&  $_POST['action'] == "drive_taxi") {
    $drive_destination = $_POST['destination'];
    $post_id = $_POST['post_id'];
    if ($drive_destination != null) {
        update_post_meta($post_id, 'taxi_destination', $drive_destination);
    }
}
?>
<h3 class="card-header">Drive</h3>

<?php get_drive_taxi_cards(); ?>

    </div>

<?php get_sidebar(); ?>

    <div class="clear"></div>
    </div></div>

<?php get_footer(); ?>