<?php
/**
 * Single — Oficineiro & Homenageado
 *
 * Exibe o perfil completo de um Oficineiro ou Homenageado.
 * Schema.org Person + BreadcrumbList para rastreabilidade máxima.
 *
 * @package Abril_Pra_Angola
 */

get_header();

$post_type      = get_post_type();
$is_homenageado = ( $post_type === 'homenageado' );
$is_oficineiro  = ( $post_type === 'oficineiro' );

// Ícones e rótulos das redes sociais
$icons   = function_exists( 'abril_get_social_network_icons' )   ? abril_get_social_network_icons()   : [];
$choices = function_exists( 'abril_get_social_network_choices' ) ? abril_get_social_network_choices() : [];
?>

<main id="main-content" class="single-person">

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

        $post_id    = get_the_ID();
        $post_title = get_the_title();
        $post_url   = get_permalink();
        $excerpt    = wp_strip_all_tags( get_the_excerpt() );

        // ── Imagem Destacada ─────────────────────────────────────────────
        $thumb_id     = get_post_thumbnail_id( $post_id );
        $thumb_src    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium_large' ) : '';
        $thumb_srcset = $thumb_id ? wp_get_attachment_image_srcset( $thumb_id, 'medium_large' ) : '';
        $thumb_alt    = $thumb_id ? (string) get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) : '';
        $thumb_alt    = $thumb_alt ?: $post_title;

        // ── Campos ACF — prefixo varia conforme o CPT ───────────────────
        $prefix       = $is_homenageado ? 'homenageado' : 'oficineiro';
        $grupo_escola = function_exists( 'get_field' ) ? (string) get_field( $prefix . '_grupo_escola', $post_id ) : '';
        $redes        = function_exists( 'get_field' ) ? get_field( $prefix . '_redes_sociais', $post_id ) : [];

        // ── Filtragem das redes sociais ──────────────────────────────────
        $redes_validas = [];
        if ( ! empty( $redes ) && is_array( $redes ) ) {
            foreach ( $redes as $rede ) {
                $net      = sanitize_key( $rede['rede_social'] ?? '' );
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

        // ── Breadcrumb ───────────────────────────────────────────────────
        $archive_url   = get_post_type_archive_link( $post_type );
        $archive_label = $is_homenageado
            ? __( 'Homenageados', 'abril-pra-angola' )
            : __( 'Oficineiros', 'abril-pra-angola' );
    ?>

    <!-- ── Breadcrumb ──────────────────────────────────────────────────── -->
    <nav
        class="single-person__breadcrumb"
        aria-label="<?php esc_attr_e( 'Breadcrumb de navegação', 'abril-pra-angola' ); ?>"
        itemscope
        itemtype="https://schema.org/BreadcrumbList"
    >
        <div class="container">
            <ol class="single-person__breadcrumb-list">
                <li
                    class="single-person__breadcrumb-item"
                    itemprop="itemListElement"
                    itemscope
                    itemtype="https://schema.org/ListItem"
                >
                    <a itemprop="item" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <i class="fa-solid fa-house" aria-hidden="true"></i>
                        <span itemprop="name"><?php esc_html_e( 'Home', 'abril-pra-angola' ); ?></span>
                    </a>
                    <meta itemprop="position" content="1">
                </li>

                <?php if ( $archive_url ) : ?>
                <li
                    class="single-person__breadcrumb-item"
                    itemprop="itemListElement"
                    itemscope
                    itemtype="https://schema.org/ListItem"
                >
                    <a itemprop="item" href="<?php echo esc_url( $archive_url ); ?>">
                        <span itemprop="name"><?php echo esc_html( $archive_label ); ?></span>
                    </a>
                    <meta itemprop="position" content="2">
                </li>
                <?php endif; ?>

                <li
                    class="single-person__breadcrumb-item single-person__breadcrumb-item--current"
                    itemprop="itemListElement"
                    itemscope
                    itemtype="https://schema.org/ListItem"
                    aria-current="page"
                >
                    <span itemprop="name"><?php echo esc_html( $post_title ); ?></span>
                    <meta itemprop="position" content="<?php echo $archive_url ? '3' : '2'; ?>">
                </li>
            </ol>
        </div>
    </nav><!-- /.single-person__breadcrumb -->

    <!-- ── Artigo principal ────────────────────────────────────────────── -->
    <article
        id="post-<?php the_ID(); ?>"
        <?php post_class( 'single-person__article' ); ?>
        itemscope
        itemtype="https://schema.org/Person"
    >
        <!-- Metadados invisíveis — schema.org -->
        <meta itemprop="url" content="<?php echo esc_url( $post_url ); ?>">
        <?php if ( $excerpt ) : ?>
        <meta itemprop="description" content="<?php echo esc_attr( $excerpt ); ?>">
        <?php endif; ?>
        <?php if ( $grupo_escola ) : ?>
        <meta itemprop="affiliation" content="<?php echo esc_attr( $grupo_escola ); ?>">
        <?php endif; ?>

        <div class="single-person__layout container">

            <!-- ── Sidebar ──────────────────────────────────────────────── -->
            <aside
                class="single-person__aside"
                aria-label="<?php esc_attr_e( 'Perfil do apresentante', 'abril-pra-angola' ); ?>"
            >
                <!-- Imagem Destacada — 150px de altura, largura proporcional -->
                <figure class="single-person__figure">
                    <?php if ( $thumb_src ) : ?>
                    <img
                        src="<?php echo esc_url( $thumb_src ); ?>"
                        <?php if ( $thumb_srcset ) : ?>
                        srcset="<?php echo esc_attr( $thumb_srcset ); ?>"
                        sizes="(max-width: 48rem) 9.375rem, 9.375rem"
                        <?php endif; ?>
                        alt="<?php echo esc_attr( $thumb_alt ); ?>"
                        class="single-person__photo"
                        loading="eager"
                        decoding="async"
                        itemprop="image"
                        width="200"
                        height="200"
                    >
                    <?php else : ?>
                    <div class="single-person__photo-placeholder" role="img" aria-label="<?php echo esc_attr( $post_title ); ?>">
                        <i class="fa-solid fa-person-walking" aria-hidden="true"></i>
                    </div>
                    <?php endif; ?>
                </figure><!-- /.single-person__figure -->

                <!-- Redes Sociais — ícones 24 × 24 px -->
                <?php if ( ! empty( $redes_validas ) ) : ?>
                <nav
                    class="single-person__social"
                    aria-label="<?php printf(
                        esc_attr__( 'Redes sociais de %s', 'abril-pra-angola' ),
                        esc_attr( $post_title )
                    ); ?>"
                >
                    <?php foreach ( $redes_validas as $item ) : ?>
                    <a
                        class="single-person__social-link"
                        href="<?php echo esc_url( $item['url'] ); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="<?php printf(
                            esc_attr__( '%1$s de %2$s — abre em nova aba', 'abril-pra-angola' ),
                            esc_attr( $item['label'] ),
                            esc_attr( $post_title )
                        ); ?>"
                        itemprop="sameAs"
                        data-network="<?php echo esc_attr( $item['network'] ); ?>"
                    >
                        <i class="<?php echo esc_attr( $item['icon_class'] ); ?>" aria-hidden="true"></i>
                        <span class="screen-reader-text"><?php echo esc_html( $item['label'] ); ?></span>
                    </a>
                    <?php endforeach; ?>
                </nav><!-- /.single-person__social -->
                <?php endif; ?>

            </aside><!-- /.single-person__aside -->

            <!-- ── Conteúdo principal ────────────────────────────────────── -->
            <div class="single-person__main">

                <header class="single-person__header">

                    <?php if ( $grupo_escola ) : ?>
                    <p class="single-person__group">
                        <i class="fa-solid fa-users" aria-hidden="true"></i>
                        <span><?php echo esc_html( $grupo_escola ); ?></span>
                    </p>
                    <?php endif; ?>

                    <h1 class="single-person__title" itemprop="name">
                        <?php echo esc_html( $post_title ); ?>
                    </h1>

                </header><!-- /.single-person__header -->

                <?php if ( get_the_content() ) : ?>
                <div class="single-person__content entry-content" itemprop="description">
                    <?php the_content(); ?>
                </div><!-- /.single-person__content -->
                <?php endif; ?>

            </div><!-- /.single-person__main -->

        </div><!-- /.single-person__layout -->

    </article><!-- /.single-person__article -->

    <?php endwhile; endif; ?>

    <?php
        get_template_part( 'template-parts/section-schedule' );
        get_template_part( 'template-parts/section-tickets' );
        get_template_part( 'template-parts/section-location' );
        get_template_part( 'template-parts/section-subscribe' );
        get_template_part( 'template-parts/section', 'sponsors' );
        get_template_part( 'template-parts/faq' );
    ?>

</main><!-- /#main-content -->

<?php get_footer(); ?>


