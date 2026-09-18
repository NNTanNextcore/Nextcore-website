<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Renderer {
    public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
    }

    public function register_assets() {
        wp_register_style(
            'nextcore-team',
            NEXTCORE_TEAM_URL . 'public/css/team.css',
            array(),
            NEXTCORE_TEAM_VERSION
        );
    }

    public static function defaults() {
        $settings = wp_parse_args( get_option( 'nextcore_team_settings', array() ), array(
            'columns'              => 3,
            'tablet_columns'       => 2,
            'mobile_columns'       => 1,
            'gap'                  => 28,
            'radius'               => 12,
            'image_ratio'          => '1-1',
            'show_role'            => 1,
            'show_contact'         => 1,
            'show_section_heading' => 1,
            'custom_css'           => '',
            'social_networks'      => class_exists( 'Nextcore_Team_Installer' ) ? Nextcore_Team_Installer::default_social_networks() : array(),
        ) );
        return $settings;
    }

    public static function render( $atts = array() ) {
        $defaults = self::defaults();
        $atts = shortcode_atts( array(
            'columns'              => $defaults['columns'],
            'tablet_columns'       => $defaults['tablet_columns'],
            'mobile_columns'       => $defaults['mobile_columns'],
            'gap'                  => $defaults['gap'],
            'radius'               => $defaults['radius'],
            'image_ratio'          => $defaults['image_ratio'],
            'show_role'            => $defaults['show_role'],
            'show_contact'         => $defaults['show_contact'],
            'show_section_heading' => $defaults['show_section_heading'],
            'section'              => '',
            'role'                 => '',
            'limit'                => -1,
            'ids'                  => '',
            'class'                => '',
        ), $atts, 'nextcore_team' );

        $columns = min( 6, max( 1, absint( $atts['columns'] ) ) );
        $tablet_columns = min( 4, max( 1, absint( $atts['tablet_columns'] ) ) );
        $mobile_columns = min( 2, max( 1, absint( $atts['mobile_columns'] ) ) );
        $gap = min( 100, max( 0, absint( $atts['gap'] ) ) );
        $radius = min( 100, max( 0, absint( $atts['radius'] ) ) );
        $limit = intval( $atts['limit'] );
        if ( 0 === $limit ) {
            $limit = -1;
        }
        $allowed_ratios = array( '1-1', '4-5', '3-4', '16-9' );
        $image_ratio = in_array( $atts['image_ratio'], $allowed_ratios, true ) ? $atts['image_ratio'] : '1-1';
        $section_filter = ! empty( $atts['section'] ) ? $atts['section'] : $atts['role'];
        $section_slugs = array_filter( array_map( 'sanitize_title', array_map( 'trim', explode( ',', $section_filter ) ) ) );

        $sections = self::sections( $section_slugs );
        if ( ! $sections ) {
            return '<div class="nextcore-team-empty">' . esc_html__( 'Chưa có phần đội ngũ nào.', 'nextcore-team' ) . '</div>';
        }

        wp_enqueue_style( 'nextcore-team' );
        if ( ! empty( $defaults['custom_css'] ) ) {
            wp_add_inline_style( 'nextcore-team', $defaults['custom_css'] );
        }

        $custom_class = sanitize_html_class( $atts['class'] );
        $style = sprintf(
            '--nct-columns:%d;--nct-tablet-columns:%d;--nct-mobile-columns:%d;--nct-gap:%dpx;--nct-radius:%dpx;',
            $columns,
            $tablet_columns,
            $mobile_columns,
            $gap,
            $radius
        );

        ob_start();
        echo '<div class="nextcore-team-shell ' . esc_attr( $custom_class ) . '" style="' . esc_attr( $style ) . '">';
        foreach ( $sections as $section ) {
            $members = self::members( $section->term_id, $limit, $atts['ids'] );
            if ( ! $members ) {
                continue;
            }

            echo '<section class="nextcore-team-section" id="team-section-' . esc_attr( $section->slug ) . '">';
            if ( filter_var( $atts['show_section_heading'], FILTER_VALIDATE_BOOLEAN ) ) {
                echo '<h2 class="nextcore-team-section-title" data-no-translation>' . esc_html( self::section_name( $section ) ) . '</h2>';
            }
            echo '<div class="nextcore-team-grid">';
            foreach ( $members as $member ) {
                $post_id = $member->ID;
                $role_name = self::member_role( $post_id );
                $contacts = self::contacts( $post_id );
                $template = self::locate_template( 'team-card.php' );
                include $template;
            }
            echo '</div></section>';
        }

        echo '</div>';
        return ob_get_clean();
    }

    private static function sections( $slugs = array() ) {
        $args = array(
            'taxonomy'   => 'nextcore_role',
            'hide_empty' => false,
            'orderby'    => 'meta_value_num',
            'meta_key'   => 'nextcore_section_order',
        );
        if ( $slugs ) {
            $args['slug'] = $slugs;
        }
        $sections = get_terms( $args );
        return is_wp_error( $sections ) ? array() : $sections;
    }

    private static function members( $term_id, $limit, $ids ) {
        $query_args = array(
            'post_type'      => 'nextcore_member',
            'post_status'    => 'publish',
            'posts_per_page' => $limit,
            'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
            'no_found_rows'  => true,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'nextcore_role',
                    'field'    => 'term_id',
                    'terms'    => array( absint( $term_id ) ),
                ),
            ),
        );

        if ( ! empty( $ids ) ) {
            $post_ids = array_filter( array_map( 'absint', explode( ',', $ids ) ) );
            if ( $post_ids ) {
                $query_args['post__in'] = $post_ids;
            }
        }

        return get_posts( $query_args );
    }

    public static function is_english() {
        global $TRP_LANGUAGE;
        $language = ! empty( $TRP_LANGUAGE ) ? $TRP_LANGUAGE : get_locale();
        return 0 === strpos( strtolower( $language ), 'en' );
    }

    public static function section_name( $section ) {
        $english = self::is_english() ? get_term_meta( $section->term_id, 'nextcore_team_name_en', true ) : '';
        return '' !== $english ? $english : $section->name;
    }

    public static function member_role( $post_id ) {
        $english = self::is_english() ? get_post_meta( $post_id, '_nextcore_team_role_title_en', true ) : '';
        return '' !== $english ? $english : get_post_meta( $post_id, '_nextcore_team_role_title', true );
    }

    public static function member_name( $post_id ) {
        $english = self::is_english() ? get_post_meta( $post_id, '_nextcore_team_name_en', true ) : '';
        return '' !== $english ? $english : get_the_title( $post_id );
    }

    private static function contacts( $post_id ) {
        $contacts = array(
            'email' => get_post_meta( $post_id, '_nextcore_team_email', true ),
            'phone' => get_post_meta( $post_id, '_nextcore_team_phone', true ),
        );

        foreach ( Nextcore_Team_Meta::social_networks() as $network ) {
            $key = $network['key'];
            $value = get_post_meta( $post_id, Nextcore_Team_Meta::social_meta_key( $key ), true );
            if ( '' === $value ) {
                $value = get_post_meta( $post_id, '_nextcore_team_' . $key, true );
            }
            $contacts[ $key ] = $value;
        }

        return $contacts;
    }

    public static function locate_template( $template_name ) {
        $paths = array(
            trailingslashit( get_stylesheet_directory() ) . 'nextcore-team/' . $template_name,
            trailingslashit( get_stylesheet_directory() ) . 'nextcore_team/' . $template_name,
            NEXTCORE_TEAM_DIR . 'templates/' . $template_name,
        );
        foreach ( $paths as $path ) {
            if ( file_exists( $path ) ) {
                return $path;
            }
        }
        return NEXTCORE_TEAM_DIR . 'templates/team-card.php';
    }

    public static function icon( $name ) {
        $icons = array(
            'email' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 6 9 7 9-7"/></svg>',
            'phone' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3 4 4c-2 1-1 6 3 10s9 7 11 6l2-3-5-3-2 2c-3-1-5-3-6-6l2-2Z"/></svg>',
            'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 8h3V4h-3c-3 0-5 2-5 5v3H6v4h3v8h4v-8h3.5l.5-4h-4V9c0-.7.3-1 1-1Z"/></svg>',
            'linkedin' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.5 8.5H3V21h3.5V8.5ZM4.8 3A2.1 2.1 0 1 0 4.8 7.2 2.1 2.1 0 0 0 4.8 3ZM21 14c0-3.8-2-5.8-4.8-5.8-2.2 0-3.2 1.2-3.8 2v-1.7H9V21h3.5v-6.2c0-1.6.3-3.2 2.3-3.2s2 1.9 2 3.3V21H21v-7Z"/></svg>',
            'zalo' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 3h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-8l-4.5 2V19H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Zm2.2 5v2h4.2l-4.5 5v1h6v-2H8.7l4.3-5V8H6.2Zm8 0-2.2 8h2.2l.4-1.6h2.6l.4 1.6h2.2L17.6 8h-3.4Zm1.7 2.1.8 2.5h-1.6l.8-2.5Z"/></svg>',
            'website' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20Zm6.9 6h-3a15.7 15.7 0 0 0-1.4-3A8.1 8.1 0 0 1 19 8Zm-6.9-4c.9 1.1 1.6 2.4 2 4h-4c.4-1.6 1.1-2.9 2-4ZM4.3 14a8 8 0 0 1 0-4h3.4a16.7 16.7 0 0 0 0 4H4.3Zm.8 2h3a15.7 15.7 0 0 0 1.4 3A8.1 8.1 0 0 1 5.1 16ZM8.1 8h-3a8.1 8.1 0 0 1 4.4-3 15.7 15.7 0 0 0-1.4 3Zm3.9 12c-.9-1.1-1.6-2.4-2-4h4c-.4 1.6-1.1 2.9-2 4Zm2.5-6h-5a14.7 14.7 0 0 1 0-4h5a14.7 14.7 0 0 1 0 4Zm0 5a15.7 15.7 0 0 0 1.4-3h3a8.1 8.1 0 0 1-4.4 3Zm1.8-5a16.7 16.7 0 0 0 0-4h3.4a8 8 0 0 1 0 4h-3.4Z"/></svg>',
            'link' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1.5 1.5 2 2L15 5a2.2 2.2 0 0 1 3 3l-3 3a2.2 2.2 0 0 1-3 0l-2 2Zm4-2a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1.5-1.5-2-2L9 19a2.2 2.2 0 0 1-3-3l3-3a2.2 2.2 0 0 1 3 0l2-2Z"/></svg>',
        );
        return isset( $icons[ $name ] ) ? $icons[ $name ] : $icons['link'];
    }
}
