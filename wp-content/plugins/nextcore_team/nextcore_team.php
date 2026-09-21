<?php
/**
 * Plugin Name: Nextcore Team
 * Plugin URI: https://nextcore.vn
 * Description: Quản lý đội ngũ theo từng phần, mạng xã hội động, kéo thả sắp xếp và hiển thị bằng shortcode, Gutenberg, Elementor và Flatsome UX Builder.
 * Version: 1.2.8
 * Author: Nextcore
 * Author URI: https://nextcore.vn
 * Text Domain: nextcore-team
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'NEXTCORE_TEAM_VERSION', '1.2.8' );
define( 'NEXTCORE_TEAM_FILE', __FILE__ );
define( 'NEXTCORE_TEAM_DIR', plugin_dir_path( __FILE__ ) );
define( 'NEXTCORE_TEAM_URL', plugin_dir_url( __FILE__ ) );

require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-installer.php';
require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team.php';

register_activation_hook( __FILE__, array( 'Nextcore_Team_Installer', 'activate' ) );

function nextcore_team() {
    return Nextcore_Team::instance();
}

nextcore_team();
