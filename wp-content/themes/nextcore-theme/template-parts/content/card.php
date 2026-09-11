<?php defined('ABSPATH') || exit;
$taxonomy = get_post_type() === 'dich-vu' ? 'danh-muc-dich-vu' : 'category';
$terms = get_the_terms(get_the_ID(), $taxonomy);
?>
<article <?php post_class('nextcore-post-card'); ?>>
    <a class="nextcore-card-media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
        <?php if (has_post_thumbnail()) { the_post_thumbnail('large', array('loading' => 'lazy', 'alt' => '')); }
        else { echo '<span class="nextcore-card-placeholder">N</span>'; } ?>
    </a>
    <div class="nextcore-card-copy">
        <?php if ($terms && !is_wp_error($terms)) : ?><p class="nextcore-card-meta"><?php echo esc_html($terms[0]->name); ?></p><?php endif; ?>
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php echo esc_html(wp_trim_words(wp_strip_all_tags(get_the_excerpt()), 34)); ?></p>
    </div>
</article>
