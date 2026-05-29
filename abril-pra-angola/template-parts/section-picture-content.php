<?php
/**
 * Template Part: Seção Picture + Content
 *
 * Exibe uma seção dividida em duas colunas: imagem e conteúdo textual.
 * O layout e os dados são determinados pelo argumento 'variant':
 *
 *   - 'responsavel' → Responsável do Evento  (imagem à esquerda, texto à direita)
 *   - 'atividades'  → Atividades do Evento   (texto à esquerda, imagem à direita — reverse)
 *
 * Marcação Schema.org e metadados semânticos para indexação por mecanismos de
 * busca e bots de IA (Google, Bing, ChatGPT, Perplexity, etc.).
 *
 * @var array $args Argumentos passados via get_template_part( ..., null, $args ).
 */

// ── Variante e dados ──────────────────────────────────────────────────────────
$variant = $args['variant'] ?? 'responsavel';
$options = function_exists( 'abril_get_home_options' ) ? abril_get_home_options() : [];

if ( 'responsavel' === $variant ) {
    $foto        = $options['foto_responsavel']       ?? null;
    $titulo      = $options['titulo_responsavel']     ?? '';
    $subtitulo   = $options['subtitulo_responsavel']  ?? '';
    $descricao   = $options['descricao_responsavel']  ?? '';
    $section_id  = 'responsavel-evento';
    $heading_id  = 'responsavel-evento-heading';
    $schema_type = 'https://schema.org/Person';
    $modifier    = '';
    $img_alt_fallback = __( 'Responsável do Evento', 'abril-pra-angola' );
} else {
    $foto        = $options['foto_atividades']        ?? null;
    $titulo      = $options['titulo_atividades']      ?? '';
    $subtitulo   = $options['subtitulo_atividades']   ?? '';
    $descricao   = $options['descricao_atividades']   ?? '';
    $section_id  = 'atividades-evento';
    $heading_id  = 'atividades-evento-heading';
    $schema_type = 'https://schema.org/Event';
    $modifier    = ' section-picture-content--reverse';
    $img_alt_fallback = __( 'Atividades do Evento', 'abril-pra-angola' );
}

// Bail out silently se não houver conteúdo mínimo cadastrado
if ( empty( $titulo ) && empty( $foto ) ) {
    return;
}

// ── Preparar dados da imagem ──────────────────────────────────────────────────
$img_src    = '';
$img_alt    = $img_alt_fallback;
$img_width  = '';
$img_height = '';

if ( ! empty( $foto ) && is_array( $foto ) ) {
    $img_src    = esc_url( $foto['url']    ?? '' );
    $img_alt    = esc_attr( ! empty( $foto['alt'] ) ? $foto['alt'] : ( $titulo ?: $img_alt_fallback ) );
    $img_width  = ! empty( $foto['width'] )  ? (int) $foto['width']  : '';
    $img_height = ! empty( $foto['height'] ) ? (int) $foto['height'] : '';
} elseif ( ! empty( $foto ) && is_string( $foto ) ) {
    $img_src = esc_url( $foto );
    $img_alt = esc_attr( $titulo ?: $img_alt_fallback );
}
?>

<section
    id="<?php echo esc_attr( $section_id ); ?>"
    class="section-picture-content<?php echo esc_attr( $modifier ); ?>"
    aria-labelledby="<?php echo esc_attr( $heading_id ); ?>"
    itemscope
    itemtype="<?php echo esc_attr( $schema_type ); ?>"
>

    <!-- ── Coluna: Imagem ──────────────────────────────────────────────── -->
    <figure class="section-picture-content__media" role="img" aria-label="<?php echo esc_attr( $img_alt ); ?>">
        <?php if ( $img_src ) : ?>
            <img
                src="<?php echo $img_src; ?>"
                alt="<?php echo $img_alt; ?>"
                <?php if ( $img_width )  : ?>width="<?php echo esc_attr( $img_width ); ?>"<?php endif; ?>
                <?php if ( $img_height ) : ?>height="<?php echo esc_attr( $img_height ); ?>"<?php endif; ?>
                loading="lazy"
                decoding="async"
                itemprop="image"
            >
        <?php endif; ?>
    </figure><!-- /.section-picture-content__media -->

    <!-- ── Coluna: Conteúdo ────────────────────────────────────────────── -->
    <article class="section-picture-content__article">
        <div class="section-picture-content__content">

            <?php if ( $titulo ) : ?>
                <h2
                    id="<?php echo esc_attr( $heading_id ); ?>"
                    class="section-picture-content__title"
                    itemprop="name"
                >
                    <?php echo esc_html( $titulo ); ?>
                </h2>
            <?php endif; ?>

            <?php if ( $subtitulo ) : ?>
                <p class="section-picture-content__subtitle">
                    <?php echo esc_html( $subtitulo ); ?>
                </p>
            <?php endif; ?>

            <?php if ( $descricao ) : ?>
                <div class="section-picture-content__description" itemprop="description">
                    <?php echo nl2br( esc_html( $descricao ) ); ?>
                </div>
            <?php endif; ?>

        </div>
    </article><!-- /.section-picture-content__article -->

</section><!-- /.section-picture-content -->
