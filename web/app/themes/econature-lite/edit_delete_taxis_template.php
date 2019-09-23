<?php
/**
 * Template Name: Edit/Delete Taxi Template
 **/
get_header();

if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action'])) {
    $post_id = $_POST['post_id'];
    if ($_POST['action'] == "delete_taxi") {
        wp_delete_post($post_id);
    } else if ($_POST['action'] == "edit_taxi") {
        $owner_name = $_POST['owner_name'];
        $location_text = $_POST['location_text'];
        $color_button = $_POST['color_button'];
        $my_post = array(
            'ID'            => $post_id,
            'post_title'    => $owner_name
        );
        wp_update_post($my_post);
        if ($location_text != null) {
            $latLngTaxi = explode(",", $location_text);
            update_post_meta($post_id, "distance_to_station", get_distance_to_taxi_station($latLngTaxi[0], $latLngTaxi[1]));
            update_post_meta($post_id, 'taxi_location', $location_text);
        }
        if ($color_button != null) {
            wp_delete_object_term_relationships($post_id, 'Colors');
            wp_set_object_terms($post_id, $color_button, 'Colors');
        }
    } else if ($_POST['action'] == "remove_destination") {
        update_post_meta($post_id, 'taxi_destination', 'null');
    }
}

?>
    <h3 class="card-header">Administrate taxis</h3>
<?php get_edit_taxi_cards(); ?>
    </div>
    <div class="clear"></div>
    </div></div>

<?php get_footer(); ?>