<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Post_Type {
    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
        add_filter( 'manage_nextcore_member_posts_columns', array( $this, 'columns' ) );
        add_action( 'manage_nextcore_member_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
        add_action( 'save_post_nextcore_member', array( $this, 'save_single_section' ) );
    }

    public function register() {
        $labels = array(
            'name'                  => __( 'Đội ngũ', 'nextcore-team' ),
            'singular_name'         => __( 'Thành viên', 'nextcore-team' ),
            'menu_name'             => __( 'Đội ngũ', 'nextcore-team' ),
            'name_admin_bar'        => __( 'Thành viên', 'nextcore-team' ),
            'add_new'               => __( 'Thêm thành viên', 'nextcore-team' ),
            'add_new_item'          => __( 'Thêm thành viên', 'nextcore-team' ),
            'new_item'              => __( 'Thành viên mới', 'nextcore-team' ),
            'edit_item'             => __( 'Sửa thành viên', 'nextcore-team' ),
            'view_item'             => __( 'Xem thành viên', 'nextcore-team' ),
            'all_items'             => __( 'Tất cả thành viên', 'nextcore-team' ),
            'search_items'          => __( 'Tìm thành viên', 'nextcore-team' ),
            'not_found'             => __( 'Không tìm thấy thành viên.', 'nextcore-team' ),
            'not_found_in_trash'    => __( 'Không có thành viên trong thùng rác.', 'nextcore-team' ),
            'featured_image'        => __( 'Ảnh thành viên', 'nextcore-team' ),
            'set_featured_image'    => __( 'Chọn ảnh thành viên', 'nextcore-team' ),
            'remove_featured_image' => __( 'Xóa ảnh', 'nextcore-team' ),
        );

        register_post_type( 'nextcore_member', array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'menu_icon'          => 'dashicons-groups',
            'menu_position'      => 25,
            'supports'           => array( 'thumbnail' ),
            'has_archive'        => false,
            'rewrite'            => false,
            'query_var'          => false,
            'can_export'         => true,
            'delete_with_user'   => false,
            'taxonomies'         => array( 'nextcore_role' ),
        ) );

        $section_labels = array(
            'name'          => __( 'Nextcore Team', 'nextcore-team' ),
            'singular_name' => __( 'Phần đội ngũ', 'nextcore-team' ),
            'search_items'  => __( 'Tìm phần', 'nextcore-team' ),
            'all_items'     => __( 'Tất cả phần', 'nextcore-team' ),
            'edit_item'     => __( 'Sửa phần', 'nextcore-team' ),
            'update_item'   => __( 'Cập nhật phần', 'nextcore-team' ),
            'add_new_item'  => __( 'Thêm phần', 'nextcore-team' ),
            'new_item_name' => __( 'Tên phần', 'nextcore-team' ),
            'menu_name'     => __( 'Phần đội ngũ', 'nextcore-team' ),
        );

        register_taxonomy( 'nextcore_role', array( 'nextcore_member' ), array(
            'labels'            => $section_labels,
            'public'            => false,
            'show_ui'           => true,
            'show_in_menu'      => false,
            'show_in_nav_menus' => false,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'hierarchical'      => false,
            'rewrite'           => false,
            'query_var'         => false,
            'meta_box_cb'       => false,
        ) );
    }

    public function save_single_section( $post_id ) {
        if ( ! isset( $_POST['nextcore_team_section_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nextcore_team_section_nonce'] ) ), 'nextcore_team_save_section' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        $term_id = isset( $_POST['nextcore_team_section'] ) ? absint( $_POST['nextcore_team_section'] ) : 0;
        if ( $term_id && term_exists( $term_id, 'nextcore_role' ) ) {
            wp_set_object_terms( $post_id, array( $term_id ), 'nextcore_role', false );
        } else {
            wp_set_object_terms( $post_id, array(), 'nextcore_role', false );
        }
    }

    public function columns( $columns ) {
        $new = array();
        foreach ( $columns as $key => $label ) {
            $new[ $key ] = $label;
            if ( 'cb' === $key ) {
                $new['nextcore_photo'] = __( 'Ảnh', 'nextcore-team' );
            }
            if ( 'title' === $key ) {
                $new['nextcore_contact'] = __( 'Liên hệ', 'nextcore-team' );
            }
        }
        return $new;
    }

    public function column_content( $column, $post_id ) {
        if ( 'nextcore_photo' === $column ) {
            echo get_the_post_thumbnail( $post_id, array( 56, 56 ), array( 'style' => 'width:56px;height:56px;object-fit:cover;border-radius:8px;' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        if ( 'nextcore_contact' === $column ) {
            $email = get_post_meta( $post_id, '_nextcore_team_email', true );
            $phone = get_post_meta( $post_id, '_nextcore_team_phone', true );
            if ( $email ) {
                echo '<div>' . esc_html( $email ) . '</div>';
            }
            if ( $phone ) {
                echo '<div>' . esc_html( $phone ) . '</div>';
            }
        }
    }
}
