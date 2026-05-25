<?php
/**
 * Página de Opções do tema via ACF
 *
 * Requer ACF Pro para:
 * - Options Page
 * - Repeater
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Aviso administrativo caso os recursos necessários do ACF Pro não estejam disponíveis.
 */
function abril_acf_options_requirements_notice(): void {
    if ( function_exists( 'acf_add_options_page' ) && function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    echo '<div class="notice notice-warning"><p>'
        . esc_html__( 'Abril pra Angola: a página de opções do evento requer o ACF Pro com suporte a Options Page e Repeater.', 'abril-pra-angola' )
        . '</p></div>';
}
add_action( 'admin_notices', 'abril_acf_options_requirements_notice' );

/**
 * Lista de redes sociais disponíveis no select do ACF.
 *
 * @return array<string, string>
 */
function abril_get_social_network_choices(): array {
    return [
        'website'    => 'Website / Site oficial',
        'instagram'  => 'Instagram',
        'facebook'   => 'Facebook',
        'youtube'    => 'YouTube',
        'whatsapp'   => 'WhatsApp',
        'telegram'   => 'Telegram',
        'tiktok'     => 'TikTok',
        'threads'    => 'Threads',
        'x'          => 'X / Twitter',
        'linkedin'   => 'LinkedIn',
        'spotify'    => 'Spotify',
        'soundcloud' => 'SoundCloud',
        'pinterest'  => 'Pinterest',
        'discord'    => 'Discord',
        'twitch'     => 'Twitch',
        'github'     => 'GitHub',
        'behance'    => 'Behance',
        'dribbble'   => 'Dribbble',
        'flickr'     => 'Flickr',
        'snapchat'   => 'Snapchat',
        'reddit'     => 'Reddit',
        'tumblr'     => 'Tumblr',
        'vimeo'      => 'Vimeo',
        'email'      => 'E-mail',
    ];
}

/**
 * Mapa de ícones Font Awesome para uso no front-end.
 *
 * @return array<string, string>
 */
function abril_get_social_network_icons(): array {
    return [
        'website'    => 'fa-solid fa-globe',
        'instagram'  => 'fa-brands fa-instagram',
        'facebook'   => 'fa-brands fa-facebook-f',
        'youtube'    => 'fa-brands fa-youtube',
        'whatsapp'   => 'fa-brands fa-whatsapp',
        'telegram'   => 'fa-brands fa-telegram',
        'tiktok'     => 'fa-brands fa-tiktok',
        'threads'    => 'fa-brands fa-threads',
        'x'          => 'fa-brands fa-x-twitter',
        'linkedin'   => 'fa-brands fa-linkedin-in',
        'spotify'    => 'fa-brands fa-spotify',
        'soundcloud' => 'fa-brands fa-soundcloud',
        'pinterest'  => 'fa-brands fa-pinterest-p',
        'discord'    => 'fa-brands fa-discord',
        'twitch'     => 'fa-brands fa-twitch',
        'github'     => 'fa-brands fa-github',
        'behance'    => 'fa-brands fa-behance',
        'dribbble'   => 'fa-brands fa-dribbble',
        'flickr'     => 'fa-brands fa-flickr',
        'snapchat'   => 'fa-brands fa-snapchat',
        'reddit'     => 'fa-brands fa-reddit-alien',
        'tumblr'     => 'fa-brands fa-tumblr',
        'vimeo'      => 'fa-brands fa-vimeo-v',
        'email'      => 'fa-solid fa-envelope',
    ];
}

/**
 * Regista a página de opções principal do evento.
 */
function abril_register_theme_options_page(): void {
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        return;
    }

    acf_add_options_page( [
        'page_title' => __( 'Opções do Evento', 'abril-pra-angola' ),
        'menu_title' => __( 'Opções do Evento', 'abril-pra-angola' ),
        'menu_slug'  => 'abril-opcoes-do-evento',
        'capability' => 'manage_options',
        'redirect'   => false,
        'position'   => 22,
        'icon_url'   => 'dashicons-calendar-alt',
        'autoload'   => true,
    ] );
}
add_action( 'acf/init', 'abril_register_theme_options_page' );

/**
 * Regista o grupo de campos da página de opções via PHP.
 */
function abril_register_theme_options_fields(): void {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( [
        'key'                   => 'group_abril_opcoes_evento',
        'title'                 => 'Configurações do Evento',
        'fields'                => [
            [
                'key'   => 'field_abril_tab_evento',
                'label' => 'Informações do Evento',
                'name'  => '',
                'type'  => 'tab',
                'placement' => 'top',
            ],
            [
                'key'           => 'field_abril_edicao_evento',
                'label'         => 'Edição do Evento',
                'name'          => 'edicao_evento',
                'type'          => 'text',
                'required'      => 1,
                'default_value' => 'Abril pra Angola 2027',
                'placeholder'   => 'Ex: Abril pra Angola 2027',
                'wrapper'       => [ 'width' => '50' ],
            ],
            [
                'key'           => 'field_abril_local_evento',
                'label'         => 'Local do Evento',
                'name'          => 'local_evento',
                'type'          => 'text',
                'required'      => 1,
                'placeholder'   => 'Ex: Alvorada Camping',
                'wrapper'       => [ 'width' => '50' ],
            ],
            [
                'key'         => 'field_abril_endereco_evento',
                'label'       => 'Endereço do Evento',
                'name'        => 'endereco_evento',
                'type'        => 'text',
                'required'    => 1,
                'placeholder' => 'Ex: Estr. dos Botelhos, 1944 - Jardim Petropolis, Itapecerica da Serra - SP, 06873-000',
            ],
            [
                'key'         => 'field_abril_url_google_maps',
                'label'       => 'URL do Google Maps',
                'name'        => 'url_google_maps',
                'type'        => 'url',
                'placeholder' => 'https://maps.google.com/...',
            ],
            [
                'key'           => 'field_abril_data_inicio_evento',
                'label'         => 'Data de Início do Evento',
                'name'          => 'data_inicio_evento',
                'type'          => 'date_picker',
                'required'      => 1,
                'display_format'=> 'd/m/Y',
                'return_format' => 'Y-m-d',
                'first_day'     => 1,
                'wrapper'       => [ 'width' => '50' ],
            ],
            [
                'key'           => 'field_abril_data_final_evento',
                'label'         => 'Data Final do Evento',
                'name'          => 'data_final_evento',
                'type'          => 'date_picker',
                'required'      => 1,
                'display_format'=> 'd/m/Y',
                'return_format' => 'Y-m-d',
                'first_day'     => 1,
                'wrapper'       => [ 'width' => '50' ],
            ],
            [
                'key'   => 'field_abril_tab_whatsapp',
                'label' => 'WhatsApp',
                'name'  => '',
                'type'  => 'tab',
                'placement' => 'top',
            ],
            [
                'key'           => 'field_abril_whatsapp_numero',
                'label'         => 'Número WhatsApp',
                'name'          => 'whatsapp_numero',
                'type'          => 'text',
                'instructions'  => 'Informe o número com código do país e DDD, sem espaços ou traços. Ex: 5511983929196',
                'placeholder'   => '5511983929196',
                'default_value' => '5511983929196',
                'wrapper'       => [ 'width' => '50' ],
            ],
            [
                'key'           => 'field_abril_whatsapp_cor_botao',
                'label'         => 'Cor do Botão',
                'name'          => 'whatsapp_cor_botao',
                'type'          => 'color_picker',
                'instructions'  => 'Escolha a cor de fundo do botão flutuante do WhatsApp. Padrão: #FAB206 (--color-primary-0).',
                'default_value' => '#FAB206',
                'wrapper'       => [ 'width' => '50' ],
            ],
            [
                'key'           => 'field_abril_whatsapp_mensagem',
                'label'         => 'Mensagem Personalizada',
                'name'          => 'whatsapp_mensagem',
                'type'          => 'textarea',
                'instructions'  => 'Texto pré-preenchido que será enviado ao iniciar a conversa no WhatsApp.',
                'rows'          => 4,
                'default_value' => 'Seja Bem-Vindo ao Abril pra Angola, é um prazer imenso falar com você? O que podemos te ajudar sobre o evento?',
                'wrapper'       => [ 'width' => '100' ],
            ],
            [
                'key'   => 'field_abril_tab_redes_sociais',
                'label' => 'Redes Sociais',
                'name'  => '',
                'type'  => 'tab',
                'placement' => 'top',
            ],
            [
                'key'          => 'field_abril_redes_sociais',
                'label'        => 'Redes Sociais',
                'name'         => 'redes_sociais',
                'type'         => 'repeater',
                'instructions' => 'Escolha a rede social e adicione o respetivo link. O ícone no front-end será definido automaticamente via Font Awesome.',
                'layout'       => 'row',
                'button_label' => 'Adicionar Rede Social',
                'collapsed'    => 'field_abril_rede_social_nome',
                'sub_fields'   => [
                    [
                        'key'           => 'field_abril_rede_social_nome',
                        'label'         => 'Rede Social',
                        'name'          => 'rede_social',
                        'type'          => 'select',
                        'required'      => 1,
                        'ui'            => 1,
                        'allow_null'    => 0,
                        'choices'       => abril_get_social_network_choices(),
                        'wrapper'       => [ 'width' => '40' ],
                    ],
                    [
                        'key'               => 'field_abril_rede_social_url',
                        'label'             => 'Link da Rede Social',
                        'name'              => 'rede_social_url',
                        'type'              => 'url',
                        'required'          => 1,
                        'placeholder'       => 'https://...',
                        'wrapper'           => [ 'width' => '60' ],
                        'conditional_logic' => [
                            [
                                [
                                    'field'    => 'field_abril_rede_social_nome',
                                    'operator' => '!=',
                                    'value'    => '',
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            // ── Aba: Meios de Pagamento ──────────────────
            [
                'key'       => 'field_abril_tab_pagamento',
                'label'     => 'Meios de Pagamento',
                'name'      => '',
                'type'      => 'tab',
                'placement' => 'top',
            ],
            [
                'key'          => 'field_abril_pix_chave',
                'label'        => '🔑 Chave PIX',
                'name'         => 'pix_chave',
                'type'         => 'text',
                'instructions' => 'Informe a chave PIX (CPF, CNPJ, e-mail, telefone ou chave aleatória). Será usada para gerar o QR Code na página de inscrição.',
                'placeholder'  => 'Ex: email@dominio.com ou 11999999999',
                'wrapper'      => [ 'width' => '50' ],
            ],
            [
                'key'          => 'field_abril_pix_nome_beneficiario',
                'label'        => '👤 Nome do Beneficiário',
                'name'         => 'pix_nome_beneficiario',
                'type'         => 'text',
                'instructions' => 'Nome que aparecerá no QR Code PIX (máx. 25 caracteres, sem acentos).',
                'placeholder'  => 'Ex: Abril pra Angola',
                'wrapper'      => [ 'width' => '30' ],
            ],
            [
                'key'          => 'field_abril_pix_cidade',
                'label'        => '🏙️ Cidade do Beneficiário',
                'name'         => 'pix_cidade',
                'type'         => 'text',
                'instructions' => 'Cidade do recebedor (máx. 15 caracteres, sem acentos).',
                'placeholder'  => 'Ex: Sao Paulo',
                'wrapper'      => [ 'width' => '20' ],
            ],
            [
                'key'          => 'field_abril_dados_bancarios',
                'label'        => '🏦 Dados Bancários (Depósito)',
                'name'         => 'dados_bancarios',
                'type'         => 'textarea',
                'instructions' => 'Informe os dados bancários para depósito. Serão exibidos na página de inscrição quando o participante escolher "Depósito Bancário".',
                'rows'         => 5,
                'placeholder'  => "Banco: Nubank\nAgência: 0001\nConta: 12345678-9\nTitular: Nome Completo\nCPF: 000.000.000-00",
                'wrapper'      => [ 'width' => '100' ],
            ],
        ],
        'location'              => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'abril-opcoes-do-evento',
                ],
            ],
        ],
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
        'show_in_rest'          => 0,
    ] );
}
add_action( 'acf/init', 'abril_register_theme_options_fields' );

/**
 * Formata uma data ACF (Y-m-d) em formato brasileiro.
 *
 * @param string|null $date Data no formato de retorno do ACF.
 * @return string
 */
function abril_format_option_date_br( ?string $date ): string {
    if ( empty( $date ) ) {
        return '';
    }

    $timestamp = strtotime( $date );

    if ( ! $timestamp ) {
        return '';
    }

    return wp_date( 'd/m/Y', $timestamp );
}

/**
 * Retorna as opções principais do evento com datas já formatadas.
 *
 * @return array<string, mixed>
 */
function abril_get_event_options(): array {
    if ( ! function_exists( 'get_field' ) ) {
        return [];
    }

    $data_inicio = get_field( 'data_inicio_evento', 'option' );
    $data_final  = get_field( 'data_final_evento', 'option' );

    return [
        'edicao_evento'       => get_field( 'edicao_evento', 'option' ),
        'local_evento'        => get_field( 'local_evento', 'option' ),
        'endereco_evento'     => get_field( 'endereco_evento', 'option' ),
        'url_google_maps'     => get_field( 'url_google_maps', 'option' ),
        'data_inicio_evento'  => $data_inicio,
        'data_final_evento'   => $data_final,
        'data_inicio_br'      => abril_format_option_date_br( $data_inicio ),
        'data_final_br'       => abril_format_option_date_br( $data_final ),
    ];
}

/**
 * Retorna as configurações do botão flutuante do WhatsApp.
 *
 * @return array<string, string>
 */
function abril_get_whatsapp_options(): array {
    if ( ! function_exists( 'get_field' ) ) {
        return [];
    }

    return [
        'numero'    => get_field( 'whatsapp_numero', 'option' )    ?: '5511983929196',
        'mensagem'  => get_field( 'whatsapp_mensagem', 'option' )  ?: 'Seja Bem-Vindo ao Abril pra Angola, é um prazer imenso falar com você? O que podemos te ajudar sobre o evento?',
        'cor_botao' => get_field( 'whatsapp_cor_botao', 'option' ) ?: '#FAB206',
    ];
}
/**
 * Retorna as configurações dos meios de pagamento.
 *
 * @return array<string, string>
 */
function abril_get_pagamento_options(): array {
    if ( ! function_exists( 'get_field' ) ) {
        return [];
    }

    return [
        'pix_chave'             => get_field( 'pix_chave', 'option' )             ?: '',
        'pix_nome_beneficiario' => get_field( 'pix_nome_beneficiario', 'option' ) ?: 'Abril pra Angola',
        'pix_cidade'            => get_field( 'pix_cidade', 'option' )            ?: 'Sao Paulo',
        'dados_bancarios'       => get_field( 'dados_bancarios', 'option' )       ?: '',
    ];
}

 /**
 * @return array<int, array<string, string>>
 */
function abril_get_social_links(): array {
    if ( ! function_exists( 'get_field' ) ) {
        return [];
    }

    $rows    = get_field( 'redes_sociais', 'option' );
    $choices = abril_get_social_network_choices();
    $icons   = abril_get_social_network_icons();

    if ( empty( $rows ) || ! is_array( $rows ) ) {
        return [];
    }

    $social_links = [];

    foreach ( $rows as $row ) {
        $network = sanitize_key( $row['rede_social'] ?? '' );
        $url     = esc_url_raw( $row['rede_social_url'] ?? '' );

        if ( empty( $network ) || empty( $url ) ) {
            continue;
        }

        $social_links[] = [
            'network'    => $network,
            'label'      => $choices[ $network ] ?? ucfirst( $network ),
            'url'        => $url,
            'icon_class' => $icons[ $network ] ?? 'fa-solid fa-link',
        ];
    }

    return $social_links;
}

/**
 * Renderiza uma navegação de redes sociais com ícones Font Awesome.
 *
 * @param array<string, string> $args Argumentos simples de classe CSS.
 * @return string
 */
function abril_render_social_links( array $args = [] ): string {
    $items = abril_get_social_links();

    if ( empty( $items ) ) {
        return '';
    }

    $nav_class  = $args['nav_class'] ?? 'social-links';
    $list_class = $args['list_class'] ?? 'social-links__list';
    $item_class = $args['item_class'] ?? 'social-links__item';
    $link_class = $args['link_class'] ?? 'social-links__link';

    ob_start();
    ?>
    <nav class="<?php echo esc_attr( $nav_class ); ?>" aria-label="<?php esc_attr_e( 'Redes sociais', 'abril-pra-angola' ); ?>">
        <ul class="<?php echo esc_attr( $list_class ); ?>">
            <?php foreach ( $items as $item ) : ?>
                <li class="<?php echo esc_attr( $item_class ); ?>">
                    <a
                        class="<?php echo esc_attr( $link_class ); ?>"
                        href="<?php echo esc_url( $item['url'] ); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="<?php echo esc_attr( $item['label'] ); ?>"
                    >
                        <i class="<?php echo esc_attr( $item['icon_class'] ); ?>" aria-hidden="true"></i>
                        <span class="screen-reader-text"><?php echo esc_html( $item['label'] ); ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <?php

    return (string) ob_get_clean();
}
