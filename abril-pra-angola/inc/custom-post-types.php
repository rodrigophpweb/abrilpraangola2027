<?php
/**
 * Custom Post Types - Abril pra Angola
 *
 * Registra os seguintes CPTs:
 *  - Oficineiros (Speakers)
 *  - Homenageados
 *  - Patrocinadores (Sponsors)
 *  - Pacotes
 *  - Agenda
 */

// ─────────────────────────────────────────────
// 1. OFICINEIROS (SPEAKERS)
// ─────────────────────────────────────────────
function abril_register_cpt_oficineiros() {
    $labels = [
        'name'                  => __( 'Oficineiros', 'abril-pra-angola' ),
        'singular_name'         => __( 'Oficineiro', 'abril-pra-angola' ),
        'menu_name'             => __( 'Oficineiros', 'abril-pra-angola' ),
        'add_new'               => __( 'Adicionar Novo', 'abril-pra-angola' ),
        'add_new_item'          => __( 'Adicionar Novo Oficineiro', 'abril-pra-angola' ),
        'edit_item'             => __( 'Editar Oficineiro', 'abril-pra-angola' ),
        'new_item'              => __( 'Novo Oficineiro', 'abril-pra-angola' ),
        'view_item'             => __( 'Ver Oficineiro', 'abril-pra-angola' ),
        'search_items'          => __( 'Buscar Oficineiros', 'abril-pra-angola' ),
        'not_found'             => __( 'Nenhum oficineiro encontrado', 'abril-pra-angola' ),
        'not_found_in_trash'    => __( 'Nenhum oficineiro na lixeira', 'abril-pra-angola' ),
        'all_items'             => __( 'Todos os Oficineiros', 'abril-pra-angola' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-businessperson',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'oficineiros' ],
        'capability_type'    => 'post',
    ];

    register_post_type( 'oficineiro', $args );
}
add_action( 'init', 'abril_register_cpt_oficineiros' );


// ─────────────────────────────────────────────
// 2. HOMENAGEADOS
// ─────────────────────────────────────────────
function abril_register_cpt_homenageados() {
    $labels = [
        'name'                  => __( 'Homenageados', 'abril-pra-angola' ),
        'singular_name'         => __( 'Homenageado', 'abril-pra-angola' ),
        'menu_name'             => __( 'Homenageados', 'abril-pra-angola' ),
        'add_new'               => __( 'Adicionar Novo', 'abril-pra-angola' ),
        'add_new_item'          => __( 'Adicionar Novo Homenageado', 'abril-pra-angola' ),
        'edit_item'             => __( 'Editar Homenageado', 'abril-pra-angola' ),
        'new_item'              => __( 'Novo Homenageado', 'abril-pra-angola' ),
        'view_item'             => __( 'Ver Homenageado', 'abril-pra-angola' ),
        'search_items'          => __( 'Buscar Homenageados', 'abril-pra-angola' ),
        'not_found'             => __( 'Nenhum homenageado encontrado', 'abril-pra-angola' ),
        'not_found_in_trash'    => __( 'Nenhum homenageado na lixeira', 'abril-pra-angola' ),
        'all_items'             => __( 'Todos os Homenageados', 'abril-pra-angola' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-awards',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'homenageados' ],
        'capability_type'    => 'post',
    ];

    register_post_type( 'homenageado', $args );
}
add_action( 'init', 'abril_register_cpt_homenageados' );


// ─────────────────────────────────────────────
// 3. PATROCINADORES (SPONSORS)
// ─────────────────────────────────────────────
function abril_register_cpt_patrocinadores() {
    $labels = [
        'name'                  => __( 'Patrocinadores', 'abril-pra-angola' ),
        'singular_name'         => __( 'Patrocinador', 'abril-pra-angola' ),
        'menu_name'             => __( 'Patrocinadores', 'abril-pra-angola' ),
        'add_new'               => __( 'Adicionar Novo', 'abril-pra-angola' ),
        'add_new_item'          => __( 'Adicionar Novo Patrocinador', 'abril-pra-angola' ),
        'edit_item'             => __( 'Editar Patrocinador', 'abril-pra-angola' ),
        'new_item'              => __( 'Novo Patrocinador', 'abril-pra-angola' ),
        'view_item'             => __( 'Ver Patrocinador', 'abril-pra-angola' ),
        'search_items'          => __( 'Buscar Patrocinadores', 'abril-pra-angola' ),
        'not_found'             => __( 'Nenhum patrocinador encontrado', 'abril-pra-angola' ),
        'not_found_in_trash'    => __( 'Nenhum patrocinador na lixeira', 'abril-pra-angola' ),
        'all_items'             => __( 'Todos os Patrocinadores', 'abril-pra-angola' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-building',
        'supports'           => [ 'title', 'thumbnail', 'custom-fields' ],
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'patrocinadores' ],
        'capability_type'    => 'post',
    ];

    register_post_type( 'patrocinador', $args );
}
add_action( 'init', 'abril_register_cpt_patrocinadores' );


// Taxonomia: Nível de Patrocínio (ex.: Ouro, Prata, Bronze)
function abril_register_tax_nivel_patrocinio() {
    $labels = [
        'name'              => __( 'Níveis de Patrocínio', 'abril-pra-angola' ),
        'singular_name'     => __( 'Nível de Patrocínio', 'abril-pra-angola' ),
        'search_items'      => __( 'Buscar Nível', 'abril-pra-angola' ),
        'all_items'         => __( 'Todos os Níveis', 'abril-pra-angola' ),
        'edit_item'         => __( 'Editar Nível', 'abril-pra-angola' ),
        'update_item'       => __( 'Atualizar Nível', 'abril-pra-angola' ),
        'add_new_item'      => __( 'Adicionar Novo Nível', 'abril-pra-angola' ),
        'new_item_name'     => __( 'Novo Nível', 'abril-pra-angola' ),
        'menu_name'         => __( 'Níveis', 'abril-pra-angola' ),
    ];

    register_taxonomy( 'nivel_patrocinio', [ 'patrocinador' ], [
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'nivel-patrocinio' ],
    ] );
}
add_action( 'init', 'abril_register_tax_nivel_patrocinio' );


// ─────────────────────────────────────────────
// 4. PACOTES
// ─────────────────────────────────────────────
function abril_register_cpt_pacotes() {
    $labels = [
        'name'                  => __( 'Pacotes', 'abril-pra-angola' ),
        'singular_name'         => __( 'Pacote', 'abril-pra-angola' ),
        'menu_name'             => __( 'Pacotes', 'abril-pra-angola' ),
        'add_new'               => __( 'Adicionar Novo', 'abril-pra-angola' ),
        'add_new_item'          => __( 'Adicionar Novo Pacote', 'abril-pra-angola' ),
        'edit_item'             => __( 'Editar Pacote', 'abril-pra-angola' ),
        'new_item'              => __( 'Novo Pacote', 'abril-pra-angola' ),
        'view_item'             => __( 'Ver Pacote', 'abril-pra-angola' ),
        'search_items'          => __( 'Buscar Pacotes', 'abril-pra-angola' ),
        'not_found'             => __( 'Nenhum pacote encontrado', 'abril-pra-angola' ),
        'not_found_in_trash'    => __( 'Nenhum pacote na lixeira', 'abril-pra-angola' ),
        'all_items'             => __( 'Todos os Pacotes', 'abril-pra-angola' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-tickets-alt',
        'supports'           => [ 'title', 'editor', 'custom-fields' ],
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'pacotes' ],
        'capability_type'    => 'post',
    ];

    register_post_type( 'pacote', $args );
}
add_action( 'init', 'abril_register_cpt_pacotes' );


// ─────────────────────────────────────────────
// 5. AGENDA
// ─────────────────────────────────────────────
function abril_register_cpt_agenda() {
    $labels = [
        'name'                  => __( 'Agenda', 'abril-pra-angola' ),
        'singular_name'         => __( 'Evento da Agenda', 'abril-pra-angola' ),
        'menu_name'             => __( 'Agenda', 'abril-pra-angola' ),
        'add_new'               => __( 'Adicionar Novo', 'abril-pra-angola' ),
        'add_new_item'          => __( 'Adicionar Novo Evento', 'abril-pra-angola' ),
        'edit_item'             => __( 'Editar Evento', 'abril-pra-angola' ),
        'new_item'              => __( 'Novo Evento', 'abril-pra-angola' ),
        'view_item'             => __( 'Ver Evento', 'abril-pra-angola' ),
        'search_items'          => __( 'Buscar Eventos', 'abril-pra-angola' ),
        'not_found'             => __( 'Nenhum evento encontrado', 'abril-pra-angola' ),
        'not_found_in_trash'    => __( 'Nenhum evento na lixeira', 'abril-pra-angola' ),
        'all_items'             => __( 'Todos os Eventos', 'abril-pra-angola' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'agenda' ],
        'capability_type'    => 'post',
    ];

    register_post_type( 'agenda', $args );
}
add_action( 'init', 'abril_register_cpt_agenda' );


// Taxonomia: Dia / Categoria do Evento
function abril_register_tax_categoria_agenda() {
    $labels = [
        'name'              => __( 'Categorias da Agenda', 'abril-pra-angola' ),
        'singular_name'     => __( 'Categoria da Agenda', 'abril-pra-angola' ),
        'search_items'      => __( 'Buscar Categoria', 'abril-pra-angola' ),
        'all_items'         => __( 'Todas as Categorias', 'abril-pra-angola' ),
        'edit_item'         => __( 'Editar Categoria', 'abril-pra-angola' ),
        'update_item'       => __( 'Atualizar Categoria', 'abril-pra-angola' ),
        'add_new_item'      => __( 'Adicionar Nova Categoria', 'abril-pra-angola' ),
        'new_item_name'     => __( 'Nova Categoria', 'abril-pra-angola' ),
        'menu_name'         => __( 'Categorias', 'abril-pra-angola' ),
    ];

    register_taxonomy( 'categoria_agenda', [ 'agenda' ], [
        'labels'            => $labels,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'categoria-agenda' ],
    ] );
}
add_action( 'init', 'abril_register_tax_categoria_agenda' );


// ─────────────────────────────────────────────
// 6. INSCRITOS
// ─────────────────────────────────────────────
function abril_register_cpt_inscritos() {
    $labels = [
        'name'                  => __( 'Inscritos', 'abril-pra-angola' ),
        'singular_name'         => __( 'Inscrito', 'abril-pra-angola' ),
        'menu_name'             => __( 'Inscritos', 'abril-pra-angola' ),
        'add_new'               => __( 'Adicionar Novo', 'abril-pra-angola' ),
        'add_new_item'          => __( 'Adicionar Novo Inscrito', 'abril-pra-angola' ),
        'edit_item'             => __( 'Editar Inscrito', 'abril-pra-angola' ),
        'new_item'              => __( 'Novo Inscrito', 'abril-pra-angola' ),
        'view_item'             => __( 'Ver Inscrito', 'abril-pra-angola' ),
        'search_items'          => __( 'Buscar Inscritos', 'abril-pra-angola' ),
        'not_found'             => __( 'Nenhum inscrito encontrado', 'abril-pra-angola' ),
        'not_found_in_trash'    => __( 'Nenhum inscrito na lixeira', 'abril-pra-angola' ),
        'all_items'             => __( 'Todos os Inscritos', 'abril-pra-angola' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => false,
        'menu_icon'          => 'dashicons-id-alt',
        'supports'           => [ 'title', 'custom-fields' ],
        'has_archive'        => false,
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
    ];

    register_post_type( 'inscrito', $args );
}
add_action( 'init', 'abril_register_cpt_inscritos' );


// Taxonomia: Edição do Evento (2024, 2027, ...)
function abril_register_tax_edicao_evento() {
    $labels = [
        'name'          => __( 'Edições do Evento', 'abril-pra-angola' ),
        'singular_name' => __( 'Edição do Evento', 'abril-pra-angola' ),
        'menu_name'     => __( 'Edições', 'abril-pra-angola' ),
        'all_items'     => __( 'Todas as Edições', 'abril-pra-angola' ),
        'add_new_item'  => __( 'Adicionar Nova Edição', 'abril-pra-angola' ),
    ];

    register_taxonomy( 'edicao_evento', [ 'inscrito', 'foto_evento' ], [
        'labels'            => $labels,
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_in_rest'      => false,
        'show_admin_column' => true,
        'rewrite'           => false,
    ] );
}
add_action( 'init', 'abril_register_tax_edicao_evento' );


// ─────────────────────────────────────────────
// 7. FOTOS DO EVENTO (Galeria pós-evento)
// ─────────────────────────────────────────────
function abril_register_cpt_fotos_evento() {
    $labels = [
        'name'                  => __( 'Fotos do Evento', 'abril-pra-angola' ),
        'singular_name'         => __( 'Foto do Evento', 'abril-pra-angola' ),
        'menu_name'             => __( 'Galeria', 'abril-pra-angola' ),
        'add_new'               => __( 'Adicionar Nova', 'abril-pra-angola' ),
        'add_new_item'          => __( 'Adicionar Nova Foto', 'abril-pra-angola' ),
        'edit_item'             => __( 'Editar Foto', 'abril-pra-angola' ),
        'all_items'             => __( 'Todas as Fotos', 'abril-pra-angola' ),
        'not_found'             => __( 'Nenhuma foto encontrada', 'abril-pra-angola' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => false,
        'menu_icon'          => 'dashicons-format-gallery',
        'supports'           => [ 'title', 'thumbnail', 'custom-fields' ],
        'has_archive'        => false,
        'capability_type'    => 'post',
    ];

    register_post_type( 'foto_evento', $args );
}
add_action( 'init', 'abril_register_cpt_fotos_evento' );


// ─────────────────────────────────────────────
// 8. FAQ (Perguntas Frequentes)
// ─────────────────────────────────────────────
function abril_register_cpt_faq() {
    $labels = [
        'name'               => __( 'FAQ', 'abril-pra-angola' ),
        'singular_name'      => __( 'Pergunta FAQ', 'abril-pra-angola' ),
        'menu_name'          => __( 'FAQ', 'abril-pra-angola' ),
        'add_new'            => __( 'Adicionar Novo', 'abril-pra-angola' ),
        'add_new_item'       => __( 'Adicionar Nova Pergunta', 'abril-pra-angola' ),
        'edit_item'          => __( 'Editar Pergunta', 'abril-pra-angola' ),
        'new_item'           => __( 'Nova Pergunta', 'abril-pra-angola' ),
        'view_item'          => __( 'Ver Pergunta', 'abril-pra-angola' ),
        'search_items'       => __( 'Buscar Perguntas', 'abril-pra-angola' ),
        'not_found'          => __( 'Nenhuma pergunta encontrada', 'abril-pra-angola' ),
        'not_found_in_trash' => __( 'Nenhuma pergunta na lixeira', 'abril-pra-angola' ),
        'all_items'          => __( 'Todas as Perguntas', 'abril-pra-angola' ),
    ];

    $args = [
        'labels'          => $labels,
        'public'          => true,
        'show_ui'         => true,
        'show_in_menu'    => true,
        'show_in_rest'    => true,   // Habilita Gutenberg e exposição na REST API (rastreável por bots)
        'menu_icon'       => 'dashicons-editor-help',
        'supports'        => [ 'title', 'editor', 'page-attributes' ], // title = Pergunta | editor = Resposta | page-attributes = Ordem
        'has_archive'     => false,
        'rewrite'         => [ 'slug' => 'faq' ],
        'capability_type' => 'post',
    ];

    register_post_type( 'faq', $args );
}
add_action( 'init', 'abril_register_cpt_faq' );


