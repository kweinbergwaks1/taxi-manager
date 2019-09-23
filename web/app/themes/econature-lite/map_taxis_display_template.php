<?php
/**
 * Template Name: Map Display Taxi Template
 **/
get_header();
get_filter_section_map(); ?>
    <script>
        var map;
        var markers = [];

        function initMap() {
            <?php init_map_script() ?>

            <?php $query = new WP_Query(array('post_type' => 'taxi'));
            while ($query->have_posts()) :
                $query->the_post(); ?>
                <?php
                global $post;
                $location_string = get_post_meta($post->ID, 'taxi_location', true);
                $latlngArray = explode(",", $location_string); ?>
                var latlng = {lat: parseFloat(<?php echo $latlngArray[0] ?>), lng: parseFloat(<?php echo $latlngArray[1] ?>)};
                if(latlng.lat !== 0 && latlng.lng !== 0) {
                    geocoder.geocode({'location': latlng}, function(results, status) {
                        if (status === 'OK') {
                            var marker = new google.maps.Marker({
                                map: map,
                                title: "<?php the_title() ?>",
                                position: results[0].geometry.location,
                                optimized: false
                            });
                            markers.push(marker);
                        } else {
                            alert('Geocode was not successful for the following reason: ' + status);
                        }
                    });
                }
            <?php endwhile; // end of the loop.
            wp_reset_postdata();?>
        }

        <?php populate_map_with_ajax_data(); ?>

    </script>
    </div>
    <div class="clear"></div>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={YOUR-API-KEY}&callback=initMap">
    </script>
<?php ajax_script_for_filtering_map() ?>
    </div></div>

<?php get_footer(); ?>