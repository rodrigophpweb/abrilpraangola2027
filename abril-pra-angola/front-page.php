<?php
    get_header();
    get_template_part( 'template-parts/hero', 'banner' ); // template-parts/file-name.php

?>

<main id="main">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        </article>
    <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>

