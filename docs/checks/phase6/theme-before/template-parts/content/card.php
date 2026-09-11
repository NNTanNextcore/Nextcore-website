<?php defined('ABSPATH') || exit; ?>
<article <?php post_class('nextcore-post-card'); ?>>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
    <?php the_excerpt(); ?>
</article>

