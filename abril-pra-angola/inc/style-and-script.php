<?php
/**
 * Enfileiramento de estilos e scripts — Abril pra Angola
 *
 * Estilos globais + CSS específico por página/template.
 */

function abril_enqueue_styles_and_scripts() {

    $theme_uri = get_template_directory_uri();
    $version   = '1.0.0';

    // ─────────────────────────────────────────────────────────
    // Estilos globais
    // ─────────────────────────────────────────────────────────

    // Reset, variáveis CSS e base tipográfica
    wp_enqueue_style(
        'abril-variables',
        $theme_uri . '/assets/css/variables.css',
        [],
        $version
    );

    // Folha de estilos principal do tema (style.css)
    wp_enqueue_style(
        'abril-style',
        get_stylesheet_uri(),
        [ 'abril-variables' ],
        $version
    );

    // ─────────────────────────────────────────────────────────
    // CSS específico por página
    // ─────────────────────────────────────────────────────────

    $pages_css_uri = $theme_uri . '/assets/css/pages/';

    /**
     * Mapeamento: condição WordPress → arquivo CSS
     *
     * Formato: [ callable_condition, handle, arquivo.css ]
     */
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
            'condition' => is_page( 'inscricao' ),
            'handle'    => 'abril-page-inscricao',
            'file'      => 'inscricao.css',
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

        // Single post (qualquer post individual)
        [
            'condition' => is_single(),
            'handle'    => 'abril-single',
            'file'      => 'single.css',
        ],

    ];

    foreach ( $page_styles as $style ) {
        if ( $style['condition'] ) {
            wp_enqueue_style(
                $style['handle'],
                $pages_css_uri . $style['file'],
                [ 'abril-style' ],
                $version
            );
        }
    }

    // ─────────────────────────────────────────────────────────
    // Scripts
    // ─────────────────────────────────────────────────────────

    wp_enqueue_script(
        'abril-main',
        $theme_uri . '/assets/js/main.js',
        [],
        $version,
        true // carrega no rodapé
    );
}
add_action( 'wp_enqueue_scripts', 'abril_enqueue_styles_and_scripts' );
