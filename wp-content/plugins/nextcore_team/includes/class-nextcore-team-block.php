<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Block {
    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
        add_filter( 'block_categories_all', array( $this, 'category' ) );
    }

    public function category( $categories ) {
        $categories[] = array(
            'slug'  => 'nextcore-team',
            'title' => __( 'Nextcore Team', 'nextcore-team' ),
            'icon'  => 'groups',
        );
        return $categories;
    }

    public function register() {
        register_block_type( NEXTCORE_TEAM_DIR . 'blocks/team-grid', array(
            'render_callback' => array( $this, 'render' ),
        ) );
    }

    public function render( $attributes ) {
        return Nextcore_Team_Renderer::render( $attributes );
    }
}
