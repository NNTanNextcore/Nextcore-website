<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

require_once __DIR__ . '/includes/class-nextcore-team-post-type.php';
$post_type = new Nextcore_Team_Post_Type();
$post_type->register();

$posts = get_posts( array(
    'post_type'      => 'nextcore_member',
    'post_status'    => get_post_stati(),
    'posts_per_page' => -1,
    'fields'         => 'ids',
) );
foreach ( $posts as $post_id ) {
    wp_delete_post( $post_id, true );
}

$terms = get_terms( array(
    'taxonomy'   => 'nextcore_role',
    'hide_empty' => false,
    'fields'     => 'ids',
) );
if ( ! is_wp_error( $terms ) ) {
    foreach ( $terms as $term_id ) {
        wp_delete_term( $term_id, 'nextcore_role' );
    }
}

delete_option( 'nextcore_team_settings' );
delete_option( 'nextcore_team_seeded' );
delete_option( 'nextcore_team_default_css_seeded' );
delete_option( 'nextcore_team_translations_seeded' );
delete_option( 'nextcore_team_page_copy_seeded' );
delete_option( 'nextcore_team_version' );
