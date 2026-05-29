<?php
/**
 * Template Part: Seção Oficineiros (Speakers)
 *
 * Exibe os oficineiros cadastrados no CPT "oficineiro".
 * Layout em grade responsiva com foto, cargo e redes sociais.
 * Schema.org Person para indexação semântica por mecanismos de busca e bots de IA.
 *
 * @package Abril_Pra_Angola
 */

// ── Query do CPT oficineiro ──────────────────────────────────────────────────
$speakers_query = new WP_Query( [
    'post_type'      => 'oficineiro',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => [ 'menu_order' => 'ASC', 'title' => 'ASC' ],
    'no_found_rows'  => true,
] );

// ── Opções da seção via Options Page ────────────────────────────────────────
$opcoes       = function_exists( 'abril_get_oficineiros_options' ) ? abril_get_oficineiros_options() : [];
$titulo_secao = $opcoes['oficineiros_titulo']    ?? __( 'Oficineiros do Evento', 'abril-pra-angola' );
$descricao    = $opcoes['oficineiros_descricao'] ?? '';

// ── Mapa de ícones e labels para redes sociais ───────────────────────────────
$icons   = function_exists( 'abril_get_social_network_icons' )   ? abril_get_social_network_icons()   : [];
$choices = function_exists( 'abril_get_social_network_choices' ) ? abril_get_social_network_choices() : [];
?>

<section
    id="oficineiros"
    class="section-speakers"
    aria-labelledby="speakers-heading"
    itemscope
    itemtype="https://schema.org/Event"
>
    <div class="container">

        <!-- ── Cabeçalho da seção ─────────────────────────────────── -->
        <header class="section-speakers__header">
            <h2 id="speakers-heading" class="section-speakers__title">
                <?php echo esc_html( $titulo_secao ); ?>
            </h2>

            <?php if ( $descricao ) : ?>
            <div class="section-speakers__description">
                <?php echo wp_kses_post( $descricao ); ?>
            </div>
            <?php endif; ?>
        </header><!-- /.section-speakers__header -->

        <?php if ( $speakers_query->have_posts() ) : ?>

        <!-- ── Grade de oficineiros ───────────────────────────────── -->
        <div class="section-speakers__grid" role="list">

            <?php while ( $speakers_query->have_posts() ) : $speakers_query->the_post(); ?>
            <?php
            $speaker_id   = get_the_ID();
            $speaker_name = get_the_title();
            $thumb_id     = get_post_thumbnail_id( $speaker_id );
            $thumb_src    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium_large' ) : '';
            $thumb_srcset = $thumb_id ? wp_get_attachment_image_srcset( $thumb_id, 'medium_large' ) : '';
            $thumb_alt    = $thumb_id ? (string) get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) : '';
            $thumb_alt    = $thumb_alt ?: $speaker_name;
            $grupo_escola = function_exists( 'get_field' ) ? (string) get_field( 'oficineiro_grupo_escola', $speaker_id ) : '';
            $redes        = function_exists( 'get_field' ) ? get_field( 'oficineiro_redes_sociais', $speaker_id ) : [];
            ?>

            <article
                class="speaker-card"
                role="listitem"
                itemscope
                itemprop="performer"
                itemtype="https://schema.org/Person"
            >
                <!-- ── Foto do oficineiro ──────────────────────── -->
                <figure class="speaker-card__figure">
                    <?php if ( $thumb_src ) : ?>
                    <img
                        src="<?php echo esc_url( $thumb_src ); ?>"
                        <?php if ( $thumb_srcset ) : ?>
                        srcset="<?php echo esc_attr( $thumb_srcset ); ?>"
                        sizes="(max-width: 40rem) 100vw, (max-width: 64rem) 50vw, (max-width: 80rem) 33vw, 25vw"
                        <?php endif; ?>
                        alt="<?php echo esc_attr( $thumb_alt ); ?>"
                        class="speaker-card__photo"
                        loading="lazy"
                        decoding="async"
                        itemprop="image"
                        width="600"
                        height="750"
                    >
                    <?php else : ?>
                    <div class="speaker-card__photo-placeholder" aria-hidden="true">
                        <i class="fa-solid fa-person-walking" aria-hidden="true"></i>
                    </div>
                    <?php endif; ?>
                </figure><!-- /.speaker-card__figure -->

                <!-- ── Informações do oficineiro ──────────────── -->
                <div class="speaker-card__body">
                    <h3 class="speaker-card__name" itemprop="name">
                        <?php echo esc_html( $speaker_name ); ?>
                    </h3>

                    <?php if ( $grupo_escola ) : ?>
                    <p class="speaker-card__group" itemprop="affiliation">
                        <i class="fa-solid fa-users" aria-hidden="true"></i>
                        <span><?php echo esc_html( $grupo_escola ); ?></span>
                    </p>
                    <?php endif; ?>
                </div><!-- /.speaker-card__body -->

                <!-- ── Redes Sociais + Saiba Mais ──────────────── -->
                <footer class="speaker-card__footer">

                    <?php
                    $redes_validas = [];
                    if ( ! empty( $redes ) && is_array( $redes ) ) {
                        foreach ( $redes as $rede ) {
                            $net = sanitize_key( $rede['rede_social'] ?? '' );
                            $url_rede = esc_url( $rede['rede_social_url'] ?? '' );
                            if ( $net && $url_rede ) {
                                $redes_validas[] = [
                                    'network'    => $net,
                                    'url'        => $url_rede,
                                    'icon_class' => $icons[ $net ]   ?? 'fa-solid fa-link',
                                    'label'      => $choices[ $net ] ?? ucfirst( $net ),
                                ];
                            }
                        }
                    }
                    ?>

                    <?php if ( ! empty( $redes_validas ) ) : ?>
                    <nav
                        class="speaker-card__social"
                        aria-label="<?php printf(
                            esc_attr__( 'Redes sociais de %s', 'abril-pra-angola' ),
                            $speaker_name
                        ); ?>"
                    >
                        <?php foreach ( $redes_validas as $item ) : ?>
                        <a
                            class="speaker-card__social-link"
                            href="<?php echo $item['url']; ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="<?php echo esc_attr( $item['label'] ); ?>"
                            itemprop="sameAs"
                        >
                            <i class="<?php echo esc_attr( $item['icon_class'] ); ?>" aria-hidden="true"></i>
                            <span class="screen-reader-text"><?php echo esc_html( $item['label'] ); ?></span>
                        </a>
                        <?php endforeach; ?>
                    </nav>
                    <?php endif; ?>

                    <a
                        class="speaker-card__more"
                        href="<?php echo esc_url( get_permalink( $speaker_id ) ); ?>"
                        aria-label="<?php printf(
                            esc_attr__( 'Saiba mais sobre %s', 'abril-pra-angola' ),
                            $speaker_name
                        ); ?>"
                    >
                        <?php esc_html_e( 'Saiba Mais', 'abril-pra-angola' ); ?>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>

                </footer><!-- /.speaker-card__footer -->

            </article><!-- /.speaker-card -->

            <?php endwhile; wp_reset_postdata(); ?>

        </div><!-- /.section-speakers__grid -->

        <?php else : ?>

        <p class="section-speakers__empty">
            <?php esc_html_e( 'Os oficineiros desta edição serão anunciados em breve. Fique atento!', 'abril-pra-angola' ); ?>
        </p>

        <?php endif; ?>

    </div><!-- /.container -->
</section><!-- /.section-speakers -->

