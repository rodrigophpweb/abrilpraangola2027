<?php
/**
 * ACF Field Groups — Abril pra Angola
 * Registra os campos de: Pacotes, Inscritos e Fotos do Evento.
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

// ═══════════════════════════════════════════════════════════════
// 1. PACOTES — Preço, Link Mercado Pago e Descrição
// ═══════════════════════════════════════════════════════════════
acf_add_local_field_group( [
    'key'      => 'group_pacote_detalhes',
    'title'    => 'Detalhes do Pacote',
    'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'pacote' ] ] ],
    'position' => 'normal',
    'style'    => 'seamless',
    'fields'   => [
        [
            'key'           => 'field_pacote_preco',
            'name'          => 'pacote_preco',
            'label'         => '💰 Preço (R$)',
            'type'          => 'number',
            'min'           => 0,
            'step'          => '0.01',
            'prepend'       => 'R$',
            'required'      => 1,
        ],
        [
            'key'           => 'field_pacote_link_mp',
            'name'          => 'pacote_link_mp',
            'label'         => '🔗 Link Mercado Pago (Cartão)',
            'type'          => 'url',
            'placeholder'   => 'https://mpago.la/...',
            'instructions'  => 'Link gerado no Mercado Pago para pagamento com cartão de crédito.',
        ],
        [
            'key'           => 'field_pacote_descricao',
            'name'          => 'pacote_descricao',
            'label'         => '📋 O que está incluído',
            'type'          => 'textarea',
            'rows'          => 5,
            'instructions'  => 'Liste os itens incluídos no pacote (um por linha).',
        ],
    ],
] );


// ═══════════════════════════════════════════════════════════════
// 2. INSCRITOS — Todos os campos do formulário de inscrição
// ═══════════════════════════════════════════════════════════════
acf_add_local_field_group( [
    'key'      => 'group_inscrito_dados',
    'title'    => '📋 Dados da Inscrição',
    'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'inscrito' ] ] ],
    'position' => 'normal',
    'style'    => 'default',
    'fields'   => [

        // ── Separador: Status ──────────────────────────
        [
            'key'           => 'field_inscrito_status',
            'name'          => 'inscrito_status',
            'label'         => '📌 Status da Inscrição',
            'type'          => 'select',
            'choices'       => [
                'pendente'   => '⏳ Pendente',
                'confirmado' => '✅ Confirmado',
                'cancelado'  => '❌ Cancelado',
            ],
            'default_value' => 'pendente',
            'allow_null'    => 0,
            'wrapper'       => [ 'width' => '30' ],
            'instructions'  => 'Ao selecionar "Confirmado" e guardar, o e-mail de confirmação será enviado automaticamente.',
        ],

        // ── Separador: Dados Pessoais ──────────────────
        [
            'key'   => 'field_tab_pessoais',
            'label' => '👤 Dados Pessoais',
            'type'  => 'tab',
        ],
        [
            'key'         => 'field_inscrito_email',
            'name'        => 'inscrito_email',
            'label'       => 'E-mail',
            'type'        => 'email',
            'required'    => 1,
            'wrapper'     => [ 'width' => '50' ],
        ],
        [
            'key'         => 'field_inscrito_celular',
            'name'        => 'inscrito_celular',
            'label'       => 'Celular (WhatsApp)',
            'type'        => 'text',
            'required'    => 1,
            'wrapper'     => [ 'width' => '50' ],
        ],

        // ── Separador: Dados de Capoeira ───────────────
        [
            'key'   => 'field_tab_capoeira',
            'label' => '🥋 Dados de Capoeira',
            'type'  => 'tab',
        ],
        [
            'key'     => 'field_inscrito_associacao',
            'name'    => 'inscrito_associacao',
            'label'   => 'Associação / Grupo / Escola',
            'type'    => 'text',
            'wrapper' => [ 'width' => '50' ],
        ],
        [
            'key'     => 'field_inscrito_apelido',
            'name'    => 'inscrito_apelido',
            'label'   => 'Apelido de Capoeira',
            'type'    => 'text',
            'wrapper' => [ 'width' => '25' ],
        ],
        [
            'key'     => 'field_inscrito_graduacao',
            'name'    => 'inscrito_graduacao',
            'label'   => 'Graduação',
            'type'    => 'text',
            'wrapper' => [ 'width' => '25' ],
        ],

        // ── Separador: Evento ──────────────────────────
        [
            'key'   => 'field_tab_evento',
            'label' => '🎽 Informações do Evento',
            'type'  => 'tab',
        ],
        [
            'key'           => 'field_inscrito_camiseta',
            'name'          => 'inscrito_camiseta',
            'label'         => 'Tamanho da Camiseta',
            'type'          => 'button_group',
            'choices'       => [ 'P' => 'P', 'M' => 'M', 'G' => 'G', 'GG' => 'GG' ],
            'required'      => 1,
            'wrapper'       => [ 'width' => '40' ],
        ],
        [
            'key'           => 'field_inscrito_alergia_alimento',
            'name'          => 'inscrito_alergia_alimento',
            'label'         => 'Alergia Alimentar?',
            'type'          => 'select',
            'choices'       => [ 'nao' => 'Não', 'sim' => 'Sim' ],
            'default_value' => 'nao',
            'wrapper'       => [ 'width' => '30' ],
        ],
        [
            'key'               => 'field_inscrito_alergia_alimento_desc',
            'name'              => 'inscrito_alergia_alimento_desc',
            'label'             => 'Qual alimento?',
            'type'              => 'text',
            'placeholder'       => 'Descreva a alergia alimentar',
            'wrapper'           => [ 'width' => '30' ],
            'conditional_logic' => [
                [
                    [
                        'field'    => 'field_inscrito_alergia_alimento',
                        'operator' => '==',
                        'value'    => 'sim',
                    ],
                ],
            ],
        ],
        [
            'key'           => 'field_inscrito_alergia_remedio',
            'name'          => 'inscrito_alergia_remedio',
            'label'         => 'Alergia a Medicamento?',
            'type'          => 'select',
            'choices'       => [ 'nao' => 'Não', 'sim' => 'Sim' ],
            'default_value' => 'nao',
            'wrapper'       => [ 'width' => '30' ],
        ],
        [
            'key'               => 'field_inscrito_alergia_remedio_desc',
            'name'              => 'inscrito_alergia_remedio_desc',
            'label'             => 'Qual medicamento?',
            'type'              => 'text',
            'placeholder'       => 'Descreva a alergia a medicamento',
            'wrapper'           => [ 'width' => '30' ],
            'conditional_logic' => [
                [
                    [
                        'field'    => 'field_inscrito_alergia_remedio',
                        'operator' => '==',
                        'value'    => 'sim',
                    ],
                ],
            ],
        ],
        [
            'key'           => 'field_inscrito_transporte',
            'name'          => 'inscrito_transporte',
            'label'         => 'Transporte até a Chácara (R$ 70,00)',
            'type'          => 'true_false',
            'ui'            => 1,
            'ui_on_text'    => 'Sim',
            'ui_off_text'   => 'Não',
            'wrapper'       => [ 'width' => '40' ],
        ],

        // ── Separador: Pagamento ───────────────────────
        [
            'key'   => 'field_tab_pagamento',
            'label' => '💳 Pagamento',
            'type'  => 'tab',
        ],
        [
            'key'     => 'field_inscrito_pacote_id',
            'name'    => 'inscrito_pacote_id',
            'label'   => 'Pacote Selecionado',
            'type'    => 'post_object',
            'post_type' => [ 'pacote' ],
            'return_format' => 'id',
            'allow_null' => 1,
            'wrapper' => [ 'width' => '50' ],
        ],
        [
            'key'           => 'field_inscrito_forma_pagamento',
            'name'          => 'inscrito_forma_pagamento',
            'label'         => 'Forma de Pagamento',
            'type'          => 'select',
            'choices'       => [
                'deposito' => 'Depósito Bancário',
                'pix'      => 'PIX',
                'cartao'   => 'Cartão de Crédito (Mercado Pago)',
            ],
            'allow_null'    => 1,
            'wrapper'       => [ 'width' => '50' ],
        ],
        [
            'key'           => 'field_inscrito_data_pagamento',
            'name'          => 'inscrito_data_pagamento',
            'label'         => 'Data do Pagamento',
            'type'          => 'date_picker',
            'display_format' => 'd/m/Y',
            'return_format' => 'd/m/Y',
            'wrapper'       => [ 'width' => '50' ],
        ],
        [
            'key'           => 'field_inscrito_comprovante',
            'name'          => 'inscrito_comprovante',
            'label'         => '🧾 Comprovante de Pagamento',
            'type'          => 'file',
            'return_format' => 'array',
            'library'       => 'all',
            'mime_types'    => 'jpg, jpeg, png, pdf',
            'wrapper'       => [ 'width' => '50' ],
        ],

        // ── Separador: Metadados ───────────────────────
        [
            'key'   => 'field_tab_meta',
            'label' => '⚙️ Metadados',
            'type'  => 'tab',
        ],
        [
            'key'          => 'field_inscrito_user_id',
            'name'         => 'inscrito_user_id',
            'label'        => 'Utilizador WordPress',
            'type'         => 'user',
            'return_format' => 'id',
            'allow_null'   => 1,
        ],
    ],
] );


// ─────────────────────────────────────────────────────────────
// Hook: disparar e-mail ao mudar status para "confirmado"
// ─────────────────────────────────────────────────────────────
add_action( 'acf/save_post', function( $post_id ) {
    if ( get_post_type( $post_id ) !== 'inscrito' ) return;

    $status_anterior = get_post_meta( $post_id, '_status_anterior_inscrito', true );
    $status_novo     = get_field( 'inscrito_status', $post_id );

    if ( $status_anterior !== 'confirmado' && $status_novo === 'confirmado' ) {
        abril_enviar_email_confirmacao( $post_id );
    }

    update_post_meta( $post_id, '_status_anterior_inscrito', $status_novo );
}, 20 );


// ═══════════════════════════════════════════════════════════════
// 3. FOTOS DO EVENTO — Status de aprovação e autor
// ═══════════════════════════════════════════════════════════════
acf_add_local_field_group( [
    'key'      => 'group_foto_evento',
    'title'    => '📸 Informações da Foto',
    'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'foto_evento' ] ] ],
    'position' => 'side',
    'style'    => 'default',
    'fields'   => [
        [
            'key'           => 'field_foto_status',
            'name'          => 'foto_status',
            'label'         => 'Status de Aprovação',
            'type'          => 'select',
            'choices'       => [
                'pendente'   => '⏳ Aguardando aprovação',
                'aprovado'   => '✅ Aprovada',
                'rejeitado'  => '❌ Rejeitada',
            ],
            'default_value' => 'pendente',
            'required'      => 1,
        ],
        [
            'key'           => 'field_foto_user_id',
            'name'          => 'foto_user_id',
            'label'         => 'Enviada por',
            'type'          => 'user',
            'return_format' => 'id',
            'allow_null'    => 1,
        ],
        [
            'key'           => 'field_foto_edicao',
            'name'          => 'foto_edicao',
            'label'         => 'Edição do Evento',
            'type'          => 'text',
            'default_value' => '2027',
        ],
    ],
] );

// Hook: publicar/despublicar foto conforme aprovação
add_action( 'acf/save_post', function( $post_id ) {
    if ( get_post_type( $post_id ) !== 'foto_evento' ) return;

    $status = get_field( 'foto_status', $post_id );
    $post_status = $status === 'aprovado' ? 'publish' : 'pending';

    remove_action( 'acf/save_post', __FUNCTION__ );
    wp_update_post( [ 'ID' => $post_id, 'post_status' => $post_status ] );
}, 20 );
