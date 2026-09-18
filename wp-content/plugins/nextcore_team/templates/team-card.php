<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$show_role = filter_var( $atts['show_role'], FILTER_VALIDATE_BOOLEAN );
$show_contact = filter_var( $atts['show_contact'], FILTER_VALIDATE_BOOLEAN );
$phone_href = '';
if ( ! empty( $contacts['phone'] ) ) {
    $phone_href = preg_replace( '/[^0-9+]/', '', $contacts['phone'] );
}
?>
<article class="nextcore-team-card">
    <div class="nextcore-team-photo nextcore-team-ratio-<?php echo esc_attr( $image_ratio ); ?>">
        <?php if ( has_post_thumbnail( $post_id ) ) : ?>
            <?php echo get_the_post_thumbnail( $post_id, 'large', array( 'loading' => 'lazy', 'alt' => the_title_attribute( array( 'echo' => false ) ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php else : ?>
            <div class="nextcore-team-photo-placeholder" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-9 10a9 9 0 0 1 18 0H3Z"/></svg>
            </div>
        <?php endif; ?>
    </div>
    <div class="nextcore-team-body">
        <h3 class="nextcore-team-name" data-no-translation><?php echo esc_html( Nextcore_Team_Renderer::member_name( $post_id ) ); ?></h3>
        <?php if ( $show_role && $role_name ) : ?>
            <div class="nextcore-team-role" data-no-translation><?php echo esc_html( $role_name ); ?></div>
        <?php endif; ?>
        <?php if ( $show_contact ) : ?>
            <div class="nextcore-team-contact" aria-label="<?php esc_attr_e( 'Thông tin liên hệ', 'nextcore-team' ); ?>">
                <?php if ( ! empty( $contacts['email'] ) ) : ?>
                    <a href="mailto:<?php echo esc_attr( antispambot( $contacts['email'] ) ); ?>" aria-label="<?php esc_attr_e( 'Email', 'nextcore-team' ); ?>"><?php echo Nextcore_Team_Renderer::icon( 'email' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
                <?php endif; ?>
                <?php if ( $phone_href ) : ?>
                    <a href="tel:<?php echo esc_attr( $phone_href ); ?>" aria-label="<?php esc_attr_e( 'Điện thoại', 'nextcore-team' ); ?>"><?php echo Nextcore_Team_Renderer::icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
                <?php endif; ?>
                <?php foreach ( Nextcore_Team_Meta::social_networks() as $network ) : ?>
                    <?php if ( ! empty( $contacts[ $network['key'] ] ) ) : ?>
                        <a href="<?php echo esc_url( $contacts[ $network['key'] ] ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( Nextcore_Team_Renderer::is_english() && ! empty( $network['label_en'] ) ? $network['label_en'] : $network['label'] ); ?>"><?php echo Nextcore_Team_Renderer::icon( $network['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</article>
