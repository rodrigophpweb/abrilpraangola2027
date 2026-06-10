<?php get_header(); ?>

<main id="main">
    <?php
    get_template_part( 'template-parts/breadcrumb', null, [
        'items' => [
            [ 'label' => get_the_title(), 'url' => '' ],
        ],
    ] );
    ?>
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <h2><?php the_title(); ?></h2>
            <div><?php the_content(); ?></div>
        </article>
    <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>

