<?php
/**
 * Template Part: Seção Ingressos / Pacotes
 *
 * Options Page (Home):
 *  - tickets_titulo    (text)
 *  - tickets_descricao (wysiwyg)
 *
 * Post Type: pacote
 *  - pacote_preco_avista  (number)
 *  - pacote_preco_cartao  (number)
 *  - pacote_validade      (date_picker — d/m/Y)
 *  - pacote_descricao     (textarea — o que está incluído)
 */

if ( ! function_exists( 'get_field' ) ) {
    return;
}

$home_opts    = function_exists( 'abril_get_home_options' ) ? abril_get_home_options() : [];
$titulo       = ! empty( $home_opts['tickets_titulo'] )    ? $home_opts['tickets_titulo']    : __( 'Ingressos', 'abril-pra-angola' );
$descricao    = ! empty( $home_opts['tickets_descricao'] ) ? $home_opts['tickets_descricao'] : '';

// Busca a URL da página de inscrição pelo template (independente do slug)
$inscricao_pages = get_posts( [
    'post_type'      => 'page',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'meta_key'       => '_wp_page_template',
    'meta_value'     => 'template-pages/subscribe.php',
] );
$url_inscricao = $inscricao_pages ? get_permalink( $inscricao_pages[0]->ID ) : home_url( '/inscricao/' );

$pacotes = get_posts( [
    'post_type'   => 'pacote',
    'numberposts' => -1,
    'post_status' => 'publish',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
] );

if ( empty( $pacotes ) ) {
    return;
}

// JSON-LD — ItemList > Offer (rastreável por buscadores e IAs)
$ld_offers = [];
foreach ( $pacotes as $p ) {
    $ld_offers[] = [
        '@type'         => 'Offer',
        'name'          => get_the_title( $p->ID ),
        'price'         => (float) get_field( 'pacote_preco_avista', $p->ID ),
        'priceCurrency' => 'BRL',
        'url'           => esc_url( add_query_arg( 'pkg_id', $p->ID, $url_inscricao ) ),
    ];
}
?>

<script type="application/ld+json">
<?php echo wp_json_encode( [
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => esc_html( $titulo ),
    'itemListElement' => $ld_offers,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ); ?>
</script>

<section
    id="ingressos"
    class="section-tickets"
    aria-labelledby="section-tickets-title"
    itemscope
    itemtype="https://schema.org/ItemList"
>
    <div class="container">

        <header class="section-tickets__header">
            <h2 id="section-tickets-title" itemprop="name">
                <?php echo esc_html( $titulo ); ?>
            </h2>
            <?php if ( $descricao ) : ?>
                <div class="section-tickets__desc">
                    <?php echo wp_kses_post( $descricao ); ?>
                </div>
            <?php endif; ?>
        </header>

        <ol
            class="section-tickets__grid"
            role="list"
            aria-label="<?php esc_attr_e( 'Lista de pacotes disponíveis', 'abril-pra-angola' ); ?>"
        >

            <?php foreach ( $pacotes as $pacote ) :
                $pacote_id     = $pacote->ID;
                $preco_avista  = (float) get_field( 'pacote_preco_avista', $pacote_id );
                $preco_cartao  = (float) get_field( 'pacote_preco_cartao', $pacote_id );
                $validade_br   = get_field( 'pacote_validade',             $pacote_id ); // retorna d/m/Y
                $descricao_pkg = get_field( 'pacote_descricao',            $pacote_id );
                $link_cta      = add_query_arg( 'pkg_id', $pacote_id, $url_inscricao );

                // Converte d/m/Y → Y-m-d para o atributo datetime da tag <time>
                $validade_iso = '';
                if ( $validade_br ) {
                    $dt = DateTime::createFromFormat( 'd/m/Y', $validade_br );
                    if ( $dt ) {
                        $validade_iso = $dt->format( 'Y-m-d' );
                    }
                }

                // Verifica se o pacote expirou (compara com data atual UTC)
                $expirado = $validade_iso && $validade_iso < gmdate( 'Y-m-d' );

                // Itens incluídos (um por linha no campo textarea)
                $itens_incluidos = [];
                if ( $descricao_pkg ) {
                    $itens_incluidos = array_values( array_filter( array_map( 'trim', explode( "\n", $descricao_pkg ) ) ) );
                }
            ?>

                <li
                    class="ticket-card<?php echo $expirado ? ' ticket-card--expirado' : ''; ?>"
                    itemprop="itemListElement"
                    itemscope
                    itemtype="https://schema.org/Offer"
                >
                    <meta itemprop="priceCurrency" content="BRL" />
                    <meta itemprop="price"         content="<?php echo esc_attr( $preco_avista ); ?>" />
                    <meta itemprop="availability"  content="<?php echo $expirado ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock'; ?>" />
                    <link itemprop="url"           href="<?php echo esc_url( $link_cta ); ?>" />

                    <article aria-label="<?php echo esc_attr( sprintf( __( 'Pacote %s', 'abril-pra-angola' ), get_the_title( $pacote_id ) ) ); ?>">

                        <!-- ── Header do card ── -->
                        <header class="ticket-card__header">
                            <span
                                class="ticket-card__badge ticket-card__badge--<?php echo $expirado ? 'esgotado' : 'disponivel'; ?>"
                                role="status"
                                aria-label="<?php echo $expirado ? esc_attr__( 'Encerrado', 'abril-pra-angola' ) : esc_attr__( 'Disponível', 'abril-pra-angola' ); ?>"
                            >
                                <?php echo $expirado ? esc_html__( 'Encerrado', 'abril-pra-angola' ) : esc_html__( 'Disponível', 'abril-pra-angola' ); ?>
                            </span>

                            <h3 class="ticket-card__title" itemprop="name">
                                <?php echo esc_html( get_the_title( $pacote_id ) ); ?>
                            </h3>
                        </header>

                        <!-- ── Corpo: conteúdo + itens incluídos ── -->
                        <div class="ticket-card__body">
                            <div class="content" itemprop="description">
                                <?php
                                $post_content = get_post_field( 'post_content', $pacote_id );
                                if ( $post_content ) {
                                    echo wp_kses_post( apply_filters( 'the_content', $post_content ) );
                                }
                                ?>

                                <?php if ( ! empty( $itens_incluidos ) ) : ?>
                                    <ul
                                        class="ticket-card__includes"
                                        aria-label="<?php esc_attr_e( 'O que está incluído', 'abril-pra-angola' ); ?>"
                                    >
                                        <?php foreach ( $itens_incluidos as $item ) : ?>
                                            <li>
                                                <span aria-hidden="true">✓</span>
                                                <?php echo esc_html( $item ); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- ── Rodapé: preços + validade + CTA ── -->
                        <footer class="ticket-card__footer">

                            <div
                                class="ticket-card__investment"
                                aria-label="<?php esc_attr_e( 'Investimento', 'abril-pra-angola' ); ?>"
                            >
                                <h4 class="ticket-card__investment-title">
                                    <?php esc_html_e( 'Investimento', 'abril-pra-angola' ); ?>
                                </h4>

                                <p class="ticket-card__price-avista">
                                    <span class="avista" itemprop="price">
                                        R$ <?php echo number_format( $preco_avista, 2, ',', '.' ); ?>
                                    </span>
                                </p>
                            </div>

                            <?php if ( $validade_br ) : ?>
                                <p class="ticket-card__validade">
                                    <time
                                        class="valTicket"
                                        datetime="<?php echo esc_attr( $validade_iso ); ?>"
                                        aria-label="<?php printf( esc_attr__( 'Válido até %s', 'abril-pra-angola' ), esc_attr( $validade_br ) ); ?>"
                                    >
                                        🕐 <?php printf( esc_html__( 'Válido até %s', 'abril-pra-angola' ), esc_html( $validade_br ) ); ?>
                                    </time>
                                </p>
                            <?php endif; ?>

                            <?php if ( ! $expirado ) : ?>
                                <a
                                    href="<?php echo esc_url( $link_cta ); ?>"
                                    class="btn btn-primary"
                                    itemprop="url"
                                    aria-label="<?php printf( esc_attr__( 'Garantir vaga no pacote %s', 'abril-pra-angola' ), esc_attr( get_the_title( $pacote_id ) ) ); ?>"
                                >
                                    <?php esc_html_e( 'Garantir Minha Vaga', 'abril-pra-angola' ); ?> →
                                </a>
                            <?php else : ?>
                                <span
                                    class="btn btn-outline"
                                    aria-disabled="true"
                                    role="button"
                                    tabindex="-1"
                                >
                                    <?php esc_html_e( 'Pacote Encerrado', 'abril-pra-angola' ); ?>
                                </span>
                            <?php endif; ?>

                        </footer>

                    </article>
                </li>

            <?php endforeach; ?>

        </ol>

    </div><!-- /.container -->
</section><!-- /.section-tickets -->

