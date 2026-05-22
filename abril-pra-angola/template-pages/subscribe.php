<?php
/**
 * Template Name: Inscrição
 * Página de inscrição do evento com login social e formulário completo.
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

            <?php if ( ! is_user_logged_in() ) : ?>
            <!-- ── Login Social ──────────────────────────────── -->
            <section class="login-social">
                <h2>Entre com a sua conta para agilizar o preenchimento</h2>
                <div class="login-social__botoes">
                    <?php
                    // Nextend Social Login gera estes links automaticamente.
                    // Certifique-se de que o plugin está instalado e configurado.
                    if ( function_exists( 'NSL_Provider_Manager' ) ) :
                        do_action( 'wordpress_social_login' );
                    else : ?>
                        <p class="aviso-plugin">
                            <em>Para habilitar o login com Google e Facebook, instale o plugin
                            <a href="https://wordpress.org/plugins/nextend-social-login/" target="_blank">Nextend Social Login</a>.</em>
                        </p>
                    <?php endif; ?>
                </div>
                <p class="login-social__ou"><span>ou preencha o formulário abaixo</span></p>
            </section>
            <?php else :
                $current_user = wp_get_current_user();
            ?>
            <div class="utilizador-logado">
                👋 Olá, <strong><?php echo esc_html( $current_user->display_name ); ?></strong>!
                Seus dados serão vinculados a esta inscrição.
            </div>
            <?php endif; ?>

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
                        <div>
                            <label for="alergia_remedio">Alergia a algum medicamento? *</label>
                            <select id="alergia_remedio" name="alergia_remedio" required>
                                <option value="nao">Não</option>
                                <option value="sim">Sim</option>
                            </select>
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
                                $preco   = get_field( 'pacote_preco',   $pacote->ID );
                                $link_mp = get_field( 'pacote_link_mp', $pacote->ID );
                            ?>
                                <option value="<?php echo esc_attr( $pacote->ID ); ?>"
                                        data-preco="<?php echo esc_attr( $preco ); ?>"
                                        data-link-mp="<?php echo esc_attr( $link_mp ); ?>">
                                    <?php echo esc_html( $pacote->post_title ); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <!-- Preço dinâmico -->
                        <div id="pacote-preco" class="pacote-preco" style="display:none;">
                            Valor do pacote: <strong id="pacote-preco-valor"></strong>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="forma_pagamento">Forma de Pagamento *</label>
                        <select id="forma_pagamento" name="forma_pagamento" required>
                            <option value="">— Selecione —</option>
                            <option value="deposito">Depósito Bancário</option>
                            <option value="cartao">Cartão de Crédito (Mercado Pago)</option>
                        </select>
                    </div>

                    <!-- Link do Mercado Pago (aparece se cartão for selecionado) -->
                    <div id="link-mercado-pago" class="info-box info-box--mp" style="display:none;">
                        💳 <strong>Pagamento via Mercado Pago:</strong><br>
                        <a id="btn-mercado-pago" href="#" target="_blank" class="btn btn-mercado-pago">
                            Pagar com Cartão de Crédito →
                        </a>
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

</main>

<script>
(function () {
    const selectPacote    = document.getElementById('pacote_id');
    const selectPagamento = document.getElementById('forma_pagamento');
    const boxPreco        = document.getElementById('pacote-preco');
    const valorPreco      = document.getElementById('pacote-preco-valor');
    const boxMP           = document.getElementById('link-mercado-pago');
    const btnMP           = document.getElementById('btn-mercado-pago');

    function getOpcaoAtual() {
        return selectPacote.options[selectPacote.selectedIndex];
    }

    function atualizarPreco() {
        const opt   = getOpcaoAtual();
        const preco = parseFloat( opt?.dataset?.preco );

        if ( opt?.value && ! isNaN(preco) && preco > 0 ) {
            valorPreco.textContent = 'R$ ' + preco.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
            boxPreco.style.display = 'block';
        } else {
            boxPreco.style.display = 'none';
        }
    }

    function atualizarLinkMP() {
        const opt    = getOpcaoAtual();
        const linkMp = opt?.dataset?.linkMp;

        if ( selectPagamento.value === 'cartao' && linkMp && linkMp.startsWith('http') ) {
            btnMP.href = linkMp;
            boxMP.style.display = 'block';
        } else {
            boxMP.style.display = 'none';
        }
    }

    selectPacote?.addEventListener('change', function() {
        atualizarPreco();
        atualizarLinkMP();
    });

    selectPagamento?.addEventListener('change', atualizarLinkMP);
})();
</script>

<?php get_footer(); ?>
