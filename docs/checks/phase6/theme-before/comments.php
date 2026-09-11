<?php
defined('ABSPATH') || exit;
if (post_password_required()) { return; }
?>
<section id="comments" class="nextcore-shell">
<?php if (have_comments()) : ?>
    <h2><?php esc_html_e('Bình luận', 'nextcore-theme'); ?></h2>
    <ol class="comment-list"><?php wp_list_comments(array('style' => 'ol', 'short_ping' => true)); ?></ol>
    <?php the_comments_navigation(); ?>
<?php endif; ?>
<?php comment_form(); ?>
</section>

