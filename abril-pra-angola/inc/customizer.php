<?php
/**
 * Customizações gerais da instalação WordPress
 * Abril pra Angola — customizer.php
 *
 * Aqui centralizamos todos os ajustes visuais e funcionais
 * que vão além do tema em si: tela de login, admin, etc.
 */


// ═══════════════════════════════════════════════════════════════
// 1. TELA DE LOGIN — Logotipo personalizado
// ═══════════════════════════════════════════════════════════════

/**
 * Substitui o logotipo do WordPress na tela de login
 * pelo logotipo do evento.
 */
add_action( 'login_enqueue_scripts', function () {
    $logo_url = content_url( 'uploads/2026/05/logotipo-abril-pra-angola-2027-compress.webp' );
    ?>
    <style>
        /* ── Logotipo ── */
        #login h1 a,
        .login h1 a {
            background-image:    url('<?php echo esc_url( $logo_url ); ?>') !important;
            background-size:     contain !important;
            background-repeat:   no-repeat !important;
            background-position: center center !important;
            width:               280px !important;
            height:              140px !important;
            display:             block !important;
            margin:              0 auto 20px !important;
        }
    </style>
    <?php
} );

/**
 * Altera o link do logotipo (de wordpress.org para o site do evento).
 */
add_filter( 'login_headerurl', function () {
    return home_url();
} );

/**
 * Altera o atributo title / aria-label do link do logotipo.
 */
add_filter( 'login_headertext', function () {
    return get_bloginfo( 'name' );
} );

