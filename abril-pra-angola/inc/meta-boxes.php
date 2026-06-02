<?php
/**
 * ACF Field Groups — Abril pra Angola
 * Registra os campos de: Pacotes, Inscritos e Fotos do Evento.
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

// ═══════════════════════════════════════════════════════════════
// 1. PACOTES — Preços (à vista / cartão), Link Mercado Pago e Descrição
// ═══════════════════════════════════════════════════════════════
acf_add_local_field_group( [
    'key'      => 'group_pacote_detalhes',
    'title'    => 'Detalhes do Pacote',
    'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'pacote' ] ] ],
    'position' => 'normal',
    'style'    => 'seamless',
    'fields'   => [
        [
            'key'          => 'field_pacote_preco_avista',
            'name'         => 'pacote_preco_avista',
            'label'        => '💰 Preço à Vista (Depósito / PIX)',
            'type'         => 'number',
            'min'          => 0,
            'step'         => '0.01',
            'prepend'      => 'R$',
            'required'     => 1,
            'instructions' => 'Valor para pagamento via depósito bancário ou PIX.',
            'wrapper'      => [ 'width' => '50' ],
        ],
        [
            'key'          => 'field_pacote_preco_cartao',
            'name'         => 'pacote_preco_cartao',
            'label'        => '💳 Preço no Cartão de Crédito',
            'type'         => 'number',
            'min'          => 0,
            'step'         => '0.01',
            'prepend'      => 'R$',
            'required'     => 1,
            'instructions' => 'Valor para pagamento via cartão de crédito (Mercado Pago). Pode ser parcelado em até 2x.',
            'wrapper'      => [ 'width' => '50' ],
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
            'key'            => 'field_pacote_validade',
            'name'           => 'pacote_validade',
            'label'          => '📅 Validade do Ingresso',
            'type'           => 'date_picker',
            'display_format' => 'd/m/Y',
            'return_format'  => 'd/m/Y',
            'first_day'      => 0,
            'instructions'   => 'Data limite para compra deste pacote.',
            'wrapper'        => [ 'width' => '50' ],
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
            'key'          => 'field_inscrito_valor_total',
            'name'         => 'inscrito_valor_total',
            'label'        => '💲 Valor Total Cobrado (R$)',
            'type'         => 'number',
            'min'          => 0,
            'step'         => '0.01',
            'prepend'      => 'R$',
            'instructions' => 'Calculado automaticamente no momento da inscrição (inclui pacote + transporte se aplicável, pelo método de pagamento escolhido).',
            'wrapper'      => [ 'width' => '50' ],
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
// 3. PATROCINADORES — Link / URL do patrocinador
// ═══════════════════════════════════════════════════════════════
acf_add_local_field_group( [
    'key'      => 'group_patrocinador_detalhes',
    'title'    => '🤝 Dados do Patrocinador',
    'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'patrocinador' ] ] ],
    'position' => 'normal',
    'style'    => 'seamless',
    'fields'   => [
        [
            'key'          => 'field_patrocinador_url',
            'name'         => 'patrocinador_url',
            'label'        => '🔗 Site / URL do Patrocinador',
            'type'         => 'url',
            'placeholder'  => 'https://www.exemplo.com.br',
            'instructions' => 'Endereço do site do patrocinador. Ao clicar no logo, o visitante será redirecionado para este link.',
        ],
    ],
] );


// ═══════════════════════════════════════════════════════════════
// 4. FOTOS DO EVENTO — Status de aprovação e autor
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


// ═══════════════════════════════════════════════════════════════
// 5. OFICINEIROS — Nome do Grupo/Escola e Redes Sociais
// ═══════════════════════════════════════════════════════════════
// Registrado via acf/init para garantir que abril_get_social_network_choices()
// esteja disponível (definida em page-options.php, carregado após meta-boxes.php).
add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    $social_choices = function_exists( 'abril_get_social_network_choices' )
        ? abril_get_social_network_choices()
        : [];

    acf_add_local_field_group( [
        'key'      => 'group_oficineiro_detalhes',
        'title'    => '🥋 Dados do Oficineiro',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'oficineiro' ] ] ],
        'position' => 'normal',
        'style'    => 'default',
        'fields'   => [

            // ── Nome do Grupo ou Escola ────────────────────
            [
                'key'          => 'field_oficineiro_grupo_escola',
                'name'         => 'oficineiro_grupo_escola',
                'label'        => '🏫 Nome do Grupo ou Escola',
                'type'         => 'text',
                'required'     => 0,
                'placeholder'  => 'Ex: Grupo Capoeira Angola Palmares',
                'wrapper'      => [ 'width' => '50' ],
                'instructions' => 'Informe o nome do grupo, escola ou associação de capoeira do oficineiro.',
            ],

            // ── Repeater: Redes Sociais ────────────────────
            [
                'key'          => 'field_oficineiro_redes_sociais',
                'name'         => 'oficineiro_redes_sociais',
                'label'        => '🔗 Redes Sociais',
                'type'         => 'repeater',
                'instructions' => 'Adicione as redes sociais do oficineiro. O ícone será exibido automaticamente via Font Awesome.',
                'layout'       => 'row',
                'button_label' => 'Adicionar Rede Social',
                'collapsed'    => 'field_oficineiro_rede_social_nome',
                'sub_fields'   => [
                    [
                        'key'        => 'field_oficineiro_rede_social_nome',
                        'label'      => 'Rede Social',
                        'name'       => 'rede_social',
                        'type'       => 'select',
                        'required'   => 1,
                        'ui'         => 1,
                        'allow_null' => 0,
                        'choices'    => $social_choices,
                        'wrapper'    => [ 'width' => '40' ],
                    ],
                    [
                        'key'         => 'field_oficineiro_rede_social_url',
                        'label'       => 'URL da Rede',
                        'name'        => 'rede_social_url',
                        'type'        => 'url',
                        'required'    => 1,
                        'placeholder' => 'https://...',
                        'wrapper'     => [ 'width' => '60' ],
                    ],
                ],
            ],

        ],
    ] );
} );


// ═══════════════════════════════════════════════════════════════
// 6. HOMENAGEADOS — Nome do Grupo/Escola e Redes Sociais
// ═══════════════════════════════════════════════════════════════
// Mesma estrutura do grupo de Oficineiros, aplicado ao CPT "homenageado".
add_action( 'acf/init', function () {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    $social_choices = function_exists( 'abril_get_social_network_choices' )
        ? abril_get_social_network_choices()
        : [];

    acf_add_local_field_group( [
        'key'      => 'group_homenageado_detalhes',
        'title'    => '🏆 Dados do Homenageado',
        'location' => [ [ [ 'param' => 'post_type', 'operator' => '==', 'value' => 'homenageado' ] ] ],
        'position' => 'normal',
        'style'    => 'default',
        'fields'   => [

            // ── Nome do Grupo ou Escola ────────────────────
            [
                'key'          => 'field_homenageado_grupo_escola',
                'name'         => 'homenageado_grupo_escola',
                'label'        => '🏫 Nome do Grupo ou Escola',
                'type'         => 'text',
                'required'     => 0,
                'placeholder'  => 'Ex: Grupo Capoeira Angola Palmares',
                'wrapper'      => [ 'width' => '50' ],
                'instructions' => 'Informe o nome do grupo, escola ou associação de capoeira do homenageado.',
            ],

            // ── Repeater: Redes Sociais ────────────────────
            [
                'key'          => 'field_homenageado_redes_sociais',
                'name'         => 'homenageado_redes_sociais',
                'label'        => '🔗 Redes Sociais',
                'type'         => 'repeater',
                'instructions' => 'Adicione as redes sociais do homenageado. O ícone será exibido automaticamente via Font Awesome.',
                'layout'       => 'row',
                'button_label' => 'Adicionar Rede Social',
                'collapsed'    => 'field_homenageado_rede_social_nome',
                'sub_fields'   => [
                    [
                        'key'        => 'field_homenageado_rede_social_nome',
                        'label'      => 'Rede Social',
                        'name'       => 'rede_social',
                        'type'       => 'select',
                        'required'   => 1,
                        'ui'         => 1,
                        'allow_null' => 0,
                        'choices'    => $social_choices,
                        'wrapper'    => [ 'width' => '40' ],
                    ],
                    [
                        'key'         => 'field_homenageado_rede_social_url',
                        'label'       => 'URL da Rede',
                        'name'        => 'rede_social_url',
                        'type'        => 'url',
                        'required'    => 1,
                        'placeholder' => 'https://...',
                        'wrapper'     => [ 'width' => '60' ],
                    ],
                ],
            ],

        ],
    ] );
} );


