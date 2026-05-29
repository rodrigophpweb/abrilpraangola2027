<?php
/**
 * SVG Support — Abril pra Angola
 *
 * Habilita o upload de arquivos SVG na biblioteca de mídia do WordPress.
 * Por padrão o WordPress bloqueia SVG por questões de segurança.
 * Aqui aplicamos:
 *   1. Liberação do tipo MIME para upload.
 *   2. Sanitização do conteúdo SVG antes de salvar (remove scripts maliciosos).
 *   3. Preview correto no Media Library (o WP não renderiza SVG nativamente).
 *
 * @package Abril_Pra_Angola
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ═══════════════════════════════════════════════════════════════
// 1. LIBERAR TIPO MIME — Permite o upload de .svg
// ═══════════════════════════════════════════════════════════════
add_filter( 'upload_mimes', function ( array $mimes ): array {
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
} );


// ═══════════════════════════════════════════════════════════════
// 2. CORRIGIR VERIFICAÇÃO DE TIPO DO ARQUIVO (WordPress 4.7.1+)
//    O WP faz uma verificação extra via Fileinfo/mime_content_type
//    que pode rejeitar SVGs mesmo com o MIME liberado acima.
// ═══════════════════════════════════════════════════════════════
add_filter( 'wp_check_filetype_and_ext', function ( array $data, string $file, string $filename ): array {
    $ext = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );

    if ( ! in_array( $ext, [ 'svg', 'svgz' ], true ) ) {
        return $data;
    }

    // Força o tipo correto para que o WP não rejeite o arquivo
    $data['ext']  = $ext;
    $data['type'] = 'image/svg+xml';

    return $data;
}, 10, 3 );


// ═══════════════════════════════════════════════════════════════
// 3. SANITIZAR SVG — Remove scripts e atributos perigosos
//    Executa antes de salvar o arquivo no servidor.
// ═══════════════════════════════════════════════════════════════
add_filter( 'wp_handle_upload_prefilter', function ( array $file ): array {
    $ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

    if ( ! in_array( $ext, [ 'svg', 'svgz' ], true ) ) {
        return $file;
    }

    $svg_content = file_get_contents( $file['tmp_name'] );

    if ( false === $svg_content ) {
        $file['error'] = __( 'Não foi possível ler o arquivo SVG.', 'abril-pra-angola' );
        return $file;
    }

    $sanitized = abril_sanitize_svg( $svg_content );

    if ( false === $sanitized ) {
        $file['error'] = __( 'O arquivo SVG não é válido ou contém código inseguro.', 'abril-pra-angola' );
        return $file;
    }

    // Sobrescreve com a versão sanitizada
    file_put_contents( $file['tmp_name'], $sanitized );

    return $file;
} );


// ═══════════════════════════════════════════════════════════════
// 4. PREVIEW NO MEDIA LIBRARY — Exibe o SVG como <img>
//    O WP não gera thumbnail para SVGs, então injetamos
//    o próprio arquivo como preview.
// ═══════════════════════════════════════════════════════════════
add_filter( 'wp_prepare_attachment_for_js', function ( array $response, WP_Post $attachment ): array {
    if ( 'image/svg+xml' !== $response['mime'] ) {
        return $response;
    }

    // Usa a URL do arquivo original como preview
    if ( empty( $response['sizes'] ) ) {
        $url = wp_get_attachment_url( $attachment->ID );

        $response['sizes'] = [
            'full' => [
                'url'         => $url,
                'width'       => 0,
                'height'      => 0,
                'orientation' => 'landscape',
            ],
        ];
    }

    return $response;
}, 10, 2 );


// ═══════════════════════════════════════════════════════════════
// 5. FUNÇÃO AUXILIAR — Sanitização do SVG
//    Remove: <script>, on* handlers, javascript: hrefs,
//    e outros vetores de XSS comuns em SVGs.
// ═══════════════════════════════════════════════════════════════

/**
 * Sanitiza o conteúdo de um arquivo SVG.
 *
 * Remove tags e atributos potencialmente perigosos mantendo
 * toda a estrutura visual do svg intacta.
 *
 * @param string $svg_content Conteúdo bruto do arquivo SVG.
 * @return string|false Conteúdo sanitizado ou false se inválido.
 */
function abril_sanitize_svg( string $svg_content ) {
    // Tenta carregar como XML
    libxml_use_internal_errors( true );
    $dom = new DOMDocument();
    $dom->loadXML( $svg_content, LIBXML_NONET );

    $errors = libxml_get_errors();
    libxml_clear_errors();

    // Se não é um XML válido, rejeita
    if ( ! empty( $errors ) ) {
        $fatal_errors = array_filter( $errors, fn( $e ) => $e->level === LIBXML_ERR_FATAL );
        if ( ! empty( $fatal_errors ) ) {
            return false;
        }
    }

    $root = $dom->documentElement;

    // Deve ser um elemento <svg>
    if ( ! $root || strtolower( $root->localName ) !== 'svg' ) {
        return false;
    }

    // ── Tags proibidas ────────────────────────────────────────
    $tags_proibidas = [
        'script', 'object', 'embed', 'iframe',
        'applet', 'base', 'link', 'meta',
        'handler', 'foreignObject',
    ];

    foreach ( $tags_proibidas as $tag ) {
        $nodes = $dom->getElementsByTagName( $tag );
        // Itera de trás para frente para evitar problemas ao remover
        for ( $i = $nodes->length - 1; $i >= 0; $i-- ) {
            $node = $nodes->item( $i );
            if ( $node && $node->parentNode ) {
                $node->parentNode->removeChild( $node );
            }
        }
    }

    // ── Atributos de evento e hrefs perigosos ─────────────────
    $xpath       = new DOMXPath( $dom );
    $todos_nodes = $xpath->query( '//*' );

    if ( $todos_nodes ) {
        foreach ( $todos_nodes as $node ) {
            if ( ! ( $node instanceof DOMElement ) ) {
                continue;
            }

            $atributos_para_remover = [];

            foreach ( $node->attributes as $attr ) {
                $nome  = strtolower( $attr->nodeName );
                $valor = strtolower( trim( $attr->nodeValue ) );

                // Remove qualquer handler de evento (onclick, onload, onerror…)
                if ( str_starts_with( $nome, 'on' ) ) {
                    $atributos_para_remover[] = $attr->nodeName;
                    continue;
                }

                // Remove hrefs e src com javascript:
                if ( in_array( $nome, [ 'href', 'xlink:href', 'src', 'action' ], true ) ) {
                    if ( str_contains( $valor, 'javascript:' ) || str_contains( $valor, 'data:' ) ) {
                        $atributos_para_remover[] = $attr->nodeName;
                    }
                }
            }

            foreach ( $atributos_para_remover as $attr_nome ) {
                $node->removeAttribute( $attr_nome );
            }
        }
    }

    // Retorna o SVG sanitizado como string
    return $dom->saveXML( $dom->documentElement );
}

