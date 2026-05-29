<?php
/**
 * Template Part: Seção Inscreva-se
 *
 * Campos ACF utilizados:
 *  - subscribe_titulo    (text)
 *  - subscribe_descricao (textarea)
 */

if ( ! function_exists( 'get_field' ) ) {
    return;
}

$home_opts = function_exists( 'abril_get_home_options' ) ? abril_get_home_options() : [];

$titulo    = ! empty( $home_opts['subscribe_titulo'] )    ? $home_opts['subscribe_titulo']    : '';
$descricao = ! empty( $home_opts['subscribe_descricao'] ) ? $home_opts['subscribe_descricao'] : '';
$cta_url   = home_url( '/inscricao/' );

if ( empty( $titulo ) && empty( $descricao ) ) {
    return;
}
?>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Event",
    "name": "<?php echo esc_js( $titulo ); ?>",
    "description": "<?php echo esc_js( $descricao ); ?>",
    "url": "<?php echo esc_js( $cta_url ); ?>"
}
</script>

<section
    id="inscricao"
    class="section-subscribe"
    aria-labelledby="section-subscribe-title"
    itemscope
    itemtype="https://schema.org/Event"
>
    <meta itemprop="name"        content="<?php echo esc_attr( $titulo ); ?>">
    <meta itemprop="description" content="<?php echo esc_attr( $descricao ); ?>">
    <meta itemprop="url"         content="<?php echo esc_attr( $cta_url ); ?>">

    <div class="container">
        <div class="section-subscribe__inner">

            <h2 id="section-subscribe-title"><?php echo esc_html( $titulo ); ?></h2>

            <p><?php echo esc_html( $descricao ); ?></p>

            <a
                href="<?php echo esc_url( $cta_url ); ?>"
                class="btn btn-primary"
                aria-label="<?php esc_attr_e( 'Inscreva-se no evento', 'abril-pra-angola' ); ?>"
            >
                <?php esc_html_e( 'Inscreva-se', 'abril-pra-angola' ); ?>
            </a>

        </div><!-- /.section-subscribe__inner -->
    </div><!-- /.container -->
</section><!-- /.section-subscribe -->
