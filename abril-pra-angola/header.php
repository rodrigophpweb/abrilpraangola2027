<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!--
    role="banner"   → indica aos bots e leitores de ecrã que este é o cabeçalho do site
    Schema.org/WPHeader → estrutura reconhecida por Google, Bing e IAs
-->
<header
    class="site-header"
    role="banner"
    itemscope
    itemtype="https://schema.org/WPHeader"
>
    <div class="container">
        <div class="site-header__inner" itemscope itemtype="https://schema.org/Organization">

            <!-- ── Logotipo ────────────────────────────────────
                 itemprop="url"  → URL canônica da organização
                 itemprop="logo" → logotipo reconhecido pelo Google
            ──────────────────────────────────────────────────── -->
            <a
                href="<?php echo esc_url( home_url( '/' ) ); ?>"
                class="site-header__logo"
                aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> — Página inicial"
                itemprop="url"
            >
                <?php echo abril_get_logo(); ?>
            </a>

            <!-- ── Navegação principal ─────────────────────────
                 aria-label distingue esta nav de outras na página
                 (ex: breadcrumb, footer nav)
            ──────────────────────────────────────────────────── -->
            <nav
                class="site-nav"
                id="primary-nav"
                aria-label="<?php esc_attr_e( 'Navegação principal', 'abril-pra-angola' ); ?>"
            >
                <?php
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'site-nav__list',
                    'fallback_cb'    => false,
                ] );
                ?>
            </nav>

            <!-- ── Botão hamburguer (mobile) ───────────────────
                 aria-expanded → informa leitores de ecrã se o menu está aberto
                 aria-controls → associa o botão ao elemento que controla
            ──────────────────────────────────────────────────── -->
            <button
                class="site-header__toggle"
                aria-expanded="false"
                aria-controls="primary-nav"
                aria-label="<?php esc_attr_e( 'Abrir menu de navegação', 'abril-pra-angola' ); ?>"
            >
                <span class="site-header__toggle-bar" aria-hidden="true"></span>
                <span class="site-header__toggle-bar" aria-hidden="true"></span>
                <span class="site-header__toggle-bar" aria-hidden="true"></span>
            </button>

        </div><!-- .site-header__inner -->
    </div><!-- .container -->
</header>