<?php
    get_header();
    get_template_part( 'template-parts/hero', 'banner' );
    get_template_part( 'template-parts/section-about', 'general' );
?>

<main id="main">

    <?php get_template_part( 'template-parts/section-picture-content', null, [ 'variant' => 'responsavel' ] ); ?>
    <?php get_template_part( 'template-parts/section-picture-content', null, [ 'variant' => 'atividades' ] ); ?>

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        </article>
    <?php endwhile; endif; ?>
    <?php
        get_template_part( 'template-parts/section-schedule' );
        get_template_part( 'template-parts/section', 'speakers' );
        get_template_part( 'template-parts/section-tickets' );
        get_template_part( 'template-parts/section-location' );
        get_template_part( 'template-parts/section-subscribe' );
        get_template_part( 'template-parts/section', 'sponsors' );
        get_template_part( 'template-parts/faq' );
    ?>

</main>

<?php get_footer(); ?>
