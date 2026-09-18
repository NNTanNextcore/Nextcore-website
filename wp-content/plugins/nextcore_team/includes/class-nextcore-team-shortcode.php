<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Nextcore_Team_Shortcode {
    public function __construct() {
        add_shortcode( 'nextcore_team', array( $this, 'render' ) );
        add_shortcode( 'team_members', array( $this, 'render' ) );
    }

    public function render( $atts ) {
        return Nextcore_Team_Renderer::render( is_array( $atts ) ? $atts : array() );
    }
}
