<?php
/**
 * Template Part: Section About General — Resumo do Evento
 *
 * Campos da página de opções (ACF):
 *  - edicao_evento       → h2
 *  - introducao_evento   → p
 *  - local_evento        → address > h3
 *  - data_inicio_evento  → time.dayWeek / time.dayEvent
 *  - data_final_evento   → time.dayWeek / time.dayEvent
 *
 * @package Abril_pra_Angola
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ── Dados do evento ─────────────────────────────── */
$opts        = function_exists( 'abril_get_event_options' ) ? abril_get_event_options() : [];
$edicao      = sanitize_text_field( $opts['edicao_evento']      ?? '' );
$introducao  = wp_kses_post( $opts['introducao_evento']         ?? '' );
$local       = sanitize_text_field( $opts['local_evento']       ?? '' );
$data_inicio = $opts['data_inicio_evento'] ?? ''; // Y-m-d (retorno ACF)
$data_final  = $opts['data_final_evento']  ?? ''; // Y-m-d (retorno ACF)

/* ── Nomes em português ──────────────────────────── */
$dias_semana = [
    1 => 'Segunda',
    2 => 'Terça',
    3 => 'Quarta',
    4 => 'Quinta',
    5 => 'Sexta',
    6 => 'Sábado',
    7 => 'Domingo',
];

$meses = [
    1  => 'Janeiro',
    2  => 'Fevereiro',
    3  => 'Março',
    4  => 'Abril',
    5  => 'Maio',
    6  => 'Junho',
    7  => 'Julho',
    8  => 'Agosto',
    9  => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro',
];

/* ── Formatação das datas ────────────────────────── */
$day_week_text   = '';
$day_event_text  = '';
$datetime_inicio = '';
$datetime_final  = '';
$ts_inicio       = $data_inicio ? strtotime( $data_inicio ) : false;
$ts_final        = $data_final  ? strtotime( $data_final )  : false;

if ( $ts_inicio && $ts_final ) {
    $datetime_inicio = gmdate( 'Y-m-d', $ts_inicio );
    $datetime_final  = gmdate( 'Y-m-d', $ts_final );

    /* dayWeek — ex.: "Quinta à Domingo" */
    $dow_inicio    = (int) gmdate( 'N', $ts_inicio );
    $dow_final     = (int) gmdate( 'N', $ts_final );
    $day_week_text = ( $dias_semana[ $dow_inicio ] ?? '' ) . ' à ' . ( $dias_semana[ $dow_final ] ?? '' );

    /* dayEvent — ex.: "29 de Abril à 02 de Maio de 2027" */
    $d_i = (int) gmdate( 'j', $ts_inicio );
    $m_i = (int) gmdate( 'n', $ts_inicio );
    $d_f = (int) gmdate( 'j', $ts_final );
    $m_f = (int) gmdate( 'n', $ts_final );
    $y_f = (int) gmdate( 'Y', $ts_final );

    if ( $m_i === $m_f ) {
        $day_event_text = sprintf(
            '%d à %02d de %s de %d',
            $d_i, $d_f, $meses[ $m_f ] ?? '', $y_f
        );
    } else {
        $day_event_text = sprintf(
            '%d de %s à %02d de %s de %d',
            $d_i, $meses[ $m_i ] ?? '', $d_f, $meses[ $m_f ] ?? '', $y_f
        );
    }
}

$datetime_range  = ( $datetime_inicio && $datetime_final )
    ? esc_attr( $datetime_inicio . '/' . $datetime_final )
    : '';
$oficineiros_url = esc_url( home_url( '/nossos-oficineiros/' ) );

/* ── Schema.org JSON-LD (Event) ─────────────────── */
$schema = [
    '@context'            => 'https://schema.org',
    '@type'               => 'Event',
    'name'                => $edicao,
    'description'         => wp_strip_all_tags( $introducao ),
    'eventStatus'         => 'https://schema.org/EventScheduled',
    'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
    'url'                 => esc_url( home_url( '/' ) ),
];

if ( $datetime_inicio ) {
    $schema['startDate'] = $datetime_inicio;
}
if ( $datetime_final ) {
    $schema['endDate'] = $datetime_final;
}
if ( $local ) {
    $schema['location'] = [ '@type' => 'Place', 'name' => $local ];
    $endereco = sanitize_text_field( $opts['endereco_evento'] ?? '' );
    if ( $endereco ) {
        $schema['location']['address'] = [
            '@type'          => 'PostalAddress',
            'streetAddress'  => $endereco,
            'addressCountry' => 'BR',
        ];
    }
}
?>

<script type="application/ld+json">
<?php echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?>
</script>

<section
    id="sobre"
    class="about-general"
    aria-label="<?php esc_attr_e( 'Resumo do Evento', 'abril-pra-angola' ); ?>"
    itemscope
    itemtype="https://schema.org/Event"
>
    <article class="container">
        <header class="about-general__inner">

            <?php if ( $edicao ) : ?>
                <h2 itemprop="name"><?php echo esc_html( $edicao ); ?></h2>
            <?php endif; ?>

            <?php if ( $introducao ) : ?>
                <div class="about-general__desc" itemprop="description">
                    <?php echo $introducao; ?>
                </div>
            <?php endif; ?>

            <address
                class="about-general__location"
                itemprop="location"
                itemscope
                itemtype="https://schema.org/Place"
            >
                <span class="about-general__label"><?php esc_html_e( 'Local', 'abril-pra-angola' ); ?></span>
                <?php if ( $local ) : ?>
                    <h3 itemprop="name"><?php echo esc_html( $local ); ?></h3>
                <?php endif; ?>
                <?php
                $endereco = sanitize_text_field( $opts['endereco_evento'] ?? '' );
                if ( $endereco ) : ?>
                    <span
                        class="about-general__address"
                        itemprop="address"
                        itemscope
                        itemtype="https://schema.org/PostalAddress"
                    >
                        <span itemprop="streetAddress"><?php echo esc_html( $endereco ); ?></span>
                    </span>
                <?php endif; ?>
            </address>

            <div
                class="about-general__when when"
                aria-label="<?php esc_attr_e( 'Data do evento', 'abril-pra-angola' ); ?>"
            >
                <span class="about-general__label"><?php esc_html_e( 'Quando', 'abril-pra-angola' ); ?></span>

                <?php if ( $day_week_text ) : ?>
                    <time
                        class="dayWeek"
                        datetime="<?php echo $datetime_range; ?>"
                    ><?php echo esc_html( $day_week_text ); ?></time>
                <?php endif; ?>

                <?php if ( $day_event_text ) : ?>
                    <time
                        class="dayEvent"
                        datetime="<?php echo $datetime_range; ?>"
                        itemprop="startDate"
                        content="<?php echo esc_attr( $datetime_inicio ); ?>"
                    ><?php echo esc_html( $day_event_text ); ?></time>
                <?php endif; ?>
            </div>

            <a
                href="<?php echo $oficineiros_url; ?>"
                class="btn btn-primary about-general__cta"
                aria-label="<?php esc_attr_e( 'Conheça nossos oficineiros', 'abril-pra-angola' ); ?>"
            ><?php esc_html_e( 'Oficineiros', 'abril-pra-angola' ); ?></a>

        </header><!-- .about-general__inner -->
    </article><!-- .container -->

</section>

