<?php
defined('ABSPATH') || exit;

global $nextcore_footer_content_rendered;

if (empty($nextcore_footer_content_rendered)) {
    get_template_part('template-parts/global/footer-content');
}
?>
<?php wp_footer(); ?>
</body>
</html>
