<?php
/**
 * Subscriber Admin — Abril pra Angola
 *
 * - Permite ao assinante ver/editar apenas o seu próprio post de inscrito
 * - Redireciona automaticamente para o post após o login
 * - Limpa o menu do wp-admin para assinantes
 */

// ─────────────────────────────────────────────────────────────
// 1. PERMISSÃO — Assinante pode editar apenas o seu inscrito
// ─────────────────────────────────────────────────────────────
add_filter( 'user_has_cap', function ( $caps, $cap_requested, $args ) {
    // Só actua para utilizadores com papel de assinante
    $user = wp_get_current_user();
    if ( ! $user || ! in_array( 'subscriber', (array) $user->roles ) ) {
        return $caps;
    }

    // Verificar se é um pedido de permissão sobre um post específico
    if ( in_array( $args[0], [ 'edit_post', 'read_post', 'delete_post' ] ) && isset( $args[2] ) ) {
        $post_id   = (int) $args[2];
        $post      = get_post( $post_id );

        if ( $post && $post->post_type === 'inscrito' && (int) $post->post_author === (int) $user->ID ) {
            // É o próprio inscrito do utilizador — conceder acesso
            $caps['edit_posts']           = true;
            $caps['edit_published_posts'] = true;
            $caps['read_private_posts']   = true;
        }
    }

    // Permitir acesso geral ao wp-admin (necessário para o ecrã de edição carregar)
    if ( $args[0] === 'read' ) {
        $caps['read'] = true;
    }

    return $caps;
}, 10, 3 );


// ─────────────────────────────────────────────────────────────
// 2. REDIRECCIONAMENTO — Após login vai directo ao seu inscrito
// ─────────────────────────────────────────────────────────────
add_filter( 'login_redirect', function ( $redirect_to, $request, $user ) {
    if ( ! isset( $user->roles ) || ! is_array( $user->roles ) ) {
        return $redirect_to;
    }

    // Apenas para assinantes
    if ( in_array( 'subscriber', $user->roles ) && ! in_array( 'administrator', $user->roles ) ) {
        $inscrito = get_posts( [
            'post_type'      => 'inscrito',
            'author'         => $user->ID,
            'posts_per_page' => 1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ] );

        if ( ! empty( $inscrito ) ) {
            return admin_url( 'post.php?post=' . $inscrito[0]->ID . '&action=edit' );
        }
    }

    return $redirect_to;
}, 10, 3 );


// ─────────────────────────────────────────────────────────────
// 3. MENU LIMPO — Esconder itens desnecessários para assinantes
// ─────────────────────────────────────────────────────────────
add_action( 'admin_menu', function () {
    $user = wp_get_current_user();
    if ( ! $user || ! in_array( 'subscriber', (array) $user->roles ) ) return;

    // Remover itens do menu lateral
    remove_menu_page( 'index.php' );              // Dashboard
    remove_menu_page( 'edit-comments.php' );      // Comentários
    remove_menu_page( 'profile.php' );            // Perfil (opcional — manter se quiser)

    // Remover itens do menu do topo (toolbar)
    add_action( 'admin_bar_menu', function ( $wp_admin_bar ) {
        $wp_admin_bar->remove_node( 'comments' );
        $wp_admin_bar->remove_node( 'new-content' );
        $wp_admin_bar->remove_node( 'updates' );
        $wp_admin_bar->remove_node( 'wp-logo' );
    }, 999 );
} );


// ─────────────────────────────────────────────────────────────
// 4. BLOQUEIO — Assinante não acede a outros ecrãs do admin
// ─────────────────────────────────────────────────────────────
add_action( 'current_screen', function ( $screen ) {
    $user = wp_get_current_user();
    if ( ! $user || ! in_array( 'subscriber', (array) $user->roles ) ) return;
    if ( in_array( 'administrator', (array) $user->roles ) ) return;

    // Páginas permitidas para assinantes
    $permitidas = [ 'post', 'profile' ];

    if ( ! in_array( $screen->base, $permitidas ) ) {
        // Redirecionar para o seu inscrito se tentar aceder a outro ecrã
        $inscrito = get_posts( [
            'post_type'      => 'inscrito',
            'author'         => $user->ID,
            'posts_per_page' => 1,
            'post_status'    => 'publish',
        ] );

        if ( ! empty( $inscrito ) ) {
            wp_redirect( admin_url( 'post.php?post=' . $inscrito[0]->ID . '&action=edit' ) );
            exit;
        }
    }
} );


// ─────────────────────────────────────────────────────────────
// 5. CSS — Estilo personalizado no admin para assinantes
// ─────────────────────────────────────────────────────────────
add_action( 'admin_head', function () {
    $user = wp_get_current_user();
    if ( ! $user || ! in_array( 'subscriber', (array) $user->roles ) ) return;
    if ( in_array( 'administrator', (array) $user->roles ) ) return;
    ?>
    <style>
        /* Limpar o admin para assinantes */
        #adminmenumain, #adminmenuback, #adminmenuwrap { display: none !important; }
        #wpcontent, #wpfooter { margin-left: 0 !important; }
        #wpbody-content .wrap h1 .page-title-action { display: none !important; }
        /* Ocultar opções de publicação que o assinante não precisa */
        #delete-action, #minor-publishing, .misc-pub-section.misc-pub-post-status { display: none !important; }
    </style>
    <?php
} );


// ─────────────────────────────────────────────────────────────
// 6. ACF — Campo "Status" desactivado para assinantes
// ─────────────────────────────────────────────────────────────
add_filter( 'acf/prepare_field/key=field_inscrito_status', function ( $field ) {
    $user = wp_get_current_user();
    if ( ! $user || ! in_array( 'subscriber', (array) $user->roles ) ) return $field;
    if ( in_array( 'administrator', (array) $user->roles ) ) return $field;

    $field['disabled']  = 1;
    $field['readonly']  = 1;
    $field['instructions'] = '📌 O estado da inscrição é gerido pela organização.';

    return $field;
} );


// ─────────────────────────────────────────────────────────────
// 7. ACF — Ocultar aba "Metadados" e campos internos para assinantes
// ─────────────────────────────────────────────────────────────
add_filter( 'acf/prepare_field/key=field_tab_meta', function ( $field ) {
    $user = wp_get_current_user();
    if ( ! $user || ! in_array( 'subscriber', (array) $user->roles ) ) return $field;
    if ( in_array( 'administrator', (array) $user->roles ) ) return $field;

    return false; // Oculta a aba
} );

add_filter( 'acf/prepare_field/key=field_inscrito_user_id', function ( $field ) {
    $user = wp_get_current_user();
    if ( ! $user || ! in_array( 'subscriber', (array) $user->roles ) ) return $field;
    if ( in_array( 'administrator', (array) $user->roles ) ) return $field;

    return false; // Oculta o campo de utilizador
} );


