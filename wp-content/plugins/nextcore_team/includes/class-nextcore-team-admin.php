<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Admin {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'menu' ), 30 );
        add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
        add_action( 'wp_ajax_nextcore_team_save_order', array( $this, 'save_order' ) );
        add_filter( 'posts_orderby', array( $this, 'admin_orderby' ), 10, 2 );
    }

    public function menu() {
        add_submenu_page(
            'edit.php?post_type=nextcore_member',
            __( 'Phần & sắp xếp đội ngũ', 'nextcore-team' ),
            __( 'Phần & sắp xếp', 'nextcore-team' ),
            'edit_posts',
            'nextcore-team-order',
            array( $this, 'order_page' )
        );
    }

    public function assets( $hook ) {
        $screen = get_current_screen();
        if ( ! $screen || false === strpos( $screen->id, 'nextcore-team' ) ) {
            return;
        }
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script(
            'nextcore-team-admin-sort',
            NEXTCORE_TEAM_URL . 'admin/js/sortable.js',
            array( 'jquery', 'jquery-ui-sortable' ),
            NEXTCORE_TEAM_VERSION,
            true
        );
        wp_localize_script( 'nextcore-team-admin-sort', 'NextcoreTeamOrder', array(
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'nextcore_team_order' ),
            'saving'  => __( 'Đang lưu...', 'nextcore-team' ),
            'saved'   => __( 'Đã lưu thứ tự.', 'nextcore-team' ),
            'error'   => __( 'Không thể lưu. Vui lòng thử lại.', 'nextcore-team' ),
        ) );
        wp_enqueue_style(
            'nextcore-team-admin',
            NEXTCORE_TEAM_URL . 'admin/css/admin.css',
            array(),
            NEXTCORE_TEAM_VERSION
        );
    }

    public function order_page() {
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( esc_html__( 'Bạn không có quyền truy cập trang này.', 'nextcore-team' ) );
        }

        $this->handle_section_post();
        $sections = $this->sections();
        ?>
        <div class="wrap nextcore-team-admin-wrap">
            <h1><?php esc_html_e( 'Phần & sắp xếp đội ngũ', 'nextcore-team' ); ?></h1>
            <p><?php esc_html_e( 'Tạo các phần như Board of Management, Project Manager rồi kéo thả phần hoặc thành viên để đổi thứ tự hiển thị.', 'nextcore-team' ); ?></p>

            <form method="post" class="nextcore-team-section-form">
                <?php wp_nonce_field( 'nextcore_team_section_form', 'nextcore_team_section_nonce' ); ?>
                <input type="hidden" name="nextcore_team_section_action" value="add">
                <label>VI <input type="text" name="nextcore_team_section_name" class="regular-text" placeholder="<?php esc_attr_e( 'Tên phần mới', 'nextcore-team' ); ?>"></label>
                <label>EN <input type="text" name="nextcore_team_section_name_en" class="regular-text" placeholder="<?php esc_attr_e( 'Tên phần mới', 'nextcore-team' ); ?>"></label>
                <?php submit_button( __( 'Thêm phần', 'nextcore-team' ), 'secondary', 'submit', false ); ?>
            </form>

            <div id="nextcore-team-order-status" class="nextcore-team-order-status" aria-live="polite"></div>
            <div id="nextcore-team-sections" class="nextcore-team-sections">
                <?php foreach ( $sections as $section ) : ?>
                    <section class="nextcore-team-section-box" data-section-id="<?php echo esc_attr( $section->term_id ); ?>">
                        <header class="nextcore-team-section-head">
                            <span class="dashicons dashicons-menu nextcore-team-section-handle" aria-hidden="true"></span>
                            <form method="post" class="nextcore-team-section-edit">
                                <?php wp_nonce_field( 'nextcore_team_section_form', 'nextcore_team_section_nonce' ); ?>
                                <input type="hidden" name="nextcore_team_section_action" value="update">
                                <input type="hidden" name="nextcore_team_section_id" value="<?php echo esc_attr( $section->term_id ); ?>">
                                <label>VI <input type="text" name="nextcore_team_section_name" value="<?php echo esc_attr( $section->name ); ?>"></label>
                                <label>EN <input type="text" name="nextcore_team_section_name_en" value="<?php echo esc_attr( get_term_meta( $section->term_id, 'nextcore_team_name_en', true ) ); ?>"></label>
                                <?php submit_button( __( 'Lưu tên', 'nextcore-team' ), 'secondary small', 'submit', false ); ?>
                            </form>
                            <form method="post" class="nextcore-team-section-delete" onsubmit="return confirm('<?php echo esc_js( __( 'Xóa phần này? Thành viên sẽ không bị xóa.', 'nextcore-team' ) ); ?>');">
                                <?php wp_nonce_field( 'nextcore_team_section_form', 'nextcore_team_section_nonce' ); ?>
                                <input type="hidden" name="nextcore_team_section_action" value="delete">
                                <input type="hidden" name="nextcore_team_section_id" value="<?php echo esc_attr( $section->term_id ); ?>">
                                <?php submit_button( __( 'Xóa', 'nextcore-team' ), 'delete small', 'submit', false ); ?>
                            </form>
                        </header>
                        <ul class="nextcore-team-sortable" data-section-id="<?php echo esc_attr( $section->term_id ); ?>">
                            <?php foreach ( $this->members( $section->term_id ) as $member ) : ?>
                                <li data-id="<?php echo esc_attr( $member->ID ); ?>">
                                    <span class="dashicons dashicons-menu nextcore-team-handle" aria-hidden="true"></span>
                                    <span class="nextcore-team-order-photo"><?php echo get_the_post_thumbnail( $member->ID, array( 48, 48 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                                    <span class="nextcore-team-order-main">
                                        <strong><?php echo esc_html( get_the_title( $member ) ); ?></strong>
                                        <small><?php echo esc_html( get_post_meta( $member->ID, '_nextcore_team_role_title', true ) ); ?></small>
                                    </span>
                                    <a class="button button-small" href="<?php echo esc_url( get_edit_post_link( $member->ID ) ); ?>"><?php esc_html_e( 'Sửa', 'nextcore-team' ); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    private function handle_section_post() {
        if ( empty( $_POST['nextcore_team_section_action'] ) ) {
            return;
        }
        if ( ! isset( $_POST['nextcore_team_section_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nextcore_team_section_nonce'] ) ), 'nextcore_team_section_form' ) ) {
            return;
        }
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }

        $action = sanitize_key( wp_unslash( $_POST['nextcore_team_section_action'] ) );
        $name = isset( $_POST['nextcore_team_section_name'] ) ? sanitize_text_field( wp_unslash( $_POST['nextcore_team_section_name'] ) ) : '';
        $name_en = isset( $_POST['nextcore_team_section_name_en'] ) ? sanitize_text_field( wp_unslash( $_POST['nextcore_team_section_name_en'] ) ) : '';
        $term_id = isset( $_POST['nextcore_team_section_id'] ) ? absint( $_POST['nextcore_team_section_id'] ) : 0;

        if ( 'add' === $action && '' !== $name ) {
            $result = wp_insert_term( $name, 'nextcore_role' );
            if ( ! is_wp_error( $result ) && isset( $result['term_id'] ) ) {
                update_term_meta( $result['term_id'], 'nextcore_section_order', count( $this->sections() ) + 1 );
                if ( '' !== $name_en ) {
                    update_term_meta( $result['term_id'], 'nextcore_team_name_en', $name_en );
                }
            }
        }
        if ( 'update' === $action && $term_id && '' !== $name ) {
            $result = wp_update_term( $term_id, 'nextcore_role', array( 'name' => $name ) );
            if ( ! is_wp_error( $result ) ) {
                if ( '' !== $name_en ) {
                    update_term_meta( $term_id, 'nextcore_team_name_en', $name_en );
                } else {
                    delete_term_meta( $term_id, 'nextcore_team_name_en' );
                }
            }
        }
        if ( 'delete' === $action && $term_id ) {
            wp_delete_term( $term_id, 'nextcore_role' );
        }

    }

    private function sections() {
        $sections = get_terms( array(
            'taxonomy'   => 'nextcore_role',
            'hide_empty' => false,
            'orderby'    => 'meta_value_num',
            'meta_key'   => 'nextcore_section_order',
        ) );
        return is_wp_error( $sections ) ? array() : $sections;
    }

    private function members( $term_id ) {
        return get_posts( array(
            'post_type'      => 'nextcore_member',
            'post_status'    => array( 'publish', 'draft', 'pending', 'private' ),
            'posts_per_page' => -1,
            'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'nextcore_role',
                    'field'    => 'term_id',
                    'terms'    => array( absint( $term_id ) ),
                ),
            ),
        ) );
    }

    public function save_order() {
        check_ajax_referer( 'nextcore_team_order', 'nonce' );
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( array( 'message' => __( 'Không đủ quyền.', 'nextcore-team' ) ), 403 );
        }

        $sections = isset( $_POST['sections'] ) && is_array( $_POST['sections'] ) ? array_map( 'absint', wp_unslash( $_POST['sections'] ) ) : array();
        foreach ( $sections as $index => $term_id ) {
            update_term_meta( $term_id, 'nextcore_section_order', $index );
        }

        $orders = isset( $_POST['order'] ) && is_array( $_POST['order'] ) ? wp_unslash( $_POST['order'] ) : array();
        foreach ( $orders as $term_id => $post_ids ) {
            $term_id = absint( $term_id );
            if ( ! is_array( $post_ids ) ) {
                continue;
            }
            foreach ( array_map( 'absint', $post_ids ) as $index => $post_id ) {
                if ( 'nextcore_member' !== get_post_type( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
                    continue;
                }
                wp_update_post( array(
                    'ID'         => $post_id,
                    'menu_order' => $index,
                ) );
                if ( $term_id ) {
                    wp_set_object_terms( $post_id, array( $term_id ), 'nextcore_role', false );
                }
            }
        }

        wp_send_json_success();
    }

    public function admin_orderby( $orderby, $query ) {
        if ( ! is_admin() || ! $query->is_main_query() ) {
            return $orderby;
        }
        if ( 'nextcore_member' !== $query->get( 'post_type' ) ) {
            return $orderby;
        }
        if ( $query->get( 'orderby' ) ) {
            return $orderby;
        }
        global $wpdb;
        return "{$wpdb->posts}.menu_order ASC, {$wpdb->posts}.post_title ASC";
    }
}
