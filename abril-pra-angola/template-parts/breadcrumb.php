<?php
/**
 * Componente — Breadcrumb (Trilha de navegação)
 *
 * Navegação semântica e rastreável com Schema.org BreadcrumbList.
 * O item "Home" é gerado automaticamente como primeiro elo da trilha.
 *
 * Uso:
 *   get_template_part( 'template-parts/breadcrumb', null, [
 *       'items' => [
 *           [ 'label' => 'Inscrição', 'url' => '' ], // último item = página atual (sem url)
 *       ],
 *   ] );
 *
 * @package abril-pra-angola
 */

$items = $args['items'] ?? [];

// Garante array indexado e remove entradas vazias.
$items = array_values( array_filter( (array) $items, static function ( $item ) {
    return ! empty( $item['label'] );
} ) );
?>

<nav
    class="breadcrumb"
    aria-label="<?php esc_attr_e( 'Trilha de navegação', 'abril-pra-angola' ); ?>"
    itemscope
    itemtype="https://schema.org/BreadcrumbList"
>
    <div class="container">
        <ol class="breadcrumb__list">

            <?php
            // ── Elo 1: Home (sempre presente) ─────────────────────
            $position = 1;
            ?>
            <li
                class="breadcrumb__item"
                itemprop="itemListElement"
                itemscope
                itemtype="https://schema.org/ListItem"
            >
                <a class="breadcrumb__link" itemprop="item" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <i class="fa-solid fa-house" aria-hidden="true"></i>
                    <span itemprop="name"><?php esc_html_e( 'Home', 'abril-pra-angola' ); ?></span>
                </a>
                <meta itemprop="position" content="<?php echo esc_attr( $position ); ?>">
            </li>

            <?php
            // ── Elos seguintes ────────────────────────────────────
            $total = count( $items );
            foreach ( $items as $index => $item ) :
                $position++;
                $is_current = ( $index === $total - 1 ) || empty( $item['url'] );
                ?>

                <?php if ( $is_current ) : ?>
                    <li
                        class="breadcrumb__item breadcrumb__item--current"
                        itemprop="itemListElement"
                        itemscope
                        itemtype="https://schema.org/ListItem"
                        aria-current="page"
                    >
                        <span itemprop="name"><?php echo esc_html( $item['label'] ); ?></span>
                        <meta itemprop="position" content="<?php echo esc_attr( $position ); ?>">
                    </li>
                <?php else : ?>
                    <li
                        class="breadcrumb__item"
                        itemprop="itemListElement"
                        itemscope
                        itemtype="https://schema.org/ListItem"
                    >
                        <a class="breadcrumb__link" itemprop="item" href="<?php echo esc_url( $item['url'] ); ?>">
                            <span itemprop="name"><?php echo esc_html( $item['label'] ); ?></span>
                        </a>
                        <meta itemprop="position" content="<?php echo esc_attr( $position ); ?>">
                    </li>
                <?php endif; ?>

            <?php endforeach; ?>

        </ol>
    </div>
</nav><!-- /.breadcrumb -->

