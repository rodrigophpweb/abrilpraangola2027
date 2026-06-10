<?php
/**
 * Abril pra Angola - functions.php
 */


// Carrega ficheiros do tema
require_once get_template_directory() . '/inc/style-and-script.php';
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/meta-boxes.php';
require_once get_template_directory() . '/inc/form-handler.php';
require_once get_template_directory() . '/inc/contact-handler.php';
require_once get_template_directory() . '/inc/subscriber-admin.php';
require_once get_template_directory() . '/inc/admin-columns.php';
require_once get_template_directory() . '/inc/page-options.php';
require_once get_template_directory() . '/inc/svg-support.php';
require_once get_template_directory() . '/inc/customizer.php';

// Registra 'pkg_id' como query var do WordPress para não conflitar com o CPT 'pacote'
// Permite usar ?pkg_id=22 para pré-selecionar um pacote na página de inscrição
add_filter( 'query_vars', function ( array $vars ): array {
    $vars[] = 'pkg_id';
    return $vars;
} );

// ─────────────────────────────────────────────
// MailHog — Redirecionar e-mails para o servidor
// local de desenvolvimento (apenas em local)
// ─────────────────────────────────────────────
add_action( 'phpmailer_init', function ( $phpmailer ) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = 'mailhog';
    $phpmailer->Port       = 1025;
    $phpmailer->SMTPAuth   = false;
    $phpmailer->SMTPSecure = '';
    $phpmailer->From       = 'noreply@abril-pra-angola.com';
    $phpmailer->FromName   = 'Abril pra Angola';
} );

// Definir remetente padrão para todos os wp_mail
add_filter( 'wp_mail_from',      fn() => 'noreply@abril-pra-angola.com' );
add_filter( 'wp_mail_from_name', fn() => 'Abril pra Angola' );


// Suporte a recursos do tema
function abril_pra_angola_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'automatic-feed-links' );

    // Suporte ao logotipo via Personalizar → Identidade do site
    // Logo quadrado 300×300 — tamanho medium
    add_theme_support( 'custom-logo', [
        'height'               => 300,
        'width'                => 300,
        'flex-height'          => false,
        'flex-width'           => false,
        'unlink-homepage-logo' => false,
    ] );

    register_nav_menus( [
        'primary' => __( 'Menu Principal', 'abril-pra-angola' ),
    ] );
}
add_action( 'after_setup_theme', 'abril_pra_angola_setup' );


/**
 * Retorna a tag <img> do logotipo usando o tamanho 'thumbnail' (150×150).
 * Fallback para o nome do site se não houver logotipo configurado.
 *
 * @return string HTML da imagem ou span de fallback.
 */
function abril_get_logo(): string {
    $logo_id = get_theme_mod( 'custom_logo' );

    if ( ! $logo_id ) {
        return '<span class="site-header__logo-fallback" itemprop="name">'
             . esc_html( get_bloginfo( 'name' ) )
             . '</span>';
    }

    return wp_get_attachment_image(
        $logo_id,
        'medium',             // tamanho 300×300 — sem upscaling
        false,
        [
            'class'    => 'site-header__logo-img',
            'itemprop' => 'logo',
            'alt'      => esc_attr( get_bloginfo( 'name' ) ),
            'loading'  => 'eager',   // logotipo carrega imediatamente (above the fold)
        ]
    );
}


