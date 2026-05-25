<?php
/**
 * Form Handler - Inscrição Abril pra Angola
 */

// ─────────────────────────────────────────────────
// PROCESSAR SUBMISSÃO DO FORMULÁRIO
// ─────────────────────────────────────────────────
function abril_processar_inscricao() {
    if ( ! isset( $_POST['abril_inscricao_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['abril_inscricao_nonce'], 'abril_inscricao_submit' ) ) {
        wp_die( __( 'Verificação de segurança falhou. Tente novamente.', 'abril-pra-angola' ) );
    }

    $nome             = sanitize_text_field( $_POST['nome_completo'] ?? '' );
    $email            = sanitize_email( $_POST['email'] ?? '' );
    $celular          = sanitize_text_field( $_POST['celular'] ?? '' );
    $associacao       = sanitize_text_field( $_POST['associacao'] ?? '' );
    $apelido          = sanitize_text_field( $_POST['apelido'] ?? '' );
    $graduacao        = sanitize_text_field( $_POST['graduacao'] ?? '' );
    $camiseta         = sanitize_text_field( $_POST['camiseta'] ?? '' );
    $alergia_alimento = sanitize_text_field( $_POST['alergia_alimento'] ?? 'nao' );
    $alergia_alimento_desc = sanitize_text_field( $_POST['alergia_alimento_desc'] ?? '' );
    $alergia_remedio  = sanitize_text_field( $_POST['alergia_remedio'] ?? 'nao' );
    $alergia_remedio_desc  = sanitize_text_field( $_POST['alergia_remedio_desc'] ?? '' );
    $pacote_id        = intval( $_POST['pacote_id'] ?? 0 );
    $forma_pagamento  = sanitize_text_field( $_POST['forma_pagamento'] ?? '' );
    $data_pagamento   = sanitize_text_field( $_POST['data_pagamento'] ?? '' );
    $transporte       = isset( $_POST['transporte'] ) ? 1 : 0;
    $termo            = isset( $_POST['termo'] );

    $erros = [];
    if ( empty( $nome ) )            $erros[] = 'Nome completo é obrigatório.';
    if ( empty( $email ) )           $erros[] = 'E-mail é obrigatório.';
    if ( ! is_email( $email ) )      $erros[] = 'E-mail inválido.';
    if ( empty( $celular ) )         $erros[] = 'Celular é obrigatório.';
    if ( empty( $pacote_id ) )       $erros[] = 'Selecione um pacote.';
    if ( empty( $forma_pagamento ) ) $erros[] = 'Selecione a forma de pagamento.';
    if ( ! $termo )                  $erros[] = 'Você deve aceitar o Termo de Compromisso.';

    if ( ! empty( $erros ) ) {
        wp_redirect( add_query_arg( 'inscricao', 'erro', wp_get_referer() ) );
        exit;
    }

    // ── Criar ou reutilizar conta WordPress (papel: assinante) ─
    $senha_gerada   = wp_generate_password( 12, false );
    $user_existente = get_user_by( 'email', $email );

    if ( $user_existente ) {
        // Utilizador já existe — actualiza a senha
        $user_id = $user_existente->ID;
        wp_set_password( $senha_gerada, $user_id );
    } else {
        // Conta nova
        $user_id = wp_create_user( $email, $senha_gerada, $email );

        if ( ! is_wp_error( $user_id ) ) {
            wp_update_user( [
                'ID'           => $user_id,
                'display_name' => $nome,
                'first_name'   => $nome,
                'role'         => 'subscriber',
            ] );
            // Suprimir o e-mail padrão do WordPress
            add_filter( 'wp_new_user_notification_email', '__return_false' );
        } else {
            $user_id      = 1;
            $senha_gerada = null;
        }
    }

    // ── Criar post do inscrito ────────────────────────────
    $post_id = wp_insert_post( [
        'post_title'  => $nome,
        'post_type'   => 'inscrito',
        'post_status' => 'publish',
        'post_author' => $user_id,
    ] );

    if ( is_wp_error( $post_id ) ) {
        wp_redirect( add_query_arg( 'inscricao', 'erro', wp_get_referer() ) );
        exit;
    }

    // ── Salvar campos via ACF ─────────────────────────────
    update_field( 'field_inscrito_email',                    $email,                    $post_id );
    update_field( 'field_inscrito_celular',                  $celular,                  $post_id );
    update_field( 'field_inscrito_associacao',               $associacao,               $post_id );
    update_field( 'field_inscrito_apelido',                  $apelido,                  $post_id );
    update_field( 'field_inscrito_graduacao',                $graduacao,                $post_id );
    update_field( 'field_inscrito_camiseta',                 $camiseta,                 $post_id );
    update_field( 'field_inscrito_alergia_alimento',         $alergia_alimento,         $post_id );
    update_field( 'field_inscrito_alergia_alimento_desc',    $alergia_alimento_desc,    $post_id );
    update_field( 'field_inscrito_alergia_remedio',          $alergia_remedio,          $post_id );
    update_field( 'field_inscrito_alergia_remedio_desc',     $alergia_remedio_desc,     $post_id );
    update_field( 'field_inscrito_pacote_id',                $pacote_id,                $post_id );
    update_field( 'field_inscrito_forma_pagamento',          $forma_pagamento,          $post_id );
    update_field( 'field_inscrito_data_pagamento',           $data_pagamento,           $post_id );
    update_field( 'field_inscrito_transporte',               $transporte,               $post_id );
    update_field( 'field_inscrito_status',                   'pendente',                $post_id );
    update_field( 'field_inscrito_user_id',                  $user_id,                  $post_id );

    update_post_meta( $post_id, '_status_anterior_inscrito', 'pendente' );

    // Token único para envio do comprovante
    $token = wp_generate_password( 40, false );
    update_post_meta( $post_id, '_inscrito_token', $token );

    // Guardar senha temporariamente para enviar no 2.º e-mail
    if ( $senha_gerada ) {
        update_post_meta( $post_id, '_inscrito_senha_temp', $senha_gerada );
    }

    wp_set_object_terms( $post_id, '2027', 'edicao_evento' );

    // ── EMAIL 1: Agradecimento + link do comprovante ──────
    abril_email_inscricao_recebida( $nome, $email, $token );

    wp_redirect( add_query_arg( 'inscricao', 'sucesso', wp_get_referer() ) );
    exit;
}
add_action( 'init', function () {
    if ( isset( $_POST['abril_inscricao_nonce'] ) ) {
        abril_processar_inscricao();
    }
} );


// ─────────────────────────────────────────────────
// EMAIL 1 — Inscrição recebida + link do comprovante
// ─────────────────────────────────────────────────
function abril_email_inscricao_recebida( $nome, $email, $token ) {
    $link_comprovante = add_query_arg( 'token', $token, home_url( '/enviar-comprovante/' ) );

    $assunto  = sprintf( '[%s] Obrigado pela sua inscrição! 🎉', get_option( 'blogname' ) );
    $mensagem = "Olá, {$nome}!\n\n" .
                "Obrigado por se inscrever no evento Abril pra Angola 2027.\n" .
                "A sua inscrição foi recebida com sucesso e está aguardando confirmação.\n\n" .
                "─────────────────────────────────\n" .
                "📎 PRÓXIMO PASSO: ENVIAR O COMPROVANTE\n" .
                "─────────────────────────────────\n\n" .
                "Para confirmarmos a sua vaga, envie o comprovante de pagamento\n" .
                "através do link abaixo (pode fazer isso quando quiser):\n\n" .
                $link_comprovante . "\n\n" .
                "⚠️ Guarde este e-mail! O link é pessoal e intransferível.\n\n" .
                "Em caso de dúvidas, responda a este e-mail.\n\n" .
                "Equipe Abril pra Angola";

    wp_mail( $email, $assunto, $mensagem );
}


// ─────────────────────────────────────────────────
// EMAIL 2 — Comprovante recebido + credenciais de acesso
// (chamado a partir de enviar-comprovante.php)
// ─────────────────────────────────────────────────
function abril_email_comprovante_recebido( $post_id ) {
    $nome  = get_the_title( $post_id );
    $email = get_field( 'inscrito_email', $post_id );
    if ( empty( $email ) ) return;

    // Link directo para o post no wp-admin (o assinante é redirecionado automaticamente)
    $link_acesso = wp_login_url( admin_url( 'post.php?post=' . $post_id . '&action=edit' ) );

    // Recuperar senha temporária
    $senha = get_post_meta( $post_id, '_inscrito_senha_temp', true );

    $bloco_acesso =
        "─────────────────────────────────\n" .
        "🔑 SEUS DADOS DE ACESSO\n" .
        "─────────────────────────────────\n" .
        "🌐 URL:   " . $link_acesso . "\n" .
        "👤 Login: " . $email . "\n" .
        "🔒 Senha: " . ( $senha ?: '(verifique o primeiro e-mail)' ) . "\n\n" .
        "Acesse o link acima para acompanhar o status da sua inscrição.\n";

    $assunto  = sprintf( '[%s] Comprovante recebido — aguarde a confirmação! ✅', get_option( 'blogname' ) );
    $mensagem = "Olá, {$nome}!\n\n" .
                "Recebemos o seu comprovante de pagamento. Obrigado! 🙏\n\n" .
                "A nossa equipa irá validá-lo em breve e você receberá\n" .
                "um novo e-mail confirmando a sua participação.\n\n" .
                $bloco_acesso . "\n" .
                "Equipe Abril pra Angola";

    wp_mail( $email, $assunto, $mensagem );

    // Apagar senha temporária após envio (segurança)
    delete_post_meta( $post_id, '_inscrito_senha_temp' );
}


// ─────────────────────────────────────────────────
// EMAIL 3 — Confirmação final (disparado pelo ACF ao mudar status)
// ─────────────────────────────────────────────────
function abril_enviar_email_confirmacao( $post_id ) {
    $nome  = get_the_title( $post_id );
    $email = get_field( 'inscrito_email', $post_id );
    if ( empty( $email ) ) return;

    $assunto  = sprintf( '[%s] 🎊 Inscrição CONFIRMADA!', get_option( 'blogname' ) );
    $mensagem = "Parabéns, {$nome}!\n\n" .
                "A sua inscrição para o Abril pra Angola 2027 está CONFIRMADA! 🎊\n\n" .
                "Aguardamos você no evento!\n\n" .
                "Equipe Abril pra Angola";

    wp_mail( $email, $assunto, $mensagem );
}


// ─────────────────────────────────────────────────
// AJAX: Retornar preço e link MP do pacote
// ─────────────────────────────────────────────────
function abril_ajax_get_pacote() {
    check_ajax_referer( 'abril_ajax_nonce', 'nonce' );

    $pacote_id = intval( $_POST['pacote_id'] ?? 0 );
    if ( ! $pacote_id ) wp_send_json_error();

    $preco   = get_field( 'pacote_preco',   $pacote_id );
    $link_mp = get_field( 'pacote_link_mp', $pacote_id );

    wp_send_json_success( [
        'preco'   => number_format( (float) $preco, 2, ',', '.' ),
        'link_mp' => $link_mp,
    ] );
}
add_action( 'wp_ajax_abril_get_pacote',        'abril_ajax_get_pacote' );
add_action( 'wp_ajax_nopriv_abril_get_pacote', 'abril_ajax_get_pacote' );

