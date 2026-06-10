<?php
/**
 * Contact Handler — Abril pra Angola
 *
 * Processa o formulário de contato da página /contato/ e envia
 * a mensagem para mestremeinha@hotmail.com via wp_mail().
 *
 * Usa admin-post.php (action: abril_contato) com nonce de segurança.
 *
 * @package abril-pra-angola
 */

// ─────────────────────────────────────────────────────────────
// HANDLER — admin-post.php (usuários logados e visitantes)
// ─────────────────────────────────────────────────────────────
add_action( 'admin_post_abril_contato',        'abril_processar_contato' );
add_action( 'admin_post_nopriv_abril_contato', 'abril_processar_contato' );

/**
 * Processa, valida, sanitiza e envia o formulário de contato.
 */
function abril_processar_contato(): void {

    // ── 1. Verificar nonce ─────────────────────────────────
    if (
        ! isset( $_POST['abril_contato_nonce'] ) ||
        ! wp_verify_nonce( $_POST['abril_contato_nonce'], 'abril_contato_submit' )
    ) {
        wp_die(
            esc_html__( 'Verificação de segurança falhou. Tente novamente.', 'abril-pra-angola' ),
            esc_html__( 'Erro de segurança', 'abril-pra-angola' ),
            [ 'response' => 403, 'back_link' => true ]
        );
    }

    // ── 2. Obter URL de retorno ────────────────────────────
    $referer    = wp_get_referer();
    $url_base   = $referer ?: home_url( '/contato/' );
    $url_erro   = add_query_arg( 'contato', 'erro',   $url_base );
    $url_sucesso = add_query_arg( 'contato', 'sucesso', $url_base );

    // ── 3. Sanitização ─────────────────────────────────────
    $nome      = sanitize_text_field( $_POST['contato_nome']      ?? '' );
    $email     = sanitize_email(      $_POST['contato_email']     ?? '' );
    $telefone  = sanitize_text_field( $_POST['contato_telefone']  ?? '' );
    $assunto   = sanitize_text_field( $_POST['contato_assunto']   ?? '' );
    $mensagem  = sanitize_textarea_field( $_POST['contato_mensagem'] ?? '' );

    // ── 4. Validação ───────────────────────────────────────
    $erros = [];

    if ( empty( $nome ) ) {
        $erros[] = 'Nome é obrigatório.';
    }

    if ( empty( $email ) || ! is_email( $email ) ) {
        $erros[] = 'E-mail inválido ou obrigatório.';
    }

    if ( empty( $assunto ) ) {
        $erros[] = 'Assunto é obrigatório.';
    }

    if ( empty( $mensagem ) ) {
        $erros[] = 'Mensagem é obrigatória.';
    }

    if ( mb_strlen( $mensagem ) > 5000 ) {
        $erros[] = 'Mensagem muito longa (máximo 5000 caracteres).';
    }

    if ( ! empty( $erros ) ) {
        wp_safe_redirect( $url_erro );
        exit;
    }

    // ── 5. Composição do e-mail ────────────────────────────
    $destinatario = 'mestremeinha@hotmail.com';

    $assunto_email = sprintf(
        '[Abril pra Angola] Contato: %s — %s',
        $assunto,
        $nome
    );

    $corpo = abril_contato_montar_corpo( $nome, $email, $telefone, $assunto, $mensagem );

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        sprintf( 'Reply-To: %s <%s>', $nome, $email ),
    ];

    // ── 6. Envio ───────────────────────────────────────────
    $enviado = wp_mail( $destinatario, $assunto_email, $corpo, $headers );

    if ( ! $enviado ) {
        wp_safe_redirect( $url_erro );
        exit;
    }

    // ── 7. E-mail de confirmação ao remetente ──────────────
    abril_contato_confirmar_remetente( $nome, $email, $assunto );

    // ── 8. Redirecionar com sucesso ────────────────────────
    wp_safe_redirect( $url_sucesso );
    exit;
}


/**
 * Monta o corpo HTML do e-mail recebido pelo administrador.
 */
function abril_contato_montar_corpo(
    string $nome,
    string $email,
    string $telefone,
    string $assunto,
    string $mensagem
): string {

    $telefone_exibido = $telefone ?: '—';
    $mensagem_html    = nl2br( esc_html( $mensagem ) );
    $data_envio       = wp_date( 'd/m/Y \à\s H:i', time() );
    $site_name        = esc_html( get_bloginfo( 'name' ) );

    return "
<!DOCTYPE html>
<html lang='pt-BR'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title>Nova mensagem de contato — {$site_name}</title>
</head>
<body style='margin:0;padding:0;background-color:#f4f4f4;font-family:\"Open Sans\",Arial,sans-serif;'>

  <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='background-color:#f4f4f4;padding:2rem 1rem;'>
    <tr>
      <td align='center'>
        <table role='presentation' width='600' cellpadding='0' cellspacing='0' style='max-width:600px;width:100%;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);'>

          <!-- Cabeçalho -->
          <tr>
            <td style='background-color:#E5720A;padding:2rem;text-align:center;'>
              <h1 style='margin:0;font-size:1.25rem;font-weight:700;color:#ffffff;letter-spacing:0.05em;'>
                📬 Nova mensagem de contato
              </h1>
              <p style='margin:0.5rem 0 0;font-size:0.875rem;color:rgba(255,255,255,0.85);'>
                {$site_name}
              </p>
            </td>
          </tr>

          <!-- Corpo -->
          <tr>
            <td style='padding:2rem;'>

              <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='border-collapse:collapse;'>

                <tr>
                  <td style='padding:0.75rem 0;border-bottom:1px solid #f0f0f0;'>
                    <span style='font-size:0.75rem;font-weight:700;color:#767676;letter-spacing:0.08em;text-transform:uppercase;display:block;margin-bottom:0.25rem;'>Nome</span>
                    <span style='font-size:1rem;color:#1F1F1F;font-weight:600;'>" . esc_html( $nome ) . "</span>
                  </td>
                </tr>

                <tr>
                  <td style='padding:0.75rem 0;border-bottom:1px solid #f0f0f0;'>
                    <span style='font-size:0.75rem;font-weight:700;color:#767676;letter-spacing:0.08em;text-transform:uppercase;display:block;margin-bottom:0.25rem;'>E-mail</span>
                    <a href='mailto:" . esc_attr( $email ) . "' style='font-size:1rem;color:#E5720A;text-decoration:none;font-weight:600;'>" . esc_html( $email ) . "</a>
                  </td>
                </tr>

                <tr>
                  <td style='padding:0.75rem 0;border-bottom:1px solid #f0f0f0;'>
                    <span style='font-size:0.75rem;font-weight:700;color:#767676;letter-spacing:0.08em;text-transform:uppercase;display:block;margin-bottom:0.25rem;'>Telefone / WhatsApp</span>
                    <span style='font-size:1rem;color:#1F1F1F;'>" . esc_html( $telefone_exibido ) . "</span>
                  </td>
                </tr>

                <tr>
                  <td style='padding:0.75rem 0;border-bottom:1px solid #f0f0f0;'>
                    <span style='font-size:0.75rem;font-weight:700;color:#767676;letter-spacing:0.08em;text-transform:uppercase;display:block;margin-bottom:0.25rem;'>Assunto</span>
                    <span style='font-size:1rem;color:#1F1F1F;font-weight:600;'>" . esc_html( $assunto ) . "</span>
                  </td>
                </tr>

                <tr>
                  <td style='padding:1rem 0 0;'>
                    <span style='font-size:0.75rem;font-weight:700;color:#767676;letter-spacing:0.08em;text-transform:uppercase;display:block;margin-bottom:0.75rem;'>Mensagem</span>
                    <div style='background-color:#f9f9f9;border-left:4px solid #E5720A;border-radius:0 8px 8px 0;padding:1rem 1.25rem;font-size:0.9375rem;line-height:1.7;color:#383838;'>
                      {$mensagem_html}
                    </div>
                  </td>
                </tr>

              </table>

            </td>
          </tr>

          <!-- Rodapé -->
          <tr>
            <td style='background-color:#f9f9f9;padding:1.25rem 2rem;border-top:1px solid #f0f0f0;'>
              <p style='margin:0;font-size:0.75rem;color:#767676;text-align:center;'>
                Mensagem enviada em <strong>{$data_envio}</strong> através do formulário de contato do site.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>";
}


/**
 * Envia e-mail de confirmação automático para quem preencheu o formulário.
 */
function abril_contato_confirmar_remetente(
    string $nome,
    string $email,
    string $assunto
): void {

    $site_name    = get_bloginfo( 'name' );
    $assunto_conf = sprintf( '[%s] Recebemos sua mensagem! 📬', $site_name );

    $corpo = "
<!DOCTYPE html>
<html lang='pt-BR'>
<head>
  <meta charset='UTF-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title>Mensagem recebida — {$site_name}</title>
</head>
<body style='margin:0;padding:0;background-color:#f4f4f4;font-family:\"Open Sans\",Arial,sans-serif;'>

  <table role='presentation' width='100%' cellpadding='0' cellspacing='0' style='background-color:#f4f4f4;padding:2rem 1rem;'>
    <tr>
      <td align='center'>
        <table role='presentation' width='600' cellpadding='0' cellspacing='0' style='max-width:600px;width:100%;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);'>

          <tr>
            <td style='background-color:#E5720A;padding:2rem;text-align:center;'>
              <h1 style='margin:0;font-size:1.25rem;font-weight:700;color:#ffffff;'>
                ✅ Mensagem recebida!
              </h1>
              <p style='margin:0.5rem 0 0;font-size:0.875rem;color:rgba(255,255,255,0.85);'>
                " . esc_html( $site_name ) . "
              </p>
            </td>
          </tr>

          <tr>
            <td style='padding:2rem;'>
              <p style='margin:0 0 1rem;font-size:1rem;color:#1F1F1F;line-height:1.7;'>
                Olá, <strong>" . esc_html( $nome ) . "</strong>!
              </p>
              <p style='margin:0 0 1rem;font-size:0.9375rem;color:#383838;line-height:1.7;'>
                Recebemos sua mensagem sobre <strong>" . esc_html( $assunto ) . "</strong>.
                Nossa equipe irá analisá-la e responderá em até <strong>48 horas úteis</strong>.
              </p>
              <p style='margin:0;font-size:0.9375rem;color:#383838;line-height:1.7;'>
                Caso necessite de retorno mais urgente, entre em contato diretamente pelo e-mail
                <a href='mailto:mestremeinha@hotmail.com' style='color:#E5720A;font-weight:600;text-decoration:none;'>mestremeinha@hotmail.com</a>.
              </p>
            </td>
          </tr>

          <tr>
            <td style='background-color:#f9f9f9;padding:1.25rem 2rem;border-top:1px solid #f0f0f0;'>
              <p style='margin:0;font-size:0.75rem;color:#767676;text-align:center;'>
                Este é um e-mail automático. Por favor, não responda a esta mensagem.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>";

    $headers = [ 'Content-Type: text/html; charset=UTF-8' ];
    wp_mail( $email, $assunto_conf, $corpo, $headers );
}

