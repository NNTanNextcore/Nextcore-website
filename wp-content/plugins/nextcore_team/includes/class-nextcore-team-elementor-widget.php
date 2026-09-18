<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Elementor_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'nextcore-team-list';
    }

    public function get_title() {
        return __( 'Danh sách đội ngũ', 'nextcore-team' );
    }

    public function get_icon() {
        return 'eicon-person';
    }

    public function get_categories() {
        return array( 'nextcore-team' );
    }

    public function get_keywords() {
        return array( 'nextcore', 'team', 'đội ngũ', 'members' );
    }

    public function is_reload_preview_required() {
        return true;
    }

    protected function register_controls() {
        $this->start_controls_section( 'nextcore_team_content', array(
            'label' => __( 'Danh sách đội ngũ', 'nextcore-team' ),
        ) );

        $this->add_control( 'section', array(
            'label'   => __( 'Phần đội ngũ', 'nextcore-team' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => Nextcore_Team_Builders::sections(),
            'default' => '',
        ) );

        $this->add_control( 'columns', array(
            'label'   => __( 'Số cột', 'nextcore-team' ),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'min'     => 1,
            'max'     => 6,
            'default' => 3,
        ) );

        $this->add_control( 'show_section_heading', array(
            'label'        => __( 'Tên từng phần', 'nextcore-team' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __( 'Hiện', 'nextcore-team' ),
            'label_off'    => __( 'Ẩn', 'nextcore-team' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ) );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        echo Nextcore_Team_Renderer::render( array(
            'section'              => isset( $settings['section'] ) ? $settings['section'] : '',
            'columns'              => isset( $settings['columns'] ) ? $settings['columns'] : 3,
            'show_section_heading' => ! empty( $settings['show_section_heading'] ),
        ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}
