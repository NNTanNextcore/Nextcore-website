<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Nextcore_Team {
    private static $instance = null;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-post-type.php';
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-meta.php';
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-renderer.php';
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-shortcode.php';
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-admin.php';
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-settings.php';
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-block.php';
        require_once NEXTCORE_TEAM_DIR . 'includes/class-nextcore-team-builders.php';

        new Nextcore_Team_Post_Type();
        new Nextcore_Team_Meta();
        new Nextcore_Team_Renderer();
        new Nextcore_Team_Shortcode();
        new Nextcore_Team_Admin();
        new Nextcore_Team_Settings();
        new Nextcore_Team_Block();
        new Nextcore_Team_Builders();

        add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
        add_action( 'admin_init', array( $this, 'maybe_upgrade' ) );
    }

    public function load_textdomain() {
        load_plugin_textdomain( 'nextcore-team', false, dirname( plugin_basename( NEXTCORE_TEAM_FILE ) ) . '/languages' );
    }

    public function maybe_upgrade() {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        if ( get_option( 'nextcore_team_version' ) === NEXTCORE_TEAM_VERSION ) {
            return;
        }
        Nextcore_Team_Installer::seed_settings();
        Nextcore_Team_Installer::seed_sections_and_members();
        Nextcore_Team_Installer::seed_translations();
        update_option( 'nextcore_team_version', NEXTCORE_TEAM_VERSION );
    }
}
