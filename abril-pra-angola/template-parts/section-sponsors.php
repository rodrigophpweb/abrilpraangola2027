<?php
/**
 * Template Part: Seção Patrocinadores & Apoiadores
 *
 * Exibe os patrocinadores cadastrados no CPT "patrocinador".
 * Carousel com 4 itens por página, setas e bullets de navegação.
 * Schema.org para melhor indexação por mecanismos de busca e bots de IA.
 *
 * @package Abril_Pra_Angola
 */

// ── Query do CPT patrocinador ─────────────────────────────────────────────────
$sponsors_query = new WP_Query( [
    'post_type'      => 'patrocinador',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => [ 'menu_order' => 'ASC', 'title' => 'ASC' ],
    'no_found_rows'  => true,
] );

if ( ! $sponsors_query->have_posts() ) {
    return;
}

// ── Variáveis de controle do carousel ────────────────────────────────────────
$sponsors    = $sponsors_query->posts;
$total       = count( $sponsors );
$per_page    = 4;
$total_pages = (int) ceil( $total / $per_page );
$show_nav    = $total > $per_page;
?>

<section
    class="section-sponsors"
    aria-labelledby="sponsors-heading"
    itemscope
    itemtype="https://schema.org/Event"
>
    <div class="container">

        <header class="section-sponsors__header">
            <h2 id="sponsors-heading" class="section-sponsors__title">
                <?php esc_html_e( 'Parceiros e Apoio', 'abril-pra-angola' ); ?>
            </h2>
        </header>

        <div
            class="sponsors-carousel"
            role="region"
            aria-label="<?php esc_attr_e( 'Carrossel de patrocinadores', 'abril-pra-angola' ); ?>"
            data-per-page="<?php echo esc_attr( $per_page ); ?>"
            data-total-pages="<?php echo esc_attr( $total_pages ); ?>"
        >

            <?php if ( $show_nav ) : ?>
            <button
                class="sponsors-carousel__btn sponsors-carousel__btn--prev"
                type="button"
                aria-label="<?php esc_attr_e( 'Patrocinadores anteriores', 'abril-pra-angola' ); ?>"
                aria-controls="sponsors-track"
                disabled
            >
                <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <polyline points="15 18 9 12 15 6"
                              stroke="currentColor" stroke-width="2.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <?php endif; ?>

            <div class="sponsors-carousel__viewport">
                <ul
                    class="sponsors-carousel__track"
                    id="sponsors-track"
                    role="list"
                    aria-live="polite"
                    aria-atomic="false"
                >
                    <?php
                    $page_num = 0;
                    foreach ( $sponsors as $index => $sponsor ) :
                        // ── Abre nova página a cada $per_page itens ────────
                        if ( $index % $per_page === 0 ) :
                            $page_num++;
                            $is_first_page = ( $page_num === 1 );
                    ?>
                    <li
                        class="sponsors-carousel__slide<?php echo $is_first_page ? ' is-active' : ''; ?>"
                        role="group"
                        aria-roledescription="<?php esc_attr_e( 'slide', 'abril-pra-angola' ); ?>"
                        aria-label="<?php printf(
                            /* translators: 1: página atual, 2: total */
                            esc_attr__( 'Página %1$d de %2$d', 'abril-pra-angola' ),
                            $page_num,
                            $total_pages
                        ); ?>"
                        data-page="<?php echo esc_attr( $page_num ); ?>"
                        aria-hidden="<?php echo $is_first_page ? 'false' : 'true'; ?>"
                    >
                        <ul class="sponsors-carousel__group" role="list">
                    <?php endif; ?>

                            <?php
                            setup_postdata( $sponsor );

                            $sponsor_url   = function_exists( 'get_field' )
                                ? get_field( 'patrocinador_url', $sponsor->ID )
                                : '';
                            $sponsor_title = get_the_title( $sponsor->ID );
                            $thumb_id      = get_post_thumbnail_id( $sponsor->ID );
                            $thumb_src     = $thumb_id
                                ? wp_get_attachment_image_url( $thumb_id, 'medium' )
                                : '';
                            $thumb_srcset  = $thumb_id
                                ? wp_get_attachment_image_srcset( $thumb_id, 'medium' )
                                : '';
                            ?>

                            <li
                                class="sponsor-item"
                                itemscope
                                itemprop="sponsor"
                                itemtype="https://schema.org/Organization"
                            >
                                <?php if ( $sponsor_url ) : ?>
                                <a
                                    href="<?php echo esc_url( $sponsor_url ); ?>"
                                    target="_blank"
                                    rel="noreferrer noopener nofollow"
                                    class="sponsor-item__link"
                                    itemprop="url"
                                    aria-label="<?php printf(
                                        /* translators: %s: nome do patrocinador */
                                        esc_attr__( 'Visitar site de %s (abre em nova aba)', 'abril-pra-angola' ),
                                        $sponsor_title
                                    ); ?>"
                                >
                                <?php endif; ?>

                                    <figure class="sponsor-item__figure">
                                        <?php if ( $thumb_src ) : ?>
                                        <img
                                            src="<?php echo esc_url( $thumb_src ); ?>"
                                            <?php if ( $thumb_srcset ) : ?>
                                            srcset="<?php echo esc_attr( $thumb_srcset ); ?>"
                                            sizes="(max-width: 48rem) 40vw, 15rem"
                                            <?php endif; ?>
                                            alt="<?php echo esc_attr( $sponsor_title ); ?>"
                                            class="sponsor-item__logo"
                                            loading="lazy"
                                            decoding="async"
                                            itemprop="logo"
                                            width="240"
                                            height="150"
                                        >
                                        <?php else : ?>
                                        <span
                                            class="sponsor-item__logo-placeholder"
                                            aria-hidden="true"
                                        ></span>
                                        <?php endif; ?>
                                    </figure>

                                    <span class="sponsor-item__name" itemprop="name">
                                        <?php echo esc_html( $sponsor_title ); ?>
                                    </span>

                                <?php if ( $sponsor_url ) : ?>
                                </a>
                                <?php endif; ?>

                            </li><!-- /.sponsor-item -->

                    <?php
                        // ── Fecha a página quando completa ou no último item ──
                        $is_last_in_page = ( ( $index + 1 ) % $per_page === 0 )
                            || ( $index === $total - 1 );
                        if ( $is_last_in_page ) :
                    ?>
                        </ul><!-- /.sponsors-carousel__group -->
                    </li><!-- /.sponsors-carousel__slide -->
                    <?php
                        endif;
                    endforeach;
                    wp_reset_postdata();
                    ?>

                </ul><!-- /.sponsors-carousel__track -->
            </div><!-- /.sponsors-carousel__viewport -->

            <?php if ( $show_nav ) : ?>
            <button
                class="sponsors-carousel__btn sponsors-carousel__btn--next"
                type="button"
                aria-label="<?php esc_attr_e( 'Próximos patrocinadores', 'abril-pra-angola' ); ?>"
                aria-controls="sponsors-track"
            >
                <svg aria-hidden="true" focusable="false" xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <polyline points="9 18 15 12 9 6"
                              stroke="currentColor" stroke-width="2.5"
                              stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <?php endif; ?>

        </div><!-- /.sponsors-carousel -->

        <?php if ( $show_nav ) : ?>
        <nav
            class="sponsors-carousel__nav"
            aria-label="<?php esc_attr_e( 'Páginas do carrossel de patrocinadores', 'abril-pra-angola' ); ?>"
        >
            <ul class="sponsors-carousel__bullets" role="list">
                <?php for ( $p = 1; $p <= $total_pages; $p++ ) : ?>
                <li class="sponsors-carousel__bullet-item" role="listitem">
                    <button
                        class="sponsors-carousel__bullet<?php echo $p === 1 ? ' is-active' : ''; ?>"
                        type="button"
                        data-page="<?php echo esc_attr( $p ); ?>"
                        aria-label="<?php printf(
                            /* translators: %d: número da página */
                            esc_attr__( 'Ir para página %d', 'abril-pra-angola' ),
                            $p
                        ); ?>"
                        aria-current="<?php echo $p === 1 ? 'true' : 'false'; ?>"
                    ></button>
                </li>
                <?php endfor; ?>
            </ul>
        </nav><!-- /.sponsors-carousel__nav -->
        <?php endif; ?>

    </div><!-- /.container -->
</section><!-- /.section-sponsors -->

