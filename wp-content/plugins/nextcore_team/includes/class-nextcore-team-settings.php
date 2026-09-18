<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Settings {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'menu' ), 35 );
        add_action( 'admin_init', array( $this, 'register' ) );
    }

    public function menu() {
        add_submenu_page(
            'edit.php?post_type=nextcore_member',
            __( 'Cài đặt Đội ngũ', 'nextcore-team' ),
            __( 'Cài đặt', 'nextcore-team' ),
            'manage_options',
            'nextcore-team-settings',
            array( $this, 'page' )
        );
    }

    public function register() {
        register_setting( 'nextcore_team_settings_group', 'nextcore_team_settings', array(
            'type'              => 'array',
            'sanitize_callback' => array( $this, 'sanitize' ),
            'default'           => array(),
        ) );
    }

    public function sanitize( $input ) {
        $output = array();
        $output['columns'] = min( 6, max( 1, absint( isset( $input['columns'] ) ? $input['columns'] : 3 ) ) );
        $output['tablet_columns'] = min( 4, max( 1, absint( isset( $input['tablet_columns'] ) ? $input['tablet_columns'] : 2 ) ) );
        $output['mobile_columns'] = min( 2, max( 1, absint( isset( $input['mobile_columns'] ) ? $input['mobile_columns'] : 1 ) ) );
        $current = get_option( 'nextcore_team_settings', array() );
        $output['gap'] = isset( $current['gap'] ) ? min( 100, max( 0, absint( $current['gap'] ) ) ) : 28;
        $output['radius'] = isset( $current['radius'] ) ? min( 100, max( 0, absint( $current['radius'] ) ) ) : 12;
        $allowed = array( '1-1', '4-5', '3-4', '16-9' );
        $ratio = isset( $input['image_ratio'] ) ? sanitize_text_field( $input['image_ratio'] ) : '1-1';
        $output['image_ratio'] = in_array( $ratio, $allowed, true ) ? $ratio : '1-1';
        $output['show_role'] = ! empty( $input['show_role'] ) ? 1 : 0;
        $output['show_contact'] = ! empty( $input['show_contact'] ) ? 1 : 0;
        $output['show_section_heading'] = ! empty( $input['show_section_heading'] ) ? 1 : 0;
        $output['custom_css'] = isset( $input['custom_css'] ) ? wp_strip_all_tags( $input['custom_css'] ) : '';
        $output['social_networks'] = array();

        if ( isset( $input['social_networks'] ) && is_array( $input['social_networks'] ) ) {
            foreach ( $input['social_networks'] as $network ) {
                $key = isset( $network['key'] ) ? sanitize_key( $network['key'] ) : '';
                $label = isset( $network['label'] ) ? sanitize_text_field( $network['label'] ) : '';
                $label_en = isset( $network['label_en'] ) ? sanitize_text_field( $network['label_en'] ) : '';
                $icon = isset( $network['icon'] ) ? sanitize_key( $network['icon'] ) : $key;
                if ( '' === $key || '' === $label ) {
                    continue;
                }
                $output['social_networks'][] = array( 'key' => $key, 'label' => $label, 'label_en' => $label_en, 'icon' => $icon );
            }
        }

        if ( ! $output['social_networks'] && class_exists( 'Nextcore_Team_Installer' ) ) {
            $output['social_networks'] = Nextcore_Team_Installer::default_social_networks();
        }

        return $output;
    }

    public function page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        $s = Nextcore_Team_Renderer::defaults();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Cài đặt Đội ngũ', 'nextcore-team' ); ?></h1>
            <form action="options.php" method="post">
                <?php settings_fields( 'nextcore_team_settings_group' ); ?>
                <table class="form-table" role="presentation">
                    <tr><th><?php esc_html_e( 'Cột desktop', 'nextcore-team' ); ?></th><td><input type="number" min="1" max="6" name="nextcore_team_settings[columns]" value="<?php echo esc_attr( $s['columns'] ); ?>"></td></tr>
                    <tr><th><?php esc_html_e( 'Cột tablet', 'nextcore-team' ); ?></th><td><input type="number" min="1" max="4" name="nextcore_team_settings[tablet_columns]" value="<?php echo esc_attr( $s['tablet_columns'] ); ?>"></td></tr>
                    <tr><th><?php esc_html_e( 'Cột mobile', 'nextcore-team' ); ?></th><td><input type="number" min="1" max="2" name="nextcore_team_settings[mobile_columns]" value="<?php echo esc_attr( $s['mobile_columns'] ); ?>"></td></tr>
                    <tr><th><?php esc_html_e( 'Tỷ lệ ảnh', 'nextcore-team' ); ?></th><td><select name="nextcore_team_settings[image_ratio]"><?php foreach ( array( '1-1' => '1:1', '4-5' => '4:5', '3-4' => '3:4', '16-9' => '16:9' ) as $value => $label ) : ?><option value="<?php echo esc_attr( $value ); ?>" <?php selected( $s['image_ratio'], $value ); ?>><?php echo esc_html( $label ); ?></option><?php endforeach; ?></select></td></tr>
                    <tr><th><?php esc_html_e( 'Hiển thị', 'nextcore-team' ); ?></th><td><label><input type="checkbox" name="nextcore_team_settings[show_section_heading]" value="1" <?php checked( $s['show_section_heading'], 1 ); ?>> <?php esc_html_e( 'Tiêu đề từng phần', 'nextcore-team' ); ?></label><br><label><input type="checkbox" name="nextcore_team_settings[show_role]" value="1" <?php checked( $s['show_role'], 1 ); ?>> <?php esc_html_e( 'Chức danh', 'nextcore-team' ); ?></label><br><label><input type="checkbox" name="nextcore_team_settings[show_contact]" value="1" <?php checked( $s['show_contact'], 1 ); ?>> <?php esc_html_e( 'Icon liên hệ/mạng xã hội', 'nextcore-team' ); ?></label></td></tr>
                    <tr><th><?php esc_html_e( 'Mạng xã hội', 'nextcore-team' ); ?></th><td><div id="nextcore-social-networks" class="nextcore-social-networks"><?php foreach ( $s['social_networks'] as $index => $network ) : ?><div class="nextcore-social-network-row" style="grid-template-columns:1fr 1fr 110px 110px auto"><input type="text" name="nextcore_team_settings[social_networks][<?php echo esc_attr( $index ); ?>][label]" value="<?php echo esc_attr( $network['label'] ); ?>" placeholder="VI"><input type="text" name="nextcore_team_settings[social_networks][<?php echo esc_attr( $index ); ?>][label_en]" value="<?php echo esc_attr( isset( $network['label_en'] ) ? $network['label_en'] : '' ); ?>" placeholder="EN"><input type="text" name="nextcore_team_settings[social_networks][<?php echo esc_attr( $index ); ?>][key]" value="<?php echo esc_attr( $network['key'] ); ?>" placeholder="key"><input type="text" name="nextcore_team_settings[social_networks][<?php echo esc_attr( $index ); ?>][icon]" value="<?php echo esc_attr( $network['icon'] ); ?>" placeholder="icon"><button type="button" class="button nextcore-remove-social-network"><?php esc_html_e( 'Xóa', 'nextcore-team' ); ?></button></div><?php endforeach; ?></div><p><button type="button" class="button" id="nextcore-add-social-network"><?php esc_html_e( 'Thêm mạng', 'nextcore-team' ); ?></button></p><p class="description"><?php esc_html_e( 'Key dùng làm meta field. Icon hỗ trợ: email, phone, facebook, linkedin, zalo, website; key khác sẽ dùng icon link mặc định.', 'nextcore-team' ); ?></p></td></tr>
                    <tr><th><?php esc_html_e( 'Custom CSS riêng', 'nextcore-team' ); ?></th><td><textarea class="nextcore-custom-css" name="nextcore_team_settings[custom_css]"><?php echo esc_textarea( $s['custom_css'] ); ?></textarea><p class="description"><?php esc_html_e( 'CSS này được in cùng plugin, độc lập với theme để có thể mang plugin đi nơi khác.', 'nextcore-team' ); ?></p></td></tr>
                </table>
                <?php submit_button(); ?>
            </form>
            <hr>
            <h2><?php esc_html_e( 'Chỗ nhúng', 'nextcore-team' ); ?></h2>
            <p><code>[nextcore_team]</code> &nbsp; <?php esc_html_e( 'hoặc', 'nextcore-team' ); ?> &nbsp; <code>[team_members]</code></p>
            <p><code>[nextcore_team section="project-manager" columns="3"]</code></p>
        </div>
        <?php
    }

}
