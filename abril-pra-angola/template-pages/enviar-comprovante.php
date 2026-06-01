<?php
/**
 * Template Name: Enviar Comprovante
 * Página de envio do comprovante de pagamento via link único.
 */
get_header();

// ── Processar upload do comprovante ──────────────────────────
$mensagem_status = '';
$inscrito        = null;
$token           = sanitize_text_field( $_GET['token'] ?? '' );

if ( empty( $token ) ) {
    $mensagem_status = 'erro_token';
} else {
    // Buscar inscrito pelo token
    $query = new WP_Query( [
        'post_type'      => 'inscrito',
        'posts_per_page' => 1,
        'meta_query'     => [ [
            'key'   => '_inscrito_token',
            'value' => $token,
        ] ],
    ] );

    if ( $query->have_posts() ) {
        $inscrito = $query->posts[0];
    } else {
        $mensagem_status = 'erro_token';
    }
}

// ── Processar o formulário de upload ─────────────────────────
if (
    $inscrito &&
    isset( $_POST['abril_comprovante_nonce'] ) &&
    wp_verify_nonce( $_POST['abril_comprovante_nonce'], 'abril_comprovante_upload' )
) {
    $post_id        = $inscrito->ID;
    $data_pagamento = sanitize_text_field( $_POST['data_pagamento'] ?? '' );

    if ( ! empty( $_FILES['comprovante']['name'] ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        // Elevar privilégios temporariamente para permitir upload sem login
        $usuario_atual = get_current_user_id();
        wp_set_current_user( 1 );

        $attachment_id = media_handle_upload( 'comprovante', $post_id );

        // Restaurar utilizador original
        wp_set_current_user( $usuario_atual );

        if ( is_wp_error( $attachment_id ) ) {
            $mensagem_status = 'erro_upload';
        } else {
            // Guardar comprovante via ACF
            update_field( 'field_inscrito_comprovante', $attachment_id, $post_id );

            // Guardar data do pagamento se fornecida
            if ( $data_pagamento ) {
                update_field( 'field_inscrito_data_pagamento', $data_pagamento, $post_id );
            }

            // Marcar token como usado (invalida o link após o envio)
            update_post_meta( $post_id, '_inscrito_token_usado', 1 );
            update_post_meta( $post_id, '_inscrito_comprovante_data_envio', current_time( 'mysql' ) );

            // EMAIL 2: Agradecimento + credenciais de acesso
            abril_email_comprovante_recebido( $post_id );

            $mensagem_status = 'sucesso';
        }
    } else {
        $mensagem_status = 'erro_ficheiro';
    }
}

// Verificar se o token já foi usado
$token_usado = $inscrito ? get_post_meta( $inscrito->ID, '_inscrito_token_usado', true ) : false;
$status_inscricao = $inscrito ? get_field( 'inscrito_status', $inscrito->ID ) : '';
?>

<main id="main" class="page-comprovante">
    <div class="container">

        <header class="page-comprovante__header">
            <h1>📎 Envio do Comprovante</h1>
            <p>Abril pra Angola 2027</p>
        </header>

        <?php if ( $mensagem_status === 'erro_token' ) : ?>

            <div class="alert alert--erro">
                <strong>⚠️ Link inválido ou expirado.</strong><br>
                Verifique se copiou o link correctamente do e-mail recebido.
                Se o problema persistir, entre em contato com a organização.
            </div>

        <?php elseif ( $mensagem_status === 'sucesso' ) : ?>

            <div class="alert alert--sucesso">
                <strong>✅ Comprovante enviado com sucesso!</strong><br>
                Recebemos o seu comprovante e iremos validar em breve.
                Assim que confirmarmos, enviaremos um e-mail com a sua inscrição confirmada. 🎉
            </div>

        <?php elseif ( $mensagem_status === 'erro_upload' ) : ?>

            <div class="alert alert--erro">
                <strong>⚠️ Erro ao enviar o ficheiro.</strong><br>
                Verifique se o ficheiro é JPG, PNG ou PDF e tem menos de 10MB.
            </div>

        <?php elseif ( $mensagem_status === 'erro_ficheiro' ) : ?>

            <div class="alert alert--erro">
                <strong>⚠️ Nenhum ficheiro seleccionado.</strong><br>
                Por favor, selecione o comprovante antes de enviar.
            </div>

        <?php endif; ?>

        <?php if ( $inscrito && $mensagem_status !== 'sucesso' ) : ?>

            <?php if ( $token_usado || $status_inscricao === 'confirmado' ) : ?>

                <div class="alert alert--info">
                    <?php if ( $status_inscricao === 'confirmado' ) : ?>
                        <strong>🎊 A sua inscrição já está confirmada!</strong><br>
                        Não é necessário enviar o comprovante novamente.
                    <?php else : ?>
                        <strong>✅ Comprovante já recebido!</strong><br>
                        Já enviou o seu comprovante anteriormente. Aguarde a confirmação por e-mail.
                    <?php endif; ?>
                </div>

            <?php else : ?>

                <!-- Dados do inscrito -->
                <div class="comprovante-info">
                    <p>
                        Olá, <strong><?php echo esc_html( get_the_title( $inscrito->ID ) ); ?></strong>! 👋<br>
                        Envie abaixo o comprovante do pagamento para confirmarmos a sua vaga.
                    </p>
                    <?php
                    $pacote_id        = get_field( 'inscrito_pacote_id',       $inscrito->ID );
                    $forma_pagamento  = get_field( 'inscrito_forma_pagamento',  $inscrito->ID );
                    $tem_transporte   = get_field( 'inscrito_transporte',       $inscrito->ID );
                    $valor_total_salvo = get_field( 'inscrito_valor_total',     $inscrito->ID );
                    $pacote           = $pacote_id ? get_post( $pacote_id ) : null;

                    // Determinar preço correto pelo método de pagamento
                    if ( $pacote_id ) {
                        if ( $forma_pagamento === 'cartao' ) {
                            $preco_pacote = (float) get_field( 'pacote_preco_cartao', $pacote_id );
                            $label_preco  = 'Cartão de Crédito';
                        } else {
                            $preco_pacote = (float) get_field( 'pacote_preco_avista', $pacote_id );
                            $label_preco  = ( $forma_pagamento === 'pix' ) ? 'PIX' : 'Depósito Bancário';
                        }
                    } else {
                        $preco_pacote = 0;
                        $label_preco  = '—';
                    }

                    $preco_transporte = $tem_transporte ? 70 : 0;
                    $total_calculado  = $valor_total_salvo > 0
                                        ? (float) $valor_total_salvo
                                        : ( $preco_pacote + $preco_transporte );

                    if ( $pacote ) : ?>
                        <p>
                            🎟️ Pacote: <strong><?php echo esc_html( $pacote->post_title ); ?></strong>
                            <?php if ( $preco_pacote ) : ?>
                                — <?php echo 'R$ ' . number_format( $preco_pacote, 2, ',', '.' ); ?>
                                <small>(<?php echo esc_html( $label_preco ); ?>)</small>
                            <?php endif; ?>
                        </p>
                        <?php if ( $tem_transporte ) : ?>
                            <p>🚌 Transporte até a chácara — <strong>R$ 70,00</strong></p>
                        <?php endif; ?>
                        <?php if ( $total_calculado > 0 ) : ?>
                            <p class="comprovante-info__total">
                                💰 <strong>Total a pagar: R$ <?php echo number_format( $total_calculado, 2, ',', '.' ); ?></strong>
                                <?php if ( $forma_pagamento === 'cartao' ) : ?>
                                    <small>— pode parcelar em até 2x no cartão</small>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Formulário de upload -->
                <form
                    class="form-comprovante"
                    method="POST"
                    action="<?php echo esc_url( add_query_arg( 'token', $token ) ); ?>"
                    enctype="multipart/form-data"
                >
                    <?php wp_nonce_field( 'abril_comprovante_upload', 'abril_comprovante_nonce' ); ?>

                    <div class="form-group">
                        <label for="data_pagamento">📅 Data do Pagamento</label>
                        <input
                            type="date"
                            id="data_pagamento"
                            name="data_pagamento"
                            value="<?php echo esc_attr( get_field( 'inscrito_data_pagamento', $inscrito->ID ) ); ?>"
                        />
                    </div>

                    <div class="form-group">
                        <label for="comprovante">📎 Comprovante de Pagamento *</label>
                        <input
                            type="file"
                            id="comprovante"
                            name="comprovante"
                            accept=".jpg,.jpeg,.png,.pdf"
                            required
                        />
                        <p class="description">Formatos aceites: JPG, PNG, PDF. Máx. 10MB.</p>
                    </div>

                    <div class="form-comprovante__submit">
                        <button type="submit" class="btn btn-primary">
                            Enviar Comprovante 📤
                        </button>
                    </div>
                </form>

            <?php endif; ?>

        <?php elseif ( ! $inscrito && $mensagem_status !== 'erro_token' ) : ?>

            <div class="alert alert--erro">
                <strong>Link inválido.</strong> Por favor verifique o e-mail recebido.
            </div>

        <?php endif; ?>

    </div>
    <?php
        get_template_part( 'template-parts/section-schedule' );
        get_template_part( 'template-parts/section', 'speakers' );
        get_template_part( 'template-parts/section-location' );
        get_template_part( 'template-parts/section', 'sponsors' );
        get_template_part( 'template-parts/faq' );
    ?>
</main>

<?php get_footer(); ?>

