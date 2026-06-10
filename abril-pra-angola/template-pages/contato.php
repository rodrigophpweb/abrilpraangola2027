<?php
/**
 * Template Name: Contato
 *
 * Página de contato — envia mensagem para mestremeinha@hotmail.com
 *
 * @package abril-pra-angola
 */

get_header();

// ── Estado do formulário ───────────────────────────────
$status = sanitize_key( $_GET['contato'] ?? '' );
?>

<main
    id="main-content"
    class="page-contato"
    role="main"
    itemscope
    itemtype="https://schema.org/ContactPage"
>
    <?php
    get_template_part( 'template-parts/breadcrumb', null, [
        'items' => [
            [ 'label' => get_the_title(), 'url' => '' ],
        ],
    ] );
    ?>

    <div class="container">

        <!-- ═══════════════════════════════════════════════
             CABEÇALHO DA PÁGINA
             ═══════════════════════════════════════════════ -->
        <header class="page-contato__header">
            <h1 class="page-contato__title">
                <?php the_title(); ?>
            </h1>
            <p class="page-contato__subtitle">
                <?php the_content();?>
            </p>
        </header>

        <!-- ═══════════════════════════════════════════════
             GRID PRINCIPAL — Formulário + Informações
             ═══════════════════════════════════════════════ -->
        <div class="page-contato__grid">

            <!-- ── Coluna: Formulário ─────────────────────── -->
            <section
                class="page-contato__form-wrap"
                aria-labelledby="contato-form-title"
            >
                <h2 id="contato-form-title" class="screen-reader-text">
                    Formulário de contato
                </h2>

                <?php if ( $status === 'sucesso' ) : ?>
                    <div class="alert alert--sucesso" role="alert" aria-live="polite">
                        <strong>✅ Mensagem enviada com sucesso!</strong>
                        Obrigado pelo contato. Responderemos em breve.
                    </div>
                <?php elseif ( $status === 'erro' ) : ?>
                    <div class="alert alert--erro" role="alert" aria-live="polite">
                        <strong>❌ Erro ao enviar a mensagem.</strong>
                        Verifique os campos e tente novamente.
                    </div>
                <?php endif; ?>

                <form
                    class="form-contato"
                    method="POST"
                    action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
                    novalidate
                    aria-label="Formulário de contato"
                    itemscope
                    itemtype="https://schema.org/ContactPoint"
                >
                    <?php wp_nonce_field( 'abril_contato_submit', 'abril_contato_nonce' ); ?>
                    <input type="hidden" name="action" value="abril_contato">

                    <!-- ── Linha 1: Nome + E-mail ──────────── -->
                    <div class="form-contato__row">

                        <div class="form-group">
                            <label for="contato-nome">
                                Nome completo
                                <span class="form-group__required" aria-hidden="true">*</span>
                            </label>
                            <input
                                type="text"
                                id="contato-nome"
                                name="contato_nome"
                                autocomplete="name"
                                placeholder="Seu nome completo"
                                required
                                aria-required="true"
                                itemprop="name"
                            >
                        </div>

                        <div class="form-group">
                            <label for="contato-email">
                                E-mail
                                <span class="form-group__required" aria-hidden="true">*</span>
                            </label>
                            <input
                                type="email"
                                id="contato-email"
                                name="contato_email"
                                autocomplete="email"
                                placeholder="seu@email.com"
                                required
                                aria-required="true"
                                itemprop="email"
                            >
                        </div>

                    </div>

                    <!-- ── Linha 2: Telefone + Assunto ─────── -->
                    <div class="form-contato__row">

                        <div class="form-group">
                            <label for="contato-telefone">
                                Telefone / WhatsApp
                            </label>
                            <input
                                type="tel"
                                id="contato-telefone"
                                name="contato_telefone"
                                autocomplete="tel"
                                placeholder="(11) 99999-9999"
                                itemprop="telephone"
                            >
                        </div>

                        <div class="form-group">
                            <label for="contato-assunto">
                                Assunto
                                <span class="form-group__required" aria-hidden="true">*</span>
                            </label>
                            <select
                                id="contato-assunto"
                                name="contato_assunto"
                                required
                                aria-required="true"
                            >
                                <option value="" disabled selected>Selecione o assunto</option>
                                <option value="Informações sobre o evento">Informações sobre o evento</option>
                                <option value="Inscrição e pacotes">Inscrição e pacotes</option>
                                <option value="Pagamento">Pagamento</option>
                                <option value="Oficineiros e programação">Oficineiros e programação</option>
                                <option value="Parcerias e patrocínio">Parcerias e patrocínio</option>
                                <option value="Imprensa">Imprensa</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                    </div>

                    <!-- ── Mensagem ────────────────────────── -->
                    <div class="form-group">
                        <label for="contato-mensagem">
                            Mensagem
                            <span class="form-group__required" aria-hidden="true">*</span>
                        </label>
                        <textarea
                            id="contato-mensagem"
                            name="contato_mensagem"
                            rows="6"
                            placeholder="Escreva sua mensagem aqui…"
                            required
                            aria-required="true"
                        ></textarea>
                    </div>

                    <!-- ── Nota campos obrigatórios ────────── -->
                    <p class="form-contato__required-note" aria-hidden="true">
                        <span aria-hidden="true">*</span> Campos obrigatórios
                    </p>

                    <!-- ── Botão de envio ──────────────────── -->
                    <div class="form-contato__submit">
                        <button
                            type="submit"
                            class="btn btn-primary btn-lg"
                            aria-label="Enviar mensagem de contato"
                        >
                            Enviar mensagem
                        </button>
                    </div>

                </form>
            </section>

            <!-- ── Coluna: Informações de contato ─────────── -->
            <aside
                class="page-contato__info"
                aria-label="Informações de contato"
                itemscope
                itemtype="https://schema.org/Organization"
            >
                <meta itemprop="name" content="Abril pra Angola">

                <h2 class="page-contato__info-title">Fale conosco</h2>

                <ul class="page-contato__info-list" role="list">

                    <li class="page-contato__info-item">
                        <span class="page-contato__info-icon" aria-hidden="true">
                            <i class="fa-solid fa-envelope"></i>
                        </span>
                        <div class="page-contato__info-content">
                            <strong>E-mail</strong>
                            <a
                                href="mailto:mestremeinha@hotmail.com"
                                itemprop="email"
                                aria-label="Enviar e-mail para mestremeinha@hotmail.com"
                            >
                                mestremeinha@hotmail.com
                            </a>
                        </div>
                    </li>

                    <li class="page-contato__info-item">
                        <span class="page-contato__info-icon" aria-hidden="true">
                            <i class="fa-solid fa-clock"></i>
                        </span>
                        <div class="page-contato__info-content">
                            <strong>Tempo de resposta</strong>
                            <span>Em até 48 horas úteis</span>
                        </div>
                    </li>

                </ul>

                <!-- ── Redes Sociais ───────────────────────── -->
                <div class="page-contato__social">
                    <p class="page-contato__social-label">Siga-nos nas redes sociais</p>
                    <ul class="page-contato__social-list" role="list">
                        <li>
                            <a
                                href="https://instagram.com/abrilpraangola"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Instagram do Abril pra Angola (abre em nova aba)"
                                itemprop="sameAs"
                            >
                                <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://facebook.com/abrilpraangola"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Facebook do Abril pra Angola (abre em nova aba)"
                                itemprop="sameAs"
                            >
                                <i class="fa-brands fa-facebook" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a
                                href="https://youtube.com/@abrilpraangola"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="YouTube do Abril pra Angola (abre em nova aba)"
                                itemprop="sameAs"
                            >
                                <i class="fa-brands fa-youtube" aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </div>

            </aside>

        </div><!-- /.page-contato__grid -->

    </div><!-- /.container -->
</main>

<?php get_footer(); ?>
