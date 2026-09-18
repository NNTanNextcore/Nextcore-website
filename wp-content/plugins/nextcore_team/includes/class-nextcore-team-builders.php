<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Builders {
    public function __construct() {
        add_action( 'elementor/elements/categories_registered', array( $this, 'elementor_category' ) );
        add_action( 'elementor/widgets/register', array( $this, 'elementor_widget' ) );
        add_action( 'ux_builder_setup', array( $this, 'flatsome_element' ) );
    }

    public static function sections() {
        $options = array( '' => __( 'Tất cả phần', 'nextcore-team' ) );
        $terms = get_terms( array(
            'taxonomy'   => 'nextcore_role',
            'hide_empty' => false,
            'orderby'    => 'meta_value_num',
            'meta_key'   => 'nextcore_section_order',
        ) );
        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $options[ $term->slug ] = $term->name;
            }
        }
        return $options;
    }

    public function elementor_category( $manager ) {
        $manager->add_category( 'nextcore-team', array(
            'title' => __( 'Nextcore Team', 'nextcore-team' ),
            'icon'  => 'fa fa-users',
        ) );
    }

    public function elementor_widget( $manager ) {
        if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
            return;
        }
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-elementor-widget.php';
        $manager->register( new Nextcore_Team_Elementor_Widget() );
    }

    public function flatsome_element() {
        if ( ! function_exists( 'add_ux_builder_shortcode' ) ) {
            return;
        }

        add_ux_builder_shortcode( 'nextcore_team', array(
            'name'     => __( 'Danh sách đội ngũ', 'nextcore-team' ),
            'category' => __( 'Nextcore Team', 'nextcore-team' ),
            'options'  => array(
                'section' => array(
                    'type'    => 'select',
                    'heading' => __( 'Phần đội ngũ', 'nextcore-team' ),
                    'default' => '',
                    'options' => self::sections(),
                ),
                'columns' => array(
                    'type'    => 'slider',
                    'heading' => __( 'Số cột', 'nextcore-team' ),
                    'default' => 3,
                    'min'     => 1,
                    'max'     => 6,
                ),
                'show_section_heading' => array(
                    'type'    => 'radio-buttons',
                    'heading' => __( 'Tên từng phần', 'nextcore-team' ),
                    'default' => '1',
                    'options' => array(
                        '1' => array( 'title' => __( 'Hiện', 'nextcore-team' ) ),
                        '0' => array( 'title' => __( 'Ẩn', 'nextcore-team' ) ),
                    ),
                ),
            ),
        ) );
    }
}
