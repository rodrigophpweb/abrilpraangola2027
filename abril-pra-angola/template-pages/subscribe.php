<?php
/**
 * Template Name: Inscrição
 * Página de inscrição do evento com formulário completo.
 */
get_header();

// Buscar todos os pacotes publicados
$pacotes = get_posts( [
    'post_type'   => 'pacote',
    'numberposts' => -1,
    'post_status' => 'publish',
    'orderby'     => 'menu_order',
    'order'       => 'ASC',
] );

// Pré-seleção de pacote via URL (?pkg_id=ID — vindo da seção de ingressos)
// Usamos pkg_id e não 'pacote' para evitar conflito com o CPT 'pacote' do WordPress
$pacote_preselect = intval( get_query_var( 'pkg_id', $_GET['pkg_id'] ?? 0 ) );
?>

<main id="main" class="page-inscricao">

    <div class="container">
        <header class="page-inscricao__header">
            <h1>Inscrição — Abril pra Angola 2027</h1>
            <p>Preencha o formulário abaixo para garantir a sua vaga no evento.</p>
        </header>

        <?php
        // ── Mensagens de feedback ──────────────────────────
        if ( isset( $_GET['inscricao'] ) ) :
            if ( $_GET['inscricao'] === 'sucesso' ) : ?>
                <div class="alert alert--sucesso">
                    <strong>🎉 Inscrição enviada com sucesso!</strong>
                    Recebemos os seus dados. Assim que validarmos o comprovante, enviaremos a confirmação por e-mail.
                </div>
            <?php elseif ( $_GET['inscricao'] === 'erro' ) : ?>
                <div class="alert alert--erro">
                    <strong>⚠️ Ocorreu um erro.</strong>
                    Por favor, verifique os campos e tente novamente.
                </div>
            <?php endif;
        endif;
        ?>

        <?php if ( isset( $_GET['inscricao'] ) && $_GET['inscricao'] === 'sucesso' ) : ?>
            <!-- Após sucesso mostra apenas a mensagem, sem o formulário -->
        <?php else : ?>

            <?php
            // Pré-preenchimento se o utilizador estiver autenticado
            $current_user = is_user_logged_in() ? wp_get_current_user() : null;
            ?>

            <!-- ── Formulário de Inscrição ───────────────────── -->
            <form
                id="form-inscricao"
                class="form-inscricao"
                method="POST"
                action="<?php echo esc_url( get_permalink() ); ?>"
                enctype="multipart/form-data"
                novalidate
            >
                <?php wp_nonce_field( 'abril_inscricao_submit', 'abril_inscricao_nonce' ); ?>

                <!-- ── Dados Pessoais ── -->
                <fieldset class="form-inscricao__fieldset">
                    <legend>👤 Dados Pessoais</legend>

                    <div class="form-group">
                        <label for="nome_completo">Nome Completo *</label>
                        <input type="text" id="nome_completo" name="nome_completo" required
                               value="<?php echo is_user_logged_in() ? esc_attr( $current_user->display_name ) : ''; ?>"
                               placeholder="Seu nome completo" />
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail *</label>
                        <input type="email" id="email" name="email" required
                               value="<?php echo is_user_logged_in() ? esc_attr( $current_user->user_email ) : ''; ?>"
                               placeholder="seu@email.com" />
                    </div>

                    <div class="form-group">
                        <label for="celular">Celular (WhatsApp) *</label>
                        <input type="tel" id="celular" name="celular" required
                               placeholder="(00) 90000-0000" />
                    </div>
                </fieldset>

                <!-- ── Dados de Capoeira ── -->
                <fieldset class="form-inscricao__fieldset">
                    <legend>🥋 Dados de Capoeira</legend>

                    <div class="form-group">
                        <label for="associacao">Associação / Grupo / Escola *</label>
                        <input type="text" id="associacao" name="associacao" required
                               placeholder="Nome do seu grupo" />
                    </div>

                    <div class="form-group">
                        <label for="apelido">Apelido de Capoeira</label>
                        <input type="text" id="apelido" name="apelido"
                               placeholder="Seu apelido na capoeira" />
                    </div>

                    <div class="form-group">
                        <label for="graduacao">Graduação</label>
                        <input type="text" id="graduacao" name="graduacao"
                               placeholder="Ex: Corda Amarela, Contra-Mestre..." />
                    </div>
                </fieldset>

                <!-- ── Informações do Evento ── -->
                <fieldset class="form-inscricao__fieldset">
                    <legend>🎽 Informações do Evento</legend>

                    <div class="form-group">
                        <label>Tamanho da Camiseta *</label>
                        <div class="radio-group">
                            <?php foreach ( [ 'P', 'M', 'G', 'GG' ] as $tam ) : ?>
                                <label class="radio-label">
                                    <input type="radio" name="camiseta" value="<?php echo $tam; ?>" required />
                                    <?php echo $tam; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group form-group--inline">
                        <div>
                            <label for="alergia_alimento">Alergia a algum alimento? *</label>
                            <select id="alergia_alimento" name="alergia_alimento" required>
                                <option value="nao">Não</option>
                                <option value="sim">Sim</option>
                            </select>
                        </div>
                        <div id="alergia_alimento_wrap" style="display:none;">
                            <label for="alergia_alimento_desc">Qual alimento? *</label>
                            <input type="text" id="alergia_alimento_desc" name="alergia_alimento_desc"
                                   placeholder="Ex: Amendoim, Glúten, Lactose..." />
                        </div>
                        <div>
                            <label for="alergia_remedio">Alergia a algum medicamento? *</label>
                            <select id="alergia_remedio" name="alergia_remedio" required>
                                <option value="nao">Não</option>
                                <option value="sim">Sim</option>
                            </select>
                        </div>
                        <div id="alergia_remedio_wrap" style="display:none;">
                            <label for="alergia_remedio_desc">Qual medicamento? *</label>
                            <input type="text" id="alergia_remedio_desc" name="alergia_remedio_desc"
                                   placeholder="Ex: Dipirona, Penicilina..." />
                        </div>
                    </div>
                </fieldset>

                <!-- ── Pacote e Pagamento ── -->
                <fieldset class="form-inscricao__fieldset">
                    <legend>💳 Pacote e Pagamento</legend>

                    <div class="form-group">
                        <label for="pacote_id">Pacote *</label>
                        <select id="pacote_id" name="pacote_id" required>
                            <option value="">— Selecione um pacote —</option>
                            <?php foreach ( $pacotes as $pacote ) :
                                $preco_avista = get_field( 'pacote_preco_avista', $pacote->ID );
                                $preco_cartao = get_field( 'pacote_preco_cartao', $pacote->ID );
                                $link_mp      = get_field( 'pacote_link_mp',      $pacote->ID );
                            ?>
                                <option value="<?php echo esc_attr( $pacote->ID ); ?>"
                                        data-preco-avista="<?php echo esc_attr( $preco_avista ); ?>"
                                        data-preco-cartao="<?php echo esc_attr( $preco_cartao ); ?>"
                                        data-link-mp="<?php echo esc_attr( $link_mp ); ?>"
                                        <?php selected( $pacote_preselect, $pacote->ID ); ?>>
                                    <?php echo esc_html( $pacote->post_title ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Preço dinâmico (à vista) -->
                        <div id="pacote-preco" class="pacote-preco" style="display:none;">
                            Valor do pacote: <strong id="pacote-preco-valor"></strong>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="forma_pagamento">Forma de Pagamento *</label>
                        <select id="forma_pagamento" name="forma_pagamento" required>
                            <option value="">— Selecione —</option>
                            <option value="deposito">Depósito Bancário</option>
                            <option value="pix">PIX</option>
                            <option value="cartao">Cartão de Crédito (Mercado Pago)</option>
                        </select>
                    </div>

                    <!-- Valor total (exibido quando pacote + pagamento selecionados) -->
                    <div id="box-valor-total" class="info-box info-box--total" style="display:none;">
                        💰 <strong>Total a pagar: <span id="valor-total-display">R$ 0,00</span></strong>
                        <span id="valor-total-transporte-msg" style="display:none;"> <small>(inclui R$ 70,00 do transporte)</small></span>
                        <span id="valor-total-cartao-msg" style="display:none;"> <small>— <strong>pode parcelar em até 2x</strong> no cartão de crédito</small></span>
                    </div>

                    <!-- Campo hidden com o valor total calculado (enviado ao servidor) -->
                    <input type="hidden" id="valor_total_hidden" name="valor_total" value="0" />

                    <!-- Dados Bancários (depósito) -->
                    <?php
                    $dados_bancarios = function_exists( 'get_field' ) ? get_field( 'dados_bancarios', 'option' ) : '';
                    ?>
                    <div id="box-deposito" class="info-box info-box--deposito" style="display:none;">
                        🏦 <strong>Dados para Depósito Bancário:</strong><br>
                        <pre><?php echo esc_html( $dados_bancarios ?: 'Dados bancários não configurados. Entre em contato.' ); ?></pre>
                    </div>

                    <!-- Link do Mercado Pago (aparece se cartão for selecionado) -->
                    <div id="link-mercado-pago" class="info-box info-box--mp" style="display:none;">
                        💳 <strong>Pagamento via Cartão de Crédito (Mercado Pago):</strong><br>
                        <a id="btn-mercado-pago" href="#" target="_blank" class="btn btn-mercado-pago">
                            Pagar com Cartão de Crédito →
                        </a>
                        <p><small>✨ Você tem a opção de <strong>parcelar em até 2x</strong> diretamente no link de pagamento.</small></p>
                        <p><small>Após o pagamento, envie o comprovante através do link que receberá por e-mail.</small></p>
                    </div>

                    <!-- PIX QR Code -->
                    <div id="box-pix" class="info-box info-box--pix" style="display:none;">
                        <strong>🔑 Pagamento via PIX:</strong><br>
                        <div class="pix-qrcode-wrap">
                            <img id="pix-qrcode-img" src="" alt="QR Code PIX" style="display:none; max-width:200px; margin: 12px auto; display:block;" />
                            <p><strong>Chave PIX:</strong> <span id="pix-chave-display"></span>
                            <button type="button" id="pix-copiar-chave" class="btn btn-sm">📋 Copiar chave</button></p>
                            <p id="pix-copia-cola-wrap" style="display:none;">
                                <strong>PIX Copia e Cola:</strong><br>
                                <small id="pix-payload-display" style="word-break: break-all;"></small>
                                <button type="button" id="pix-copiar-payload" class="btn btn-sm">📋 Copiar código</button>
                            </p>
                        </div>
                        <p><small>Após o pagamento, envie o comprovante através do link que receberá por e-mail.</small></p>
                    </div>

                    <div class="info-box info-box--aviso">
                        📧 <strong>Comprovante de pagamento:</strong> após o envio da inscrição,
                        você receberá um e-mail com um link para enviar o comprovante no seu tempo.
                        Só confirmaremos a sua vaga após a validação do pagamento.
                    </div>
                </fieldset>

                <!-- ── Opcionais e Termos ── -->
                <fieldset class="form-inscricao__fieldset">
                    <legend>🚌 Opcionais e Termos</legend>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="transporte" value="sim" />
                            <span>Transporte até a chácara — <strong>R$ 70,00</strong></span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="termo" value="sim" required />
                            <span>
                                Todas as informações são verdadeiras, na qual me responsabilizo por
                                quaisquer danos causados aos organizadores do evento. *
                            </span>
                        </label>
                    </div>
                </fieldset>

                <div class="form-inscricao__submit">
                    <button type="submit" class="btn btn-primary btn-lg">
                        Enviar Inscrição 🚀
                    </button>
                </div>

            </form>

        <?php endif; // fim if sucesso ?>

    </div><!-- .container -->

    <?php
        get_template_part( 'template-parts/section-schedule' );
        get_template_part( 'template-parts/section', 'speakers' );
        get_template_part( 'template-parts/section-location' );
        get_template_part( 'template-parts/section', 'sponsors' );
        get_template_part( 'template-parts/faq' );
    ?>
</main>


<?php get_footer(); ?>
