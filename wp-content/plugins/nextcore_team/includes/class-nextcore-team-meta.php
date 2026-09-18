<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Meta {
    private $base_fields = array(
        '_nextcore_team_name_en'    => 'text',
        '_nextcore_team_role_title' => 'text',
        '_nextcore_team_role_title_en' => 'text',
        '_nextcore_team_email'      => 'email',
        '_nextcore_team_phone'      => 'phone',
    );

    public function __construct() {
        add_action( 'add_meta_boxes_nextcore_member', array( $this, 'add_meta_boxes' ), 99 );
        add_action( 'save_post_nextcore_member', array( $this, 'save' ) );
        add_action( 'init', array( $this, 'register_meta' ) );
        add_filter( 'wp_insert_post_data', array( $this, 'save_name_vi' ), 10, 2 );
    }

    public static function social_networks() {
        $settings = get_option( 'nextcore_team_settings', array() );
        $networks = isset( $settings['social_networks'] ) && is_array( $settings['social_networks'] ) ? $settings['social_networks'] : array();
        if ( ! $networks && class_exists( 'Nextcore_Team_Installer' ) ) {
            $networks = Nextcore_Team_Installer::default_social_networks();
        }
        $clean = array();
        foreach ( $networks as $network ) {
            $key = isset( $network['key'] ) ? sanitize_key( $network['key'] ) : '';
            $label = isset( $network['label'] ) ? sanitize_text_field( $network['label'] ) : '';
            if ( '' === $key || '' === $label ) {
                continue;
            }
            $clean[] = array(
                'key'   => $key,
                'label' => $label,
                'label_en' => isset( $network['label_en'] ) ? sanitize_text_field( $network['label_en'] ) : '',
                'icon'  => isset( $network['icon'] ) ? sanitize_key( $network['icon'] ) : $key,
            );
        }
        return $clean;
    }

    public static function social_meta_key( $key ) {
        return '_nextcore_team_social_' . sanitize_key( $key );
    }

    public function fields() {
        $fields = $this->base_fields;
        foreach ( self::social_networks() as $network ) {
            $fields[ self::social_meta_key( $network['key'] ) ] = 'url';
        }
        return $fields;
    }

    public function register_meta() {
        foreach ( $this->fields() as $key => $type ) {
            register_post_meta( 'nextcore_member', $key, array(
                'type'              => 'string',
                'single'            => true,
                'show_in_rest'      => true,
                'sanitize_callback' => array( $this, 'sanitize_registered_meta' ),
                'auth_callback'     => function() {
                    return current_user_can( 'edit_posts' );
                },
            ) );
        }
    }

    public function sanitize_registered_meta( $value, $meta_key ) {
        $type = isset( $this->fields()[ $meta_key ] ) ? $this->fields()[ $meta_key ] : 'text';
        return $this->sanitize_value( $value, $type );
    }

    public function add_meta_boxes() {
        remove_meta_box( 'postimagediv', 'nextcore_member', 'side' );
        remove_meta_box( 'pageparentdiv', 'nextcore_member', 'side' );
        add_meta_box(
            'nextcore-team-contact',
            __( 'Thông tin thành viên', 'nextcore-team' ),
            array( $this, 'render' ),
            'nextcore_member',
            'normal',
            'default'
        );
    }

    public function render( $post ) {
        wp_nonce_field( 'nextcore_team_save_meta', 'nextcore_team_meta_nonce' );
        wp_nonce_field( 'nextcore_team_save_section', 'nextcore_team_section_nonce' );
        $terms = get_terms( array(
            'taxonomy'   => 'nextcore_role',
            'hide_empty' => false,
            'orderby'    => 'meta_value_num',
            'meta_key'   => 'nextcore_section_order',
        ) );
        $selected = wp_get_object_terms( $post->ID, 'nextcore_role', array( 'fields' => 'ids' ) );
        $selected_id = ! is_wp_error( $selected ) && ! empty( $selected ) ? (int) $selected[0] : 0;
        $labels = array(
            '_nextcore_team_name_en'    => array( __( 'Tên hiển thị (EN)', 'nextcore-team' ), 'text', 'Member name' ),
            '_nextcore_team_role_title' => array( __( 'Chức danh (VI)', 'nextcore-team' ), 'text', 'Giám đốc điều hành' ),
            '_nextcore_team_role_title_en' => array( __( 'Chức danh (EN)', 'nextcore-team' ), 'text', 'Chief Executive Officer' ),
            '_nextcore_team_email'      => array( __( 'Email', 'nextcore-team' ), 'email', 'name@example.com' ),
            '_nextcore_team_phone'      => array( __( 'Điện thoại', 'nextcore-team' ), 'text', '+84 901 234 567' ),
        );

        echo '<table class="form-table"><tbody>';
        $name_vi = 'auto-draft' === $post->post_status ? '' : $post->post_title;
        echo '<tr><th><label for="nextcore-team-name-vi">' . esc_html__( 'Tên hiển thị (VI)', 'nextcore-team' ) . '</label></th><td>';
        echo '<input type="text" class="regular-text" id="nextcore-team-name-vi" name="nextcore_team_name_vi" value="' . esc_attr( $name_vi ) . '" required></td></tr>';
        foreach ( $labels as $key => $info ) {
            $value = get_post_meta( $post->ID, $key, true );
            echo '<tr><th><label for="' . esc_attr( $key ) . '">' . esc_html( $info[0] ) . '</label></th><td>';
            echo '<input type="' . esc_attr( $info[1] ) . '" class="regular-text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $info[2] ) . '">';
            echo '</td></tr>';
        }

        echo '<tr><th><label for="nextcore-team-section">' . esc_html__( 'Phần đội ngũ', 'nextcore-team' ) . '</label></th><td>';
        echo '<select id="nextcore-team-section" name="nextcore_team_section" required><option value="">' . esc_html__( 'Không chọn', 'nextcore-team' ) . '</option>';
        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( $selected_id, $term->term_id, false ) . '>' . esc_html( $term->name ) . '</option>';
            }
        }
        echo '</select></td></tr>';

        if ( current_user_can( 'upload_files' ) ) {
            echo '<tr><th>' . esc_html__( 'Ảnh đại diện', 'nextcore-team' ) . '</th><td><div id="postimagediv"><div class="inside">';
            post_thumbnail_meta_box( $post );
            echo '</div></div></td></tr>';
        }

        foreach ( self::social_networks() as $network ) {
            $key = self::social_meta_key( $network['key'] );
            $value = get_post_meta( $post->ID, $key, true );
            echo '<tr><th><label for="' . esc_attr( $key ) . '">' . esc_html( $network['label'] ) . ' URL</label></th><td>';
            echo '<input type="url" class="regular-text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_url( $value ) . '" placeholder="https://...">';
            echo '</td></tr>';
        }
        echo '</tbody></table>';
    }

    public function save_name_vi( $data, $postarr ) {
        if ( 'nextcore_member' !== $data['post_type'] || ! isset( $_POST['nextcore_team_name_vi'], $_POST['nextcore_team_meta_nonce'] ) ) {
            return $data;
        }
        $nonce = sanitize_text_field( wp_unslash( $_POST['nextcore_team_meta_nonce'] ) );
        if ( ! wp_verify_nonce( $nonce, 'nextcore_team_save_meta' ) ) {
            return $data;
        }
        $post_id = isset( $postarr['ID'] ) ? absint( $postarr['ID'] ) : 0;
        if ( $post_id ? ! current_user_can( 'edit_post', $post_id ) : ! current_user_can( 'edit_posts' ) ) {
            return $data;
        }
        $data['post_title'] = sanitize_text_field( wp_unslash( $_POST['nextcore_team_name_vi'] ) );
        return $data;
    }

    public function save( $post_id ) {
        if ( ! isset( $_POST['nextcore_team_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nextcore_team_meta_nonce'] ) ), 'nextcore_team_save_meta' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        foreach ( $this->fields() as $key => $type ) {
            if ( isset( $_POST[ $key ] ) ) {
                $value = $this->sanitize_value( wp_unslash( $_POST[ $key ] ), $type );
                if ( '' === $value ) {
                    delete_post_meta( $post_id, $key );
                } else {
                    update_post_meta( $post_id, $key, $value );
                }
            } else {
                delete_post_meta( $post_id, $key );
            }
        }
    }

    private function sanitize_value( $value, $type ) {
        if ( 'email' === $type ) {
            return sanitize_email( $value );
        }
        if ( 'url' === $type ) {
            return esc_url_raw( $value );
        }
        return sanitize_text_field( $value );
    }
}
