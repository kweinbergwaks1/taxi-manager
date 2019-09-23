<?php
/**
 * Template Name: Add Taxi Template
 **/

get_header();
$errors = new WP_Error();
$sub_success = '';

if ('POST' == $_SERVER['REQUEST_METHOD'] && !empty($_POST['action']) &&  $_POST['action'] == "add_taxi") {
    if (isset($_POST['owner_name']) && isset($_POST['location_text']) && isset($_POST['filter_color'])) {
        $owner_name =  $_POST['owner_name'];
        $location_text =  $_POST['location_text'];
        $color_button =  $_POST['filter_color'];

        // Checks that the input it's a geo-locational valid number.
        if (!strpos($location_text, ",")) {
            $location_text = "0,0,(not valid location)";
        }
        $latLngTaxi = explode(",", $location_text);
        if (is_geo_valid('latitude', $latLngTaxi[0]) && is_geo_valid('longitude', $latLngTaxi[1])) {
            $distanceToStation = get_distance_to_taxi_station($latLngTaxi[0], $latLngTaxi[1]);
        } else {
            $distanceToStation = 0;
            $location_text = "0,0,(not valid location)";
        }

        $my_post = array(
            'post_title'    => $owner_name,
            'post_status'   => 'publish',
            'post_type'     => 'taxi'
        );
        $post_id = wp_insert_post($my_post);
        if ($post_id != null) {
            $sub_success = 'Success';
            wp_set_object_terms($post_id, $color_button, 'Colors');
            update_post_meta($post_id, "taxi_location", $location_text);
            update_post_meta($post_id, "taxi_destination", 'null');
            update_post_meta($post_id, "distance_to_station", $distanceToStation);
        }
    } else {
        $errors->add('empty_title', __('All fields marked with * are required', 'sws'));
    }
}
?>
    <h3 class="card-header">Add a new taxi</h3>

<?php if ($sub_success == 'Success') {
    echo '<div class="alert alert-success" role="alert">' . __('Taxi added to the database', 'post_new') . '</div>';
}
if (isset($errors) && is_array($errors) && sizeof($errors)>0 && $errors->get_error_code()) :
    foreach ($errors->errors as $error) {
        echo '<div class="alert alert-danger" role="alert">'.$error[0].'</div>';
    }
endif; ?>

<?php get_add_taxi_form() ?>

    </div>
    <div class="clear"></div>
    </div></div>

<?php get_footer(); ?>