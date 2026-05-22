<?php
/**
 * Template Name: Minha Inscrição
 * Área pessoal do inscrito — requer login.
 */
get_header();

// Redirecionar para login se não estiver autenticado
if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

$user_id = get_current_user_id();

// Buscar todas as inscrições do utilizador actual
$inscricoes = get_posts( [
    'post_type'      => 'inscrito',
    'posts_per_page' => -1,
    'meta_query'     => [ [
        'key'   => 'inscrito_user_id',
        'value' => $user_id,
    ] ],
    'orderby' => 'date',
    'order'   => 'DESC',
] );

$cores_status = [
    'pendente'   => '#f0ad4e',
    'confirmado' => '#5cb85c',
    'cancelado'  => '#d9534f',
];
$icones_status = [
    'pendente'   => '⏳',
    'confirmado' => '✅',
    'cancelado'  => '❌',
];
?>

<main id="main" class="page-minha-inscricao">
    <div class="container">

        <header class="page-minha-inscricao__header">
            <h1>👤 Minha Área</h1>
            <p>Olá, <strong><?php echo esc_html( wp_get_current_user()->display_name ); ?></strong>!
               Aqui podes acompanhar todas as tuas inscrições.</p>
        </header>

        <?php if ( empty( $inscricoes ) ) : ?>

            <div class="alert alert--info">
                <strong>Nenhuma inscrição encontrada.</strong><br>
                Ainda não tens inscrições. <a href="<?php echo home_url( '/inscricao/' ); ?>">Inscreve-te agora →</a>
            </div>

        <?php else : ?>

            <?php foreach ( $inscricoes as $inscricao ) :
                $status          = get_field( 'inscrito_status',          $inscricao->ID ) ?: 'pendente';
                $pacote_id       = get_field( 'inscrito_pacote_id',       $inscricao->ID );
                $pacote          = $pacote_id ? get_post( $pacote_id ) : null;
                $preco           = $pacote_id ? get_field( 'pacote_preco', $pacote_id ) : null;
                $forma_pag       = get_field( 'inscrito_forma_pagamento', $inscricao->ID );
                $data_pag        = get_field( 'inscrito_data_pagamento',  $inscricao->ID );
                $camiseta        = get_field( 'inscrito_camiseta',        $inscricao->ID );
                $transporte      = get_field( 'inscrito_transporte',      $inscricao->ID );
                $comprovante     = get_field( 'inscrito_comprovante',     $inscricao->ID );
                $token           = get_post_meta( $inscricao->ID, '_inscrito_token', true );
                $token_usado     = get_post_meta( $inscricao->ID, '_inscrito_token_usado', true );
                $cor_status      = $cores_status[ $status ] ?? '#999';
                $icone_status    = $icones_status[ $status ] ?? '•';

                // Buscar edição do evento
                $edicoes = wp_get_object_terms( $inscricao->ID, 'edicao_evento' );
                $edicao  = ! empty( $edicoes ) ? $edicoes[0]->name : '—';
            ?>

            <div class="card-inscricao">

                <!-- Cabeçalho do card -->
                <div class="card-inscricao__header">
                    <div>
                        <h2>🎟️ Abril pra Angola <?php echo esc_html( $edicao ); ?></h2>
                        <?php if ( $pacote ) : ?>
                            <p class="card-inscricao__pacote">
                                <?php echo esc_html( $pacote->post_title ); ?>
                                <?php if ( $preco ) : ?>
                                    — R$ <?php echo number_format( (float) $preco, 2, ',', '.' ); ?>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <span class="badge-status" style="background: <?php echo $cor_status; ?>">
                        <?php echo $icone_status . ' ' . strtoupper( $status ); ?>
                    </span>
                </div>

                <!-- Dados pessoais / evento -->
                <div class="card-inscricao__body">
                    <div class="card-inscricao__grid">
                        <div>
                            <strong>Tamanho da Camiseta</strong>
                            <span><?php echo esc_html( $camiseta ?: '—' ); ?></span>
                        </div>
                        <div>
                            <strong>Forma de Pagamento</strong>
                            <span><?php echo $forma_pag === 'cartao' ? '💳 Cartão (Mercado Pago)' : '🏦 Depósito'; ?></span>
                        </div>
                        <div>
                            <strong>Data do Pagamento</strong>
                            <span><?php echo esc_html( $data_pag ?: '—' ); ?></span>
                        </div>
                        <div>
                            <strong>Transporte (R$ 70,00)</strong>
                            <span><?php echo $transporte ? '✅ Sim' : '❌ Não'; ?></span>
                        </div>
                    </div>

                    <!-- Comprovante -->
                    <div class="card-inscricao__comprovante">
                        <?php if ( $comprovante ) : ?>
                            <p>📎 <strong>Comprovante enviado</strong>
                            <?php if ( $status === 'pendente' ) : ?>
                                — aguardando validação pela organização.
                            <?php endif; ?>
                            </p>
                        <?php elseif ( $status !== 'confirmado' ) : ?>
                            <div class="alert alert--aviso">
                                ⚠️ <strong>Comprovante pendente!</strong>
                                Envie o comprovante de pagamento para confirmarmos a sua vaga.<br><br>
                                <?php if ( $token && ! $token_usado ) : ?>
                                    <a href="<?php echo esc_url( add_query_arg( 'token', $token, home_url( '/enviar-comprovante/' ) ) ); ?>"
                                       class="btn btn-primary">
                                        📤 Enviar Comprovante Agora
                                    </a>
                                <?php else : ?>
                                    <em>Entre em contato com a organização para obter um novo link de envio.</em>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div><!-- .card-inscricao__body -->

            </div><!-- .card-inscricao -->

            <?php endforeach; ?>

            <div class="minha-inscricao__nova">
                <a href="<?php echo home_url( '/inscricao/' ); ?>" class="btn btn-secondary">
                    + Inscrever-me noutra edição
                </a>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>

