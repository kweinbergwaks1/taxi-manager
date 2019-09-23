<?php
/**
 * Econature Lite functions and definitions
 *
 * @package Econature Lite
 */

if (! function_exists('econature_lite_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which runs
     * before the init hook. The init hook is too late for some features, such as indicating
     * support post thumbnails.
     */
    function econature_lite_setup()
    {

        if (! isset($content_width)) {
            $content_width = 640; /* pixels */
        }
        load_theme_textdomain('econature-lite', get_template_directory() . '/languages');
        add_theme_support('automatic-feed-links');
        add_theme_support('post-thumbnails');
        add_theme_support('title-tag');
        add_theme_support('custom-logo', array(
            'height'      => 240,
            'width'       => 240,
            'flex-height' => true,
        ));
        add_image_size('econature-lite-homepage-thumb', true);
        register_nav_menus(array(
            'primary' => __('Primary Menu', 'econature-lite'),
        ));
        add_theme_support('custom-background', array(
            'default-color' => 'f1f1f1'
        ));
        add_editor_style(array( 'editor-style.css', econature_lite_font_url() ));
    }
endif; // econature_lite_setup
add_action('after_setup_theme', 'econature_lite_setup');


function econature_lite_widgets_init()
{
    register_sidebar(array(
        'name'          => __('Blog Sidebar', 'econature-lite'),
        'description'   => __('Appears on blog page sidebar', 'econature-lite'),
        'id'            => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'econature_lite_widgets_init');

function econature_lite_font_url()
{
    $font_url = '';

    /* Translators: If there are any character that are
    * not supported by Assistant, translate this to off, do not
    * translate into your own language.
    */
    $assistant = _x('on', 'Assistant font:on or off', 'econature-lite');

    /* Translators: If there are any character that are
    * not supported by Roboto, translate this to off, do not
    * translate into your own language.
    */
    $roboto = _x('on', 'Roboto font:on or off', 'econature-lite');



    if ('off' !== $assistant || 'off' !== $roboto) {
        $font_family = array();

        if ('off' !== $assistant) {
            $font_family[] = 'assistant:400,500,600,700';
        }

        if ('off' !== $roboto) {
            $font_family[] = 'Roboto:400,700';
        }

        $query_args = array(
            'family'    => urlencode(implode('|', $font_family)),
        );

        $font_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
    }

    return $font_url;
}

function econature_lite_scripts()
{
    wp_enqueue_style('econature-lite-font', econature_lite_font_url(), array());
    wp_enqueue_style('econature-lite-basic-style', get_stylesheet_uri());
    wp_enqueue_style('econature-lite-responsive-style', get_template_directory_uri().'/css/theme-responsive.css');
    wp_enqueue_style('nivo-style', get_template_directory_uri().'/css/nivo-slider.css');
    wp_enqueue_style('font-awesome-style', get_template_directory_uri().'/css/font-awesome.css');
    wp_enqueue_script('jquery-nivo-slider-js', get_template_directory_uri() . '/js/jquery.nivo.slider.js', array('jquery'));
    wp_enqueue_script('econature-lite-customscripts', get_template_directory_uri() . '/js/custom.js', array('jquery'));
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'econature_lite_scripts');

/**
 * Use front-page.php when Front page displays is set to a static page.
 *
 *
 * @param string $template front-page.php.
 *
 * @return string The template to be used: blank if is_home() is true (defaults to index.php), else $template.
 */
function econature_lite_front_page_template($template)
{
    return is_home() ? '' : $template;
}
add_filter('frontpage_template', 'econature_lite_front_page_template');

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function econature_lite_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">' . "\n", get_bloginfo('pingback_url'));
    }
}
add_action('wp_head', 'econature_lite_pingback_header');

/**
 * Creates Taxi Custom Post Type, his taxonomy and his Custom Fields (Taxi Location and Destination).
 * Perform the specified post of the custom fields.
 * Retrieves the custom information for add/view/edit post page.
 */
function taxi_init()
{
    $args = array(
        'label' => 'Taxi',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'rewrite' => array('slug' => 'taxi'),
        'query_var' => true,
        'menu_icon' => 'dashicons-edit',
        'supports' => array(
            'title',
            'author',)
    );
    register_post_type('taxi', $args);
}
add_action('init', 'taxi_init');

register_taxonomy("Colors", array("taxi"), array("hierarchical" => true, "label" => "Taxi Color", "rewrite" => true));

add_action("admin_init", "admin_init");

function admin_init()
{
    add_meta_box("taxi_location", "Location of the Taxi", "taxi_location", "taxi", "normal", "low");
    add_meta_box("taxi_destination", "Trip destination for the Taxi", "taxi_destination", "taxi", "normal", "low");
    add_meta_box("distance_to_station", "The distance to the Taxi Station", "distance_to_station", "taxi", "normal", "low");
}

function taxi_location()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $taxi_location = $custom["taxi_location"][0];
    ?>
    <label>Coordinates:</label>
    <input name="taxi_location" value="<?php echo $taxi_location; ?>" />
    <?php
}

function taxi_destination()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $taxi_destination = $custom["taxi_destination"][0];
    ?>
    <label>Coordinates:</label>
    <input name="taxi_destination" value="<?php echo $taxi_destination; ?>" />
    <?php
}

function distance_to_station()
{
    global $post;
    $custom = get_post_custom($post->ID);
    $distance_to_station = $custom["distance_to_station"][0];
    ?>
    <label>Distance to station (in meters):</label>
    <input name="distance_to_station" value="<?php echo $distance_to_station; ?>" />
    <?php
}

add_action('save_post', 'save_details');

function save_details()
{
    global $post;
    $taxi_location = isset($_POST['taxi_location']) ? $_POST['taxi_location'] : '';
    $taxi_destination = isset($_POST['taxi_destination']) ? $_POST['taxi_destination'] : '';
    $distance_to_station = isset($_POST['distance_to_station']) ? $_POST['distance_to_station'] : '';

    update_post_meta($post->ID, "taxi_location", $taxi_location);
    update_post_meta($post->ID, "taxi_destination", $taxi_destination);
    update_post_meta($post->ID, "distance_to_station", $distance_to_station);
}

add_action("manage_posts_custom_column", "taxi_custom_columns");
add_filter("manage_edit-taxi_columns", "taxi_edit_columns");

function taxi_edit_columns($columns)
{
    $columns = array(
        "cb"                    => "<input type='checkbox' />",
        "title"                 => "Taxi Owner",
        "taxi_location"         => "Taxi Location",
        "taxi_destination"      => "Taxi Destination",
        "distance_to_station"   => "Distance to the Taxi Station (in meters)",
        "colors"                => "Color",
    );

    return $columns;
}
function taxi_custom_columns($column)
{
    global $post;

    switch ($column) {
        case "taxi_location":
            $custom = get_post_custom();
            echo $custom["taxi_location"][0];
            break;
        case "taxi_destination":
            $custom = get_post_custom();
            echo $custom["taxi_destination"][0];
            break;
        case "distance_to_station":
            $custom = get_post_custom();
            echo $custom["distance_to_station"][0];
            break;
        case "colors":
            echo get_the_term_list($post->ID, 'Colors', '', ', ', '');
            break;
    }
}

add_action('wp_ajax_myfilter', 'map_filter_function');
add_action('wp_ajax_nopriv_myfilter', 'map_filter_function');

function map_filter_function()
{
    if ($_POST['filter_range'] != null && $_POST['filter_color'] == null) {
        $args = array(
            'post_type' => 'taxi',
            'meta_query' => array(
                array(
                    'key'       => 'distance_to_station',
                    'value'     => '2000',
                    'type'      => 'NUMERIC',
                    'compare'   => '<=',
                ),
            )
        );
    } else if ($_POST['filter_range'] == null && $_POST['filter_color'] != null) {
        $args = array(
            'post_type' => 'taxi',
            'tax_query' => array(
                array(
                    'taxonomy' => 'Colors',
                    'field'    => 'slug',
                    'terms'    => $_POST['filter_color'],
                )
            )
        );
    } else if ($_POST['filter_range'] != null && $_POST['filter_color'] != null) {
        $args = array(
            'post_type' => 'taxi',
            'meta_or_tax' => true,
            'tax_query' => array(
                array(
                    'taxonomy'  => 'Colors',
                    'field'     => 'slug',
                    'terms'     =>  $_POST['filter_color']
                )
            ),
            'meta_query' => array(
                array(
                    'key'       => 'distance_to_station',
                    'value'     => '2000',
                    'type'      => 'NUMERIC',
                    'compare'   => '<=',
                ),
            )
        );
    } else if ($_POST['filter_range'] == null && $_POST['filter_color'] == null) {
        $args = array(
            'post_type' => 'taxi'
        );
    } else {
        wp_send_json(null);
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) :
        $taxis = array();
        while ($query->have_posts()) :
            $query->the_post();
            global $post;
            $data = array(
                'name' => get_the_title(),
                'location' => get_post_meta($post->ID, 'taxi_location', true),
                'color' => strip_tags(get_the_term_list($post->ID, 'Colors'))
            );
            $taxis[] = $data;
        endwhile;
        wp_send_json($taxis);
        wp_reset_postdata();
    else :
            echo 'No posts found';
        endif;

        die();
}

/**
 * Get Add Taxi form
 *
 * @return string The HTML to display the Add Taxi Form.
 */
function get_add_taxi_form()
{
    echo '<form name="addTaxiForm" id="addTaxiForm" method="post" action="" >
        <div class="form-group">
            <label for="owner_name">Owner Name</label>
            <input class="form-control" id="owner_name" name="owner_name" type="text" placeholder="Enter owner\'s name" required/>
        </div>
        <div class="form-group">
            <label for="location_text">Taxi Location</label>
            <input class="form-control" id="location_text" onkeypress="inputLocationValidation(event)" name="location_text" type="text" placeholder="Enter taxi\'s location" required/>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">Taxi Color</label>
            <div class="form-check">
                '. get_radio_buttons_with_colors(true) .'
            </div>
        </div>
        <input type="hidden" name="action" value="add_taxi" >
        <button name="button_9" class="btn btn-primary btn-block" type="submit">Submit</button>
    </form>
    
    <script>
        function inputLocationValidation(evt) {
            var theEvent = evt;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var regex = /^[0-9., ]*$/;
            if( !regex.test(key) ) {
                alert("Input only for numbers, dots and a comma");
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    </script>';
}

/**
 * Returns if a number is geo-locational valid.
 *
 * @param $type "latitude" or "longitude".
 * @param $value the variable.
 *
 *
 * @return bool if its valid or not.
 */
function is_geo_valid($type, $value)
{
    $pattern = ($type == 'latitude')
        ? '/^(\+|-)?(?:90(?:(?:\.0{1,8})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,8})?))$/'
        : '/^(\+|-)?(?:180(?:(?:\.0{1,8})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,8})?))$/';

    if (preg_match($pattern, $value)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Distance calculation to the taxi station
 *
 * @return int The distance of the taxi to the station.
 */
function get_distance_to_taxi_station($latitudeFrom, $longitudeFrom, $latitudeTo = 32.064760, $longitudeTo = 34.771398, $earthRadius = 6371000)
{
    // convert from degrees to radians
    $latFrom = deg2rad($latitudeFrom);
    $lonFrom = deg2rad($longitudeFrom);
    $latTo = deg2rad($latitudeTo);
    $lonTo = deg2rad($longitudeTo);

    $lonDelta = $lonTo - $lonFrom;
    $a = pow(cos($latTo) * sin($lonDelta), 2) +
        pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
    $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

    $angle = atan2(sqrt($a), $b);
    return $angle * $earthRadius;
}

/**
 * Get section for filtering in the map.
 *
 * @return string The HTML to display the filter section.
 */
function get_filter_section_map()
{
    echo '<div id="floating-panel">
        <div class="form-group row">
            <form class="form-inline" name="form_filtering_map" action="'. site_url() .'/wp-admin/admin-ajax.php" method="POST" id="filter">
                <div class="form-check mb-2 mr-sm-2">
                    <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Filter taxis in map:</label>
                    <input class="form-check-input" type="checkbox" id="inlineFormCheck" name="filter_range" value="nearby">
                    <label class="form-check-label" for="inlineFormCheck">
                        2km range
                    </label>
                    <div class="container-radio-buttons">
                        '. get_radio_buttons_with_colors(false) .'
                    </div>
                    <input type="hidden" name="action" value="myfilter">
                    <button type="submit" class="btn btn-primary mb-2">Apply</button>
                </div>
            </form>
        </div>
    </div>
    <div id="map"></div>';
}

/**
 * Get the script for initialize the Google Maps map.
 *
 * @return string The Javascript code for initialize the map.
 */
function init_map_script()
{
    echo "var locationTaxiStation = {lat: 32.064, lng: 34.771};
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: locationTaxiStation
            });
            var taxiStation = new google.maps.Marker({
                map: map,
                title: \"Taxi Station\",
                position: locationTaxiStation,
                optimized: false,
                icon: \"https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png\"
            });
            var geocoder = new google.maps.Geocoder();";
}

/**
 * Get the script for filter map with AJAX
 *
 * @return string The jQuery code to make the AJAX request.
 */
function ajax_script_for_filtering_map()
{
    echo "<script>
                jQuery(function($){
                    $('#filter').submit(function(){
                        var filter = $('#filter');
                        if (filter) {
                            $.ajax({
                                url:filter.attr('action'),
                                data:filter.serialize(), // form data
                                type:filter.attr('method'), // POST
                                success:function(data){
                                    filterMap(data);
                                }
                            });
                        }
                        return false;
                    });
                });
            </script>";
}

/**
 * If there is response from the AJAX filtering, it populates the map with it.
 *
 * @return string The Javascript code to display all the markers.
 */
function populate_map_with_ajax_data()
{
    echo "function filterMap(data) {
            if (data) {
                for (let i = 0; i < markers.length; i++) {
                    let found = false;
                    if (markers[i].map === null) markers[i].setMap(map);
                     for (let k = 0; k < data.length; k++) {
                        if (data[k].name === markers[i].title) {
                            found = true;
                        }
                    }
                    if (!found) markers[i].setMap(null);
                }
                found = false;
            }
        }";
}

/**
 * Get all the cards for the administrate page.
 *
 * @return string The HTML to display all the cards.
 */
function get_edit_taxi_cards()
{
    $query = new WP_Query(array('post_type' => 'taxi'));
    while ($query->have_posts()) :
        $query->the_post();
        global $post;
        echo '<figure class="figure">
        <div class="card" style="width: 16rem;">
            <form name="editTaxiForm" id="editTaxiForm" method="post" action="" >
                <div class="form-group">
                    <label for="exampleInputEmail1">Owner Name</label>
                    <input class="form-control" maxlength="150" size="30" title="" id="owner_name" name="owner_name" type="text" value="'. get_the_title() .'" required/>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Taxi Location</label>
                    <input class="form-control" maxlength="150" size="30" title="" id="location_text" name="location_text" type="text" value="'.get_post_meta($post->ID, "taxi_location", true) .'" required/>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Taxi Color</label>
                    '. get_radio_buttons_with_colors_and_color_selected_required() .'
                </div>
                <input type="hidden" name="post_id" value="'. $post->ID .'" /><input type="hidden" name="action" value="edit_taxi" > <button name="button_9" class="btn btn-primary" type="submit"> Edit taxi </button>
            </form><br />';
        if (get_post_meta($post->ID, "taxi_destination", true) != 'null') {
            echo '<form name="removeTaxiDestinationForm" id="removeTaxiDestinationForm" method="post" action="" >
                <input type="hidden" name="post_id" value="'. $post->ID .'" />
                <input type="hidden" name="action" value="remove_destination" />
                <button name="button_9" class="btn btn-danger" type="submit"> Remove destination </button>
            </form><br />';
        }
        echo '<form name="deleteTaxiForm" id="deleteTaxiForm" method="post" action="" >
                <input type="hidden" name="post_id" value="'. $post->ID .'" />
                <input type="hidden" name="action" value="delete_taxi" />
                <button name="button_9" class="btn btn-danger" type="submit"> Remove taxi </button>
            </form>
        </div>
    </figure>';
    endwhile;
    wp_reset_postdata();
}

/**
 * Get all the cards for the drive taxi page.
 *
 * @return string The HTML to display all the cards.
 */
function get_drive_taxi_cards()
{
    $query = new WP_Query(array(
        'post_type' => 'taxi',
        'meta_key' => 'taxi_destination',
        'meta_value' => 'null'));
    while ($query->have_posts()) :
        $query->the_post();
        global $post;
        echo '<figure class="figure">
        <div class="card" style="width: 16rem;">
            <form name="driveTaxiForm" id="driveTaxiForm" method="post" action="" >
                <div class="form-group">
                    <label for="owner_name">Owner Name</label>
                    <input class="form-control" id="owner_name" name="owner_name" type="text" value="'. get_the_title() .'" disabled/>
                </div>
                <div class="form-group">
                    <label for="location_text">Taxi Location</label>
                    <input class="form-control" id="location_text" name="location_text" type="text" value="'.get_post_meta($post->ID, "taxi_location", true) .'" disabled/>
                </div>
                <div class="form-group">
                    <label for="color_text">Taxi Color</label>
                    <input class="form-control" id="color_text" name="color_text" type="text" value="'. strip_tags(get_the_term_list($post->ID, "Colors")) .'" disabled/>
                </div>
                <div class="form-group">
                    <label for="destination">Destination</label>
                    <input class="form-control" id="destination" name="destination" type="text" onkeypress="inputLocationValidation(event)" required/>
                </div>
                <input type="hidden" name="post_id" value="'. $post->ID .'" />
                <input type="hidden" name="action" value="drive_taxi" >
                <button name="button_9" class="btn btn-primary" type="submit"> Drive taxi </button>
            </form>
        </div>
    </figure>
    <script>
        function inputLocationValidation(evt) {
            var theEvent = evt;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var regex = /^[0-9., ]*$/;
            if( !regex.test(key) ) {
                alert("Input only for numbers, dots and a comma");
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    </script>';
    endwhile;
    wp_reset_postdata();
}

/**
 * Get simple radio buttons with color names.
 *
 * @param bool $required If buttons needs to being chosen.
 *
 * @return string The HTML code for displaying the buttons.
 */
function get_radio_buttons_with_colors($required)
{
    $text_required = $required ? 'required' : '';
    $terms = get_terms(array(
        'taxonomy' => 'Colors',
        'hide_empty' => false,
    ));
    $color_radio_buttons = '';
    foreach ($terms as $term) {
        $color_radio_buttons .= '<div class="form-check form-check-inline"><input class="form-check-input" type="radio" id="filter_color_'.$term->name.'" name="filter_color" value="'.$term->name.'" '. $text_required .'/><label class="form-check-label" for="filter_color_'.$term->name.'">'. $term->name .'</label></div>';
    }

    return $color_radio_buttons;
}

/**
 * Get simple radio buttons with color names and radio button for color selected, required to complete.
 *
 * @return string The HTML code for displaying the buttons.
 */
function get_radio_buttons_with_colors_and_color_selected_required()
{
    global $post;
    $terms = get_terms(array(
        'taxonomy' => 'Colors',
        'hide_empty' => false,
    ));
    $color_radio_buttons = '';
    $color_selected = strip_tags(get_the_term_list($post->ID, 'Colors'));
    foreach ($terms as $term) {
        if ($term->name == $color_selected) {
            $color_radio_buttons .= '<div class="form-check"><input type="radio" id="color_button_'.$term->name.$post->ID.'" class="form-check-input" name="color_button" value="'.$term->name.'" checked required/><label class="form-check-label" for="color_button_'.$term->name.$post->ID.'">'. $term->name .' </label></div>';
        } else {
            $color_radio_buttons .= '<div class="form-check"><input type="radio" id="color_button_'.$term->name.$post->ID.'" class="form-check-input" name="color_button" value="'.$term->name.'" required/><label class="form-check-label" for="color_button_'.$term->name.$post->ID.'">'. $term->name .' </label></div>';
        }
    }

    return $color_radio_buttons;
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/*
 * Load customize pro
 */
require_once(trailingslashit(get_template_directory()) . 'customize-pro/class-customize.php');
