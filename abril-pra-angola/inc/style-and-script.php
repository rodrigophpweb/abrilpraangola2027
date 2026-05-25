<?php
/**
 * Enfileiramento de estilos e scripts — Abril pra Angola
 *
 * Estilos globais + CSS específico por página/template.
 */

function abril_enqueue_styles_and_scripts() {

    $theme_uri      = get_template_directory_uri();
    $components_uri = $theme_uri . '/assets/css/components/';
    $pages_css_uri  = $theme_uri . '/assets/css/pages/';
    $version        = '1.0.0';

    // ─────────────────────────────────────────────────────────
    // 1. Variáveis CSS (Design Tokens) — base de tudo
    // ─────────────────────────────────────────────────────────
    wp_enqueue_style(
        'abril-variables',
        $theme_uri . '/assets/css/variables.css',
        [],
        $version
    );

    // ─────────────────────────────────────────────────────────
    // 2. Folha principal do tema (só o cabeçalho WordPress)
    // ─────────────────────────────────────────────────────────
    wp_enqueue_style(
        'abril-style',
        get_stylesheet_uri(),
        [ 'abril-variables' ],
        $version
    );

    // Font Awesome — ícones das redes sociais no front-end
    wp_enqueue_style(
        'abril-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        [],
        '6.5.2'
    );

    // ─────────────────────────────────────────────────────────
    // 3. Componentes globais (carregam em todas as páginas)
    // ─────────────────────────────────────────────────────────

    // Botões
    wp_enqueue_style(
        'abril-buttons',
        $components_uri . 'button.css',
        [ 'abril-variables' ],
        $version
    );

    // Alertas / Mensagens de feedback
    wp_enqueue_style(
        'abril-alerts',
        $components_uri . 'alert.css',
        [ 'abril-variables' ],
        $version
    );

    // Header
    wp_enqueue_style(
        'abril-header',
        $components_uri . 'header.css',
        [ 'abril-variables' ],
        $version
    );

    // Footer
    wp_enqueue_style(
        'abril-footer',
        $components_uri . 'footer.css',
        [ 'abril-variables' ],
        $version
    );

    // ─────────────────────────────────────────────────────────
    // 4. CSS específico por página / template
    // ─────────────────────────────────────────────────────────

    $global_deps = [ 'abril-style', 'abril-buttons', 'abril-alerts' ];

    $page_styles = [

        // Home (front-page.php)
        [
            'condition' => is_front_page(),
            'handle'    => 'abril-page-home',
            'file'      => 'front-page.css',
        ],

        // Blog (lista de posts)
        [
            'condition' => is_home() && ! is_front_page(),
            'handle'    => 'abril-page-blog',
            'file'      => 'blog.css',
        ],

        // Inscrição
        [
            'condition' => is_page_template( 'template-pages/subscribe.php' ),
            'handle'    => 'abril-page-inscricao',
            'file'      => 'inscricao.css',
        ],

        // Enviar Comprovante
        [
            'condition' => is_page_template( 'template-pages/enviar-comprovante.php' ),
            'handle'    => 'abril-page-comprovante',
            'file'      => 'enviar-comprovante.css',
        ],

        // Minha Inscrição
        [
            'condition' => is_page_template( 'template-pages/minha-inscricao.php' ),
            'handle'    => 'abril-page-minha-inscricao',
            'file'      => 'minha-inscricao.css',
        ],

        // Localização
        [
            'condition' => is_page( 'localizacao' ),
            'handle'    => 'abril-page-localizacao',
            'file'      => 'localizacao.css',
        ],

        // Nossos Oficineiros
        [
            'condition' => is_page( 'nossos-oficineiros' ),
            'handle'    => 'abril-page-oficineiros',
            'file'      => 'oficineiros.css',
        ],

        // Nossos Pacotes
        [
            'condition' => is_page( 'nossos-pacotes' ),
            'handle'    => 'abril-page-pacotes',
            'file'      => 'pacotes.css',
        ],

        // Política de Privacidade
        [
            'condition' => is_page( 'politica-de-privacidade' ),
            'handle'    => 'abril-page-politica',
            'file'      => 'politica-de-privacidade.css',
        ],

        // Contato
        [
            'condition' => is_page( 'contato' ),
            'handle'    => 'abril-page-contato',
            'file'      => 'contato.css',
        ],

        // Página genérica
        [
            'condition' => is_page() && ! is_page_template(),
            'handle'    => 'abril-page',
            'file'      => 'page.css',
        ],

        // Single post
        [
            'condition' => is_single(),
            'handle'    => 'abril-single',
            'file'      => 'single.css',
        ],

        // Busca
        [
            'condition' => is_search(),
            'handle'    => 'abril-search',
            'file'      => 'search.css',
        ],

    ];

    foreach ( $page_styles as $style ) {
        if ( $style['condition'] ) {
            wp_enqueue_style(
                $style['handle'],
                $pages_css_uri . $style['file'],
                $global_deps,
                $version
            );
        }
    }

    // ─────────────────────────────────────────────────────────
    // 5. Scripts
    // ─────────────────────────────────────────────────────────

    wp_enqueue_script(
        'abril-main',
        $theme_uri . '/assets/js/main.js',
        [],
        $version,
        true
    );

    // Localiza dados necessários para o formulário de inscrição
    if ( is_page_template( 'template-pages/subscribe.php' ) ) {
        $pix_opts = function_exists( 'abril_get_pagamento_options' ) ? abril_get_pagamento_options() : [];
        wp_localize_script( 'abril-main', 'abrilData', [
            'pixChave'    => $pix_opts['pix_chave']             ?? '',
            'pixNome'     => $pix_opts['pix_nome_beneficiario'] ?? 'Abril pra Angola',
            'pixCidade'   => $pix_opts['pix_cidade']            ?? 'Sao Paulo',
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'abril_enqueue_styles_and_scripts' );
