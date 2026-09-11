<?php
// Add custom Theme Functions here

function breadcrumb_page() {
    return flatsome_breadcrumb();
}

add_shortcode('breadcrumb_page', 'breadcrumb_page');

function wpb_adding_scripts() {
    wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/../flatsome-child/assets/css/magnific-popup.css', array(),'1.1' );
    // wp_enqueue_style( 'slick', get_template_directory_uri() . '/../flatsome-child/assets/css/slick.css', array(),'1.1' );
    wp_enqueue_style( 'frontend', get_template_directory_uri() . '/../flatsome-child/assets/css/frontend.min.css', array(),'1.1' );
    wp_enqueue_style( 'custom', get_template_directory_uri() . '/../flatsome-child/assets/css/custom.css', array(),'1.1' );
    wp_enqueue_script('match-height-min', get_template_directory_uri() . '/../flatsome-child/assets/js/match-height-min.js', array(),'1.1', true);
    wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/../flatsome-child/assets/js/magnific-popup.js', array(),'1.1', true);
    wp_enqueue_script('custom', get_template_directory_uri() . '/../flatsome-child/assets/js/custom.js', array(),'1.1', true);

    if (is_single()) { 
        wp_enqueue_style( 'lightgallery', get_template_directory_uri() . '/../flatsome-child/assets/css/lightgallery.min.css', array(),'1.1' );
        wp_enqueue_style( 'lg-thumbnail', get_template_directory_uri() . '/../flatsome-child/assets/css/lg-thumbnail.min.css', array(),'1.1' );
        wp_enqueue_style( 'lg-zoom', get_template_directory_uri() . '/../flatsome-child/assets/css/lg-zoom.min.css', array(),'1.1' );
        wp_enqueue_script('lightgallery', get_template_directory_uri() . '/../flatsome-child/assets/js/lightgallery.min.js', array(),'1.1', true);
        wp_enqueue_script('lg-zoom', get_template_directory_uri() . '/../flatsome-child/assets/js/lg-zoom.min.js', array(),'1.1', true);
        wp_enqueue_script('lg-thumbnail', get_template_directory_uri() . '/../flatsome-child/assets/js/lg-thumbnail.min.js', array(),'1.1', true);
        wp_enqueue_script('lg-autoplay', get_template_directory_uri() . '/../flatsome-child/assets/js/lg-autoplay.min.js', array(),'1.1', true);
        wp_enqueue_script('slider-custom', get_template_directory_uri() . '/../flatsome-child/assets/js/slider-custom.js', array(),'1.1', true);
        wp_localize_script('slider-custom', 'ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php')
        ));
    }
    // wp_enqueue_script('slick', get_template_directory_uri() . '/../flatsome-child/assets/js/slick.js', array(),'1.1', true);
    // wp_enqueue_script('cms-post-carousel-widget', get_template_directory_uri() . '/../flatsome-child/assets/js/cms-post-carousel-widget.js', array(),'1.1', true);
} 

add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts', 999 );

if ( !class_exists('get_first_text_title') ) {

    function get_first_text_title($title, $first = null) {

        if (!$title) {
            return '';
        }
        if ($first) {
            return mb_substr($title, 0, 1);
        } else {
            return substr($title, 1);
        }
    }
}

if (!function_exists('get_image_list')) {
    function get_image_list($images) {
        $arrayImage = [];
        foreach ($images as $key => $image) {
            $arrayImage[$key]["src"] = esc_url($image["url"]);
            $arrayImage[$key]["responsive"] = esc_url($image['sizes']['medium_large']);
            $arrayImage[$key]["thumb"] = esc_url($image['sizes']['thumbnail']);
        }

        set_transient('post_image_data', $arrayImage, 12 * HOUR_IN_SECONDS);
    }
}

add_action('wp_ajax_get_image_detai_post', 'get_image_detai_post');
add_action('wp_ajax_nopriv_get_image_detai_post', 'get_image_detai_post');
function get_image_detai_post() {
    $post_image = get_transient('post_image_data');
    if ($post_image) {
        wp_send_json_success($post_image);
    } else {
        wp_send_json_error('Không có dữ liệu hình ảnh.');
    }

}

function add_custom_css_to_head() {
    if (is_single()) {
        $custom_css = get_field('custom_css');
        if ($custom_css) {
            echo "<style>{$custom_css}</style>";
        }
    }
}
add_action('wp_head', 'add_custom_css_to_head');




