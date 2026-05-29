<?php
/**
 * Template Part: Seção FAQ
 *
 * Exibe as perguntas frequentes cadastradas no CPT "faq".
 * Título e descrição da seção são gerenciados via ACF Options (page-options.php).
 *
 * Marcação Schema.org FAQPage para melhor indexação por mecanismos de busca e bots de IA.
 */

// ── Opções da seção via ACF Options ──────────────────────────────────────────
$faq_options = function_exists( 'abril_get_faq_options' ) ? abril_get_faq_options() : [];
$faq_titulo  = ! empty( $faq_options['faq_titulo'] )
    ? $faq_options['faq_titulo']
    : __( 'Perguntas Frequentes', 'abril-pra-angola' );
$faq_desc    = $faq_options['faq_descricao'] ?? '';

// ── Query do CPT faq ─────────────────────────────────────────────────────────
$faq_query = new WP_Query( [
    'post_type'      => 'faq',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'no_found_rows'  => true,
] );
?>

<?php if ( $faq_query->have_posts() ) : ?>
<section
    id="faq"
    class="section-faq"
    aria-labelledby="faq-heading"
    itemscope
    itemtype="https://schema.org/FAQPage"
>
    <div class="container">

        <header class="section-faq__header">
            <h2 id="faq-heading" class="section-faq__title">
                <?php echo esc_html( $faq_titulo ); ?>
            </h2>
            <?php if ( $faq_desc ) : ?>
                <p class="section-faq__description">
                    <?php echo esc_html( $faq_desc ); ?>
                </p>
            <?php endif; ?>
        </header>

        <div class="section-faq__list" role="list">

            <?php while ( $faq_query->have_posts() ) : $faq_query->the_post(); ?>

                <article
                    class="faq-item"
                    role="listitem"
                    itemscope
                    itemprop="mainEntity"
                    itemtype="https://schema.org/Question"
                >
                    <details class="faq-item__details">

                        <summary class="faq-item__question" itemprop="name">
                            <?php the_title(); ?>
                        </summary>

                        <div
                            class="faq-item__answer"
                            itemscope
                            itemprop="acceptedAnswer"
                            itemtype="https://schema.org/Answer"
                        >
                            <div itemprop="text" class="faq-item__answer-text">
                                <?php the_content(); ?>
                            </div>
                        </div>

                    </details>
                </article>

            <?php endwhile; wp_reset_postdata(); ?>

        </div><!-- /.section-faq__list -->

    </div><!-- /.container -->
</section><!-- /.section-faq -->
<?php endif; ?>
