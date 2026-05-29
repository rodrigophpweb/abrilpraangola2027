<?php
/**
 * Template Part: Seção Localização do Evento
 *
 * Exibe o local, endereço e mapa do evento.
 * Dados vindos das opções de tema via ACF Options Page.
 * HTML semântico com Schema.org para indexação por mecanismos
 * de busca e bots de inteligência artificial.
 *
 * @package Abril_Pra_Angola
 */

$event_opts   = function_exists( 'abril_get_event_options' ) ? abril_get_event_options() : [];
$local_evento = $event_opts['local_evento']    ?? '';
$endereco     = $event_opts['endereco_evento'] ?? '';
$maps_url     = $event_opts['url_google_maps'] ?? '';

if ( empty( $local_evento ) && empty( $endereco ) && empty( $maps_url ) ) {
    return;
}
?>

<section
    id="localizacao"
    class="section-location"
    aria-labelledby="location-heading"
    itemscope
    itemtype="https://schema.org/Event"
>
    <div class="container">

        <header class="section-location__header">

            <h2 id="location-heading" class="section-location__title">
                <?php esc_html_e( 'Local do Evento', 'abril-pra-angola' ); ?>
            </h2>

            <?php if ( $local_evento || $endereco ) : ?>
            <address
                class="section-location__address"
                itemprop="location"
                itemscope
                itemtype="https://schema.org/Place"
            >
                <?php if ( $local_evento ) : ?>
                <span class="section-location__venue" itemprop="name">
                    <?php echo esc_html( $local_evento ); ?>
                </span>
                <?php endif; ?>

                <?php if ( $endereco ) : ?>
                <span
                    class="section-location__street"
                    itemprop="address"
                    itemscope
                    itemtype="https://schema.org/PostalAddress"
                >
                    <span itemprop="streetAddress"><?php echo esc_html( $endereco ); ?></span>
                </span>
                <?php endif; ?>
            </address>
            <?php endif; ?>

        </header><!-- /.section-location__header -->

    </div><!-- /.container -->

    <?php if ( $maps_url ) : ?>
    <div
        class="section-location__map"
        role="region"
        aria-label="<?php esc_attr_e( 'Mapa de localização do evento no Google Maps', 'abril-pra-angola' ); ?>"
    >
        <iframe
            class="section-location__iframe"
            src="<?php echo esc_url( $maps_url ); ?>"
            title="<?php printf(
                /* translators: %s: nome do local do evento */
                esc_attr__( 'Mapa de localização: %s', 'abril-pra-angola' ),
                esc_attr( $local_evento ?: __( 'Local do Evento', 'abril-pra-angola' ) )
            ); ?>"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
        ></iframe>
    </div><!-- /.section-location__map -->
    <?php endif; ?>

</section><!-- /.section-location -->
