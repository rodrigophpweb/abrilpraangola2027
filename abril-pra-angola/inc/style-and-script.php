<?php
/**
 * Enfileiramento de estilos e scripts — Abril pra Angola
 *
 * Cada componente CSS é carregado SOMENTE nas páginas que o utilizam.
 * Nenhum @import nos arquivos de página — tudo via wp_enqueue_style().
 */

function abril_enqueue_styles_and_scripts() {

    $theme_uri      = get_template_directory_uri();
    $components_uri = $theme_uri . '/assets/css/components/';
    $pages_css_uri  = $theme_uri . '/assets/css/pages/';
    $version        = '1.0.0';

    // ─────────────────────────────────────────────────────────
    // 1. Design Tokens — base de todo o sistema visual
    // ─────────────────────────────────────────────────────────
    wp_enqueue_style(
        'abril-variables',
        $theme_uri . '/assets/css/variables.css',
        [],
        $version
    );

    // ─────────────────────────────────────────────────────────
    // 2. Folha principal do tema (cabeçalho WordPress)
    // ─────────────────────────────────────────────────────────
    wp_enqueue_style(
        'abril-style',
        get_stylesheet_uri(),
        [ 'abril-variables' ],
        $version
    );

    // Font Awesome — usado no footer (WhatsApp) e demais seções com ícones
    wp_enqueue_style(
        'abril-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        [],
        '6.5.2'
    );

    // ─────────────────────────────────────────────────────────
    // 3. Componentes globais — presentes em TODAS as páginas
    // ─────────────────────────────────────────────────────────
    wp_enqueue_style(
        'abril-header',
        $components_uri . 'header.css',
        [ 'abril-variables' ],
        $version
    );

    wp_enqueue_style(
        'abril-footer',
        $components_uri . 'footer.css',
        [ 'abril-variables' ],
        $version
    );

    wp_enqueue_style(
        'abril-buttons',
        $components_uri . 'button.css',
        [ 'abril-variables' ],
        $version
    );

    // ─────────────────────────────────────────────────────────
    // 4. Alertas — apenas em páginas com formulários / feedback
    // ─────────────────────────────────────────────────────────
    $has_form = is_page_template( 'template-pages/contato.php' )
        || is_page_template( 'template-pages/subscribe.php' )
        || is_page_template( 'template-pages/enviar-comprovante.php' )
        || is_page_template( 'template-pages/minha-inscricao.php' )
        || is_page( 'contato' );

    if ( $has_form ) {
        wp_enqueue_style(
            'abril-alerts',
            $components_uri . 'alert.css',
            [ 'abril-variables' ],
            $version
        );
    }

    // ─────────────────────────────────────────────────────────
    // 5. Componentes condicionais — apenas nas páginas que os usam
    // ─────────────────────────────────────────────────────────

    // Breadcrumb — single posts + páginas internas com trilha de navegação
    $has_breadcrumb = is_single()
        || is_page()                                  // todas as páginas (genéricas + templates)
        || is_page_template( 'template-pages/contato.php' )
        || is_page_template( 'template-pages/subscribe.php' )
        || is_page_template( 'template-pages/enviar-comprovante.php' )
        || is_page_template( 'template-pages/minha-inscricao.php' );

    if ( $has_breadcrumb ) {
        wp_enqueue_style(
            'abril-breadcrumb',
            $components_uri . 'breadcrumb.css',
            [ 'abril-variables' ],
            $version
        );
    }

    // Hero Banner — apenas home
    if ( is_front_page() ) {
        wp_enqueue_style( 'abril-hero-banner',   $components_uri . 'hero-banner.css',   [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-about-general', $components_uri . 'about-general.css', [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-picture-content', $components_uri . 'picture-content.css', [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-faq',           $components_uri . 'faq.css',            [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-sponsors',      $components_uri . 'section-sponsors.css', [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-section-subscribe', $components_uri . 'section-subscribe.css', [ 'abril-variables', 'abril-buttons' ], $version );
    }

    // Seção Oficineiros — home + página nossos-oficineiros
    if ( is_front_page() || is_page( 'nossos-oficineiros' ) ) {
        wp_enqueue_style(
            'abril-speakers',
            $components_uri . 'section-speakers.css',
            [ 'abril-variables' ],
            $version
        );
    }

    // Seção Programação — home + página de programação
    if ( is_front_page() || is_page( 'programacao' ) ) {
        wp_enqueue_style(
            'abril-schedule',
            $components_uri . 'section-schedule.css',
            [ 'abril-variables', 'abril-buttons' ],
            $version
        );
    }

    // Seção Ingressos — home + página de pacotes
    if ( is_front_page() || is_page( 'nossos-pacotes' ) || is_page( 'pacotes' ) ) {
        wp_enqueue_style(
            'abril-section-tickets',
            $components_uri . 'section-tickets.css',
            [ 'abril-variables', 'abril-buttons' ],
            $version
        );
    }

    // Seção Localização — home + página de localização
    if ( is_front_page() || is_page( 'localizacao' ) ) {
        wp_enqueue_style(
            'abril-section-location',
            $components_uri . 'section-location.css',
            [ 'abril-variables' ],
            $version
        );
    }

    // Busca — componentes de resultado e formulário
    if ( is_search() ) {
        wp_enqueue_style( 'abril-searchform',       $components_uri . 'searchform.css', [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-search-component', $components_uri . 'search.css',     [ 'abril-variables' ], $version );
    }

    // Formulário de contato
    if ( is_page_template( 'template-pages/contato.php' ) || is_page( 'contato' ) ) {
        wp_enqueue_style(
            'abril-form-contact',
            $components_uri . 'form-contact.css',
            [ 'abril-variables' ],
            $version
        );
    }

    // Formulário de inscrição
    if ( is_page_template( 'template-pages/subscribe.php' ) ) {
        wp_enqueue_style('abril-form-subscribe',     $components_uri . 'form-subscribe.css',    [ 'abril-variables' ], $version);
        wp_enqueue_style('abril-section-speakers',   $components_uri . 'section-speakers.css',  [ 'abril-variables', 'abril-buttons' ], $version);
        wp_enqueue_style( 'abril-schedule',          $components_uri . 'section-schedule.css',  [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style( 'abril-section-tickets',   $components_uri . 'section-tickets.css',   [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style( 'abril-section-location',  $components_uri . 'section-location.css',  [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-section-subscribe', $components_uri . 'section-subscribe.css', [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style( 'abril-sponsors',          $components_uri . 'section-sponsors.css',  [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-faq',               $components_uri . 'faq.css',               [ 'abril-variables' ], $version );
    }

    // Formulário de enviar comprovante
    if ( is_page_template( 'template-pages/enviar-comprovante.php' ) ) {
        wp_enqueue_style( 'abril-form-comprovante', $components_uri . 'form-comprovante.css', [ 'abril-variables' ], $version);
        wp_enqueue_style( 'abril-schedule',          $components_uri . 'section-schedule.css',  [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style('abril-section-speakers',   $components_uri . 'section-speakers.css',  [ 'abril-variables', 'abril-buttons' ], $version);
        wp_enqueue_style( 'abril-section-location',  $components_uri . 'section-location.css',  [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-sponsors',          $components_uri . 'section-sponsors.css',  [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-faq',               $components_uri . 'faq.css',               [ 'abril-variables' ], $version );
    }

    // Card de inscrição — área pessoal
    if ( is_page_template( 'template-pages/minha-inscricao.php' ) ) {
        wp_enqueue_style('abril-card-subscribe', $components_uri . 'card-subscribe.css', [ 'abril-variables' ], $version);
        wp_enqueue_style( 'abril-schedule',          $components_uri . 'section-schedule.css',  [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style( 'abril-section-tickets',   $components_uri . 'section-tickets.css',   [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style( 'abril-section-location',  $components_uri . 'section-location.css',  [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-section-subscribe', $components_uri . 'section-subscribe.css', [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style( 'abril-sponsors',          $components_uri . 'section-sponsors.css',  [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-faq',               $components_uri . 'faq.css',               [ 'abril-variables' ], $version );
    }

    // ─────────────────────────────────────────────────────────
    // 6. CSS específico por página
    //    Apenas single.css contém estilos próprios de página.
    //    Todos os demais componentes já estão carregados acima.
    // ─────────────────────────────────────────────────────────

    // Single post (oficineiro / homenageado)
    if ( is_single() ) {
        wp_enqueue_style( 'abril-single', $pages_css_uri . 'single.css', [ 'abril-variables', 'abril-buttons' ], $version);
        wp_enqueue_style( 'abril-schedule',          $components_uri . 'section-schedule.css',  [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style( 'abril-section-tickets',   $components_uri . 'section-tickets.css',   [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style( 'abril-section-location',  $components_uri . 'section-location.css',  [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-section-subscribe', $components_uri . 'section-subscribe.css', [ 'abril-variables', 'abril-buttons' ], $version );
        wp_enqueue_style( 'abril-sponsors',          $components_uri . 'section-sponsors.css',  [ 'abril-variables' ], $version );
        wp_enqueue_style( 'abril-faq',               $components_uri . 'faq.css',               [ 'abril-variables' ], $version );
    }

    // ─────────────────────────────────────────────────────────
    // 7. Scripts
    // ─────────────────────────────────────────────────────────
    wp_enqueue_script(
        'abril-main',
        $theme_uri . '/assets/js/main.js',
        [],
        $version,
        true
    );

    // Dados para o formulário de inscrição (PIX)
    if ( is_page_template( 'template-pages/subscribe.php' ) ) {
        $pix_opts = function_exists( 'abril_get_pagamento_options' ) ? abril_get_pagamento_options() : [];
        wp_localize_script( 'abril-main', 'abrilData', [
            'pixChave'  => $pix_opts['pix_chave']             ?? '',
            'pixNome'   => $pix_opts['pix_nome_beneficiario'] ?? 'Abril pra Angola',
            'pixCidade' => $pix_opts['pix_cidade']            ?? 'Sao Paulo',
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'abril_enqueue_styles_and_scripts' );
