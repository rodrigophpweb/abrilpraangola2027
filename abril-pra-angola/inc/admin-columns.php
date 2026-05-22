<?php
/**
 * Admin Columns & Filters — Inscritos
 * Adiciona coluna de status e filtro na listagem do CPT Inscrito.
 */

// ─────────────────────────────────────────────────────────────
// 1. REGISTAR COLUNAS NA LISTAGEM
// ─────────────────────────────────────────────────────────────
add_filter( 'manage_inscrito_posts_columns', function ( $columns ) {
    // Inserir após o título
    $novos = [];
    foreach ( $columns as $key => $label ) {
        $novos[ $key ] = $label;
        if ( $key === 'title' ) {
            $novos['inscrito_email']  = '📧 E-mail';
            $novos['inscrito_pacote'] = '🎟️ Pacote';
            $novos['inscrito_status'] = '📌 Status';
        }
    }
    return $novos;
} );


// ─────────────────────────────────────────────────────────────
// 2. RENDERIZAR CONTEÚDO DAS COLUNAS
// ─────────────────────────────────────────────────────────────
add_action( 'manage_inscrito_posts_custom_column', function ( $column, $post_id ) {
    switch ( $column ) {

        case 'inscrito_email':
            $email = get_field( 'inscrito_email', $post_id );
            echo $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '—';
            break;

        case 'inscrito_pacote':
            $pacote_id = get_field( 'inscrito_pacote_id', $post_id );
            $pacote    = $pacote_id ? get_post( $pacote_id ) : null;
            echo $pacote ? esc_html( $pacote->post_title ) : '—';
            break;

        case 'inscrito_status':
            $status = get_field( 'inscrito_status', $post_id ) ?: 'pendente';
            $config = [
                'pendente'   => [ 'label' => 'Pendente',   'cor' => '#f0ad4e', 'icone' => '⏳' ],
                'confirmado' => [ 'label' => 'Confirmado', 'cor' => '#5cb85c', 'icone' => '✅' ],
                'cancelado'  => [ 'label' => 'Cancelado',  'cor' => '#d9534f', 'icone' => '❌' ],
            ];
            $item = $config[ $status ] ?? $config['pendente'];
            printf(
                '<span style="display:inline-block;padding:3px 10px;border-radius:12px;background:%s;color:#fff;font-size:12px;font-weight:600;">%s %s</span>',
                $item['cor'],
                $item['icone'],
                $item['label']
            );
            break;
    }
}, 10, 2 );


// ─────────────────────────────────────────────────────────────
// 3. TORNAR A COLUNA STATUS ORDENÁVEL
// ─────────────────────────────────────────────────────────────
add_filter( 'manage_edit-inscrito_sortable_columns', function ( $columns ) {
    $columns['inscrito_status'] = 'inscrito_status';
    return $columns;
} );

add_action( 'pre_get_posts', function ( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) return;
    if ( $query->get( 'post_type' ) !== 'inscrito' ) return;

    if ( $query->get( 'orderby' ) === 'inscrito_status' ) {
        $query->set( 'meta_key', 'inscrito_status' );
        $query->set( 'orderby',  'meta_value' );
    }
} );


// ─────────────────────────────────────────────────────────────
// 4. FILTRO POR STATUS NA LISTAGEM
// ─────────────────────────────────────────────────────────────
add_action( 'restrict_manage_posts', function ( $post_type ) {
    if ( $post_type !== 'inscrito' ) return;

    $atual = $_GET['filtro_status'] ?? '';
    ?>
    <select name="filtro_status" id="filtro_status">
        <option value="">— Todos os Status —</option>
        <option value="pendente"   <?php selected( $atual, 'pendente' ); ?>>⏳ Pendente</option>
        <option value="confirmado" <?php selected( $atual, 'confirmado' ); ?>>✅ Confirmado</option>
        <option value="cancelado"  <?php selected( $atual, 'cancelado' ); ?>>❌ Cancelado</option>
    </select>
    <?php
} );

add_action( 'pre_get_posts', function ( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) return;
    if ( $query->get( 'post_type' ) !== 'inscrito' ) return;
    if ( empty( $_GET['filtro_status'] ) ) return;

    $status = sanitize_text_field( $_GET['filtro_status'] );
    $meta_query = $query->get( 'meta_query' ) ?: [];
    $meta_query[] = [
        'key'     => 'inscrito_status',
        'value'   => $status,
        'compare' => '=',
    ];
    $query->set( 'meta_query', $meta_query );
} );


// ─────────────────────────────────────────────────────────────
// 5. CSS — Ajuste visual da coluna de status
// ─────────────────────────────────────────────────────────────
add_action( 'admin_head', function () {
    $screen = get_current_screen();
    if ( ! $screen || $screen->post_type !== 'inscrito' ) return;
    ?>
    <style>
        .column-inscrito_status { width: 130px; text-align: center; }
        .column-inscrito_email  { width: 200px; }
        .column-inscrito_pacote { width: 160px; }
    </style>
    <?php
} );

