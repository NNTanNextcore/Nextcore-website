<?php defined('ABSPATH') || exit; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('nextcore-entry'); ?>>
    <header class="nextcore-shell"><h1><?php the_title(); ?></h1></header>
    <?php nextcore_render_content(); ?>
</article>

