<?php
defined('ABSPATH') || exit;

$footer_rendered = function_exists('elementor_theme_do_location') && elementor_theme_do_location('footer');

if (!$footer_rendered) :
    ?>
    <footer class="site-footer nextcore-native" role="contentinfo">
        <div class="container">
            <p>&copy; <?php echo esc_html(wp_date('Y')); ?> <?php esc_html_e('Nextcore. All rights reserved.', 'nextcore-theme'); ?></p>
        </div>
    </footer>
<?php endif; ?>
