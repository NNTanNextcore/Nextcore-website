<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Installer {
    public static function activate() {
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-post-type.php';
        $post_type = new Nextcore_Team_Post_Type();
        $post_type->register();

        self::seed_settings();
        self::seed_sections_and_members();
        self::seed_translations();
        flush_rewrite_rules();
    }

    public static function seed_settings() {
        $settings = get_option( 'nextcore_team_settings', array() );
        $settings = wp_parse_args( is_array( $settings ) ? $settings : array(), array(
            'columns'              => 3,
            'tablet_columns'       => 2,
            'mobile_columns'       => 1,
            'gap'                  => 28,
            'radius'               => 12,
            'image_ratio'          => '1-1',
            'show_role'            => 1,
            'show_contact'         => 1,
            'show_section_heading' => 1,
            'custom_css'           => self::default_css(),
            'social_networks'      => self::default_social_networks(),
        ) );
        if ( ! get_option( 'nextcore_team_default_css_seeded' ) ) {
            if ( empty( $settings['custom_css'] ) ) {
                $settings['custom_css'] = self::default_css();
            }
            update_option( 'nextcore_team_default_css_seeded', 1 );
        }
        unset( $settings['page_title'], $settings['page_title_en'], $settings['page_description'], $settings['page_description_en'] );
        update_option( 'nextcore_team_settings', $settings );
    }

    public static function default_css() {
        $path = NEXTCORE_TEAM_DIR . 'includes/default-team.css';
        return is_readable( $path ) ? file_get_contents( $path ) : '';
    }

    public static function default_social_networks() {
        return array(
            array( 'key' => 'facebook', 'label' => 'Facebook', 'icon' => 'facebook' ),
            array( 'key' => 'linkedin', 'label' => 'LinkedIn', 'icon' => 'linkedin' ),
            array( 'key' => 'zalo', 'label' => 'Zalo', 'icon' => 'zalo' ),
            array( 'key' => 'website', 'label' => 'Website', 'icon' => 'website' ),
        );
    }

    public static function seed_sections_and_members() {
        if ( get_option( 'nextcore_team_seeded' ) ) {
            return;
        }

        $sections = array(
            'board-of-management' => 'Board of Management',
            'project-manager'     => 'Project Manager',
        );

        foreach ( $sections as $index => $name ) {
            $slug = sanitize_title( $index );
            if ( ! term_exists( $slug, 'nextcore_role' ) ) {
                $result = wp_insert_term( $name, 'nextcore_role', array( 'slug' => $slug ) );
                if ( ! is_wp_error( $result ) && isset( $result['term_id'] ) ) {
                    update_term_meta( $result['term_id'], 'nextcore_section_order', count( get_terms( array( 'taxonomy' => 'nextcore_role', 'hide_empty' => false ) ) ) );
                }
            }
        }

        $members = array(
            array(
                'name'    => 'Nguyễn Văn Hiền',
                'section' => 'board-of-management',
                'role'    => 'Chief Executive Officer',
                'email'   => 'hiennv@nextcore.vn',
                'phone'   => '0378962625',
                'image'   => 'NguyenVanHien.jpg',
            ),
            array(
                'name'    => 'Trần Đức Anh',
                'section' => 'board-of-management',
                'role'    => 'Chief Technology Officer',
                'email'   => 'anhtd@nextcore.vn',
                'phone'   => '0976748059',
                'image'   => 'TranDucAnh.jpg',
            ),
            array(
                'name'    => 'Phan Thanh Tú',
                'section' => 'project-manager',
                'role'    => 'Project Manager',
                'email'   => 'tupt@nextcore.vn',
                'phone'   => '0979525694',
                'image'   => 'PhanThanhTu.png',
            ),
        );

        foreach ( $members as $index => $member ) {
            $existing = get_page_by_title( $member['name'], OBJECT, 'nextcore_member' );
            $post_id = $existing ? (int) $existing->ID : wp_insert_post( array(
                'post_type'   => 'nextcore_member',
                'post_status' => 'publish',
                'post_title'  => $member['name'],
                'menu_order'  => $index,
            ) );

            if ( ! $post_id || is_wp_error( $post_id ) ) {
                continue;
            }

            update_post_meta( $post_id, '_nextcore_team_role_title', $member['role'] );
            update_post_meta( $post_id, '_nextcore_team_email', $member['email'] );
            update_post_meta( $post_id, '_nextcore_team_phone', $member['phone'] );
            wp_set_object_terms( $post_id, $member['section'], 'nextcore_role', false );

            $attachment_id = self::find_attachment_by_filename( $member['image'] );
            if ( $attachment_id ) {
                set_post_thumbnail( $post_id, $attachment_id );
            }
        }

        update_option( 'nextcore_team_seeded', 1 );
    }

    public static function find_attachment_by_filename( $filename ) {
        global $wpdb;
        $like = '%' . $wpdb->esc_like( '/' . $filename );
        $id = $wpdb->get_var( $wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s LIMIT 1",
            $like
        ) );
        return $id ? absint( $id ) : 0;
    }

    public static function seed_translations() {
        if ( get_option( 'nextcore_team_translations_seeded' ) ) {
            return;
        }

        $sections = get_terms( array( 'taxonomy' => 'nextcore_role', 'hide_empty' => false ) );
        if ( ! is_wp_error( $sections ) ) {
            foreach ( $sections as $section ) {
                if ( '' === get_term_meta( $section->term_id, 'nextcore_team_name_en', true ) ) {
                    update_term_meta( $section->term_id, 'nextcore_team_name_en', $section->name );
                }
            }
        }

        $members = get_posts( array( 'post_type' => 'nextcore_member', 'post_status' => 'any', 'numberposts' => -1 ) );
        foreach ( $members as $member ) {
            $role = get_post_meta( $member->ID, '_nextcore_team_role_title', true );
            if ( '' !== $role && '' === get_post_meta( $member->ID, '_nextcore_team_role_title_en', true ) ) {
                update_post_meta( $member->ID, '_nextcore_team_role_title_en', $role );
            }
        }

        update_option( 'nextcore_team_translations_seeded', 1 );
    }

}
