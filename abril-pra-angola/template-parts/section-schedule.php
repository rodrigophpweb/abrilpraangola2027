<?php
/**
 * Template Part: Seção Agenda (Schedule)
 *
 * Exibe os itens da programação cadastrados no CPT "agenda",
 * agrupados visualmente pela taxonomia "categoria_agenda".
 * Título e descrição gerenciados via ACF Options (page-options.php).
 *
 * Marcação Schema.org Event para indexação semântica por mecanismos
 * de busca e bots de inteligência artificial.
 *
 * Segurança:
 *  - esc_html()          → strings simples (títulos, termos)
 *  - esc_attr()          → atributos HTML (datetime, aria-label)
 *  - wp_kses_post()      → conteúdo rico do editor (WYSIWYG/ACF)
 *  - esc_url()           → hrefs
 *  - apply_filters('the_content') + wp_kses_post() → conteúdo do CPT
 *
 * @package Abril_Pra_Angola
 */

// ── Opções da seção via ACF Options ──────────────────────────────────────────
$agenda_opts  = function_exists( 'abril_get_agenda_options' ) ? abril_get_agenda_options() : [];
$titulo_secao = ! empty( $agenda_opts['agenda_titulo'] )
    ? $agenda_opts['agenda_titulo']
    : __( 'Programação', 'abril-pra-angola' );
$descricao    = $agenda_opts['agenda_descricao'] ?? '';

// ── Query do CPT Agenda ───────────────────────────────────────────────────────
$agenda_query = new WP_Query( [
    'post_type'      => 'agenda',
    'posts_per_page' => 7,
    'post_status'    => 'publish',
    'orderby'        => [ 'menu_order' => 'ASC', 'date' => 'ASC' ],
    'no_found_rows'  => true,
] );
?>

<section
    id="programacao"
    class="section-schedule"
    aria-labelledby="schedule-heading"
    itemscope
    itemtype="https://schema.org/EventSeries"
>
    <div class="container">

        <!-- ── Cabeçalho da seção ─────────────────────────────────── -->
        <header class="section-schedule__header">
            <h2 id="schedule-heading" class="section-schedule__title" itemprop="name">
                <?php echo esc_html( $titulo_secao ); ?>
            </h2>

            <?php if ( $descricao ) : ?>
            <div class="section-schedule__description" itemprop="description">
                <?php echo wp_kses_post( $descricao ); ?>
            </div>
            <?php endif; ?>
        </header><!-- /.section-schedule__header -->

        <?php if ( $agenda_query->have_posts() ) : ?>

        <!-- ── Grade da Programação — 2 colunas (grid) ───────────── -->
        <div class="section-schedule__grid">

            <?php
            // Contador de posição e total de posts para a lógica dos títulos
            $agenda_index = 0;
            $agenda_total = $agenda_query->post_count;
            ?>

            <?php while ( $agenda_query->have_posts() ) : $agenda_query->the_post(); ?>
            <?php
            $agenda_index++;
            $agenda_id    = get_the_ID();

            // ── Título do card (h3) — baseado na posição no loop ─────────────────
            // 1º post  → "Abertura do Evento"
            // 2º…(n-1) → "Dia X de Atividades"
            // Último   → "Último dia de atividades"
            if ( 1 === $agenda_index ) {
                $card_title = __( 'Abertura do Evento', 'abril-pra-angola' );
            } elseif ( $agenda_index === $agenda_total ) {
                $card_title = __( 'Último dia de atividades', 'abril-pra-angola' );
            } else {
                /* translators: %d = número sequencial do dia (2, 3, 4…) */
                $card_title = sprintf( __( 'Dia %d de Atividades', 'abril-pra-angola' ), $agenda_index );
            }

            // ── Termo da taxonomia categoria_agenda ───────────────────────────────
            $terms     = get_the_terms( $agenda_id, 'categoria_agenda' );
            $term_name = ( ! is_wp_error( $terms ) && ! empty( $terms ) )
                ? $terms[0]->name
                : '';

            // ── Data ISO 8601 para os atributos datetime e content ────────────────
            // Prioridade:
            //   1. Slug do termo no formato YYYY-MM-DD  (ex.: "2027-04-29")
            //   2. Parsear a data do nome do termo (meses PT → EN para strtotime)
            //   3. Data de publicação do post como último recurso
            $event_date_iso = '';

            if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                $term_slug = $terms[0]->slug ?? '';
                if ( preg_match( '/(\d{4}-\d{2}-\d{2})/', $term_slug, $slug_match ) ) {
                    $event_date_iso = $slug_match[1];
                }
            }

            if ( empty( $event_date_iso ) && ! empty( $term_name ) ) {
                // Tabela de tradução PT → EN para que strtotime() consiga parsear
                $pt_to_en = [
                    'janeiro'   => 'January',   'fevereiro' => 'February',
                    'março'     => 'March',      'abril'     => 'April',
                    'maio'      => 'May',        'junho'     => 'June',
                    'julho'     => 'July',       'agosto'    => 'August',
                    'setembro'  => 'September',  'outubro'   => 'October',
                    'novembro'  => 'November',   'dezembro'  => 'December',
                ];
                $term_name_en = str_ireplace(
                    array_keys( $pt_to_en ),
                    array_values( $pt_to_en ),
                    $term_name
                );
                $parsed = strtotime( $term_name_en );
                if ( $parsed ) {
                    $event_date_iso = gmdate( 'Y-m-d', $parsed );
                }
            }

            // Fallback: data de publicação do post
            if ( empty( $event_date_iso ) ) {
                $event_date_iso = get_the_date( 'Y-m-d', $agenda_id );
            }

            // ── Texto visível do <time>: "Dia X - dd de Mês de YYYY" ─────────────
            // Formata $event_date_iso em português para exibição humana.
            $pt_months_label = [
                '01' => 'Janeiro',  '02' => 'Fevereiro', '03' => 'Março',
                '04' => 'Abril',    '05' => 'Maio',       '06' => 'Junho',
                '07' => 'Julho',    '08' => 'Agosto',     '09' => 'Setembro',
                '10' => 'Outubro',  '11' => 'Novembro',   '12' => 'Dezembro',
            ];

            $date_parts = explode( '-', $event_date_iso ); // ['2027', '04', '29']
            if ( 3 === count( $date_parts ) ) {
                $day_num    = ltrim( $date_parts[2], '0' );              // '29'
                $month_name = $pt_months_label[ $date_parts[1] ] ?? ''; // 'Abril'
                $year_num   = $date_parts[0];                            // '2027'
                /* translators: 1: número do dia no loop, 2: dia, 3: mês, 4: ano */
                $time_label = sprintf(
                    __( 'Dia %1$d - %2$s de %3$s de %4$s', 'abril-pra-angola' ),
                    $agenda_index,
                    $day_num,
                    $month_name,
                    $year_num
                );
            } else {
                /* translators: %d = número do dia */
                $time_label = sprintf( __( 'Dia %d', 'abril-pra-angola' ), $agenda_index );
            }
            ?>

            <article
                class="schedule-card"
                itemscope
                itemtype="https://schema.org/Event"
            >
                <!-- ── Cabeçalho do card ──────────────────────── -->
                <header class="schedule-card__header">
                    <!--
                        <time> exibe somente o nome do termo (ex.: "Dia 1 — Quinta-Feira: 29 de Abril 2027").
                        datetime e content recebem a data ISO 8601 real do evento,
                        extraída do slug do termo, do nome do termo ou da data de publicação.
                    -->
                    <time
                        class="schedule-card__time"
                        datetime="<?php echo esc_attr( $event_date_iso ); ?>"
                        itemprop="startDate"
                        content="<?php echo esc_attr( $event_date_iso ); ?>"
                    >
                        <?php echo esc_html( $time_label ); ?>
                    </time>
                    <h3 class="schedule-card__title" itemprop="name">
                        <?php echo esc_html( $card_title ); ?>
                    </h3>
                </header><!-- /.schedule-card__header -->

                <!-- ── Conteúdo do post: ul + links ───────────── -->
                <div class="schedule-card__content" itemprop="description">
                    <?php
                    /*
                     * Renderiza o conteúdo do editor (lista não ordenada e links).
                     * apply_filters( 'the_content' ) executa todos os filtros padrão do WordPress,
                     * incluindo autop, shortcodes e embeds.
                     * wp_kses_post() adiciona camada explícita de sanitização XSS,
                     * mantendo apenas tags e atributos HTML permitidos para posts.
                     */
                    echo wp_kses_post( apply_filters( 'the_content', get_the_content() ) );
                    ?>
                </div><!-- /.schedule-card__content -->

                <!-- ── CTA ─────────────────────────────────────── -->
                <footer class="schedule-card__footer">
                    <a
                        href="<?php echo esc_url( home_url( '/#ingressos' ) ); ?>"
                        class="btn btn-primary"
                        aria-label="<?php printf(
                            /* translators: %s = título do card (ex.: "Abertura do Evento") */
                            esc_attr__( 'Inscreva-se para participar: %s', 'abril-pra-angola' ),
                            esc_attr( $card_title )
                        ); ?>"
                    >
                        <?php esc_html_e( 'Inscreva-se', 'abril-pra-angola' ); ?>
                    </a>
                </footer><!-- /.schedule-card__footer -->

            </article><!-- /.schedule-card -->

            <?php endwhile; wp_reset_postdata(); ?>

        </div><!-- /.section-schedule__grid -->

        <?php else : ?>

        <!-- ── Estado vazio ───────────────────────────────────── -->
        <div class="section-schedule__empty" role="status" aria-live="polite">
            <p class="section-schedule__empty-icon" aria-hidden="true">
                <i class="fa-regular fa-calendar-xmark"></i>
            </p>
            <p class="section-schedule__empty-text">
                <?php esc_html_e( 'A programação desta edição será divulgada em breve. Fique atento!', 'abril-pra-angola' ); ?>
            </p>
        </div><!-- /.section-schedule__empty -->

        <?php endif; ?>

    </div><!-- /.container -->
</section><!-- /.section-schedule -->

