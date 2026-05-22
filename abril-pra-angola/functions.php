<?php
/**
 * Abril pra Angola - functions.php
 */


// Carrega ficheiros do tema
require_once get_template_directory() . '/inc/style-and-script.php';
require_once get_template_directory() . '/inc/custom-post-types.php';
require_once get_template_directory() . '/inc/meta-boxes.php';
require_once get_template_directory() . '/inc/form-handler.php';
require_once get_template_directory() . '/inc/subscriber-admin.php';
require_once get_template_directory() . '/inc/admin-columns.php';

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

    register_nav_menus( [
        'primary' => __( 'Menu Principal', 'abril-pra-angola' ),
    ] );
}
add_action( 'after_setup_theme', 'abril_pra_angola_setup' );

