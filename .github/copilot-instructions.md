# Frontend Rules

## Filosofia
- Priorize componentes fluidos e intrinsecamente responsivos.
- Não assuma Mobile First como regra obrigatória.
- Pense primeiro no **comportamento do componente**, não no dispositivo.
- Evite criar layouts baseados em tamanhos fixos de tela.
- Utilize breakpoints apenas quando houver necessidade real.
- Sempre busque uma solução responsiva **sem media queries** antes de criar breakpoints.

---

## Layout

- Utilize **CSS Grid Layout** como primeira opção para construção de layouts.
- Utilize **Flexbox** apenas quando for tecnicamente mais adequado.
- **Nunca** utilize `float` para layout.
- Priorize layouts fluidos e adaptáveis.
- Sempre que possível, utilize:
    - `repeat()`
    - `minmax()`
    - `auto-fit`
    - `auto-fill`
    - `clamp()`
    - `gap`
    - `aspect-ratio`
    - CSS Nesting
    - Container Queries

### Exemplo preferencial

```css
.grid {
  display: grid;
  grid-template-columns: repeat(
    auto-fit,
    minmax(min(100%, 18rem), 1fr)
  );
  gap: 1.5rem;
}
```

---

## Breakpoints

- **Não crie** media queries por padrão.
- Crie breakpoints apenas quando:
    - o conteúdo quebrar visualmente;
    - a leitura ficar comprometida;
    - a navegação ficar prejudicada;
    - o layout perder hierarquia.
- Evite breakpoints baseados em dispositivos — pense no **comportamento do layout**, não no aparelho.

### ❌ Evitar

```css
@media (max-width: 768px) {}
```

### ✅ Preferir

```css
@media (width < 48rem) {
  .hero {
    grid-template-columns: 1fr;
  }
}
```

---

## Tipografia e Espaçamento

- Utilize `rem` como unidade padrão. Considere `1rem = 16px`.
- Evite `px` para tamanhos estruturais.
- Utilize `clamp()` para tipografia fluida quando necessário — mas com atenção à acessibilidade.

### Regras de acessibilidade para tipografia fluida

- **Nunca** use unidades de viewport puras em `font-size` — elas neutralizam o zoom do usuário e violam a WCAG 1.4.4.
- Sempre combine uma unidade relativa (`em`/`rem`) com a unidade de viewport via `calc()` ou `clamp()`.
- O valor máximo do `clamp()` deve ser **≤ 2,5× o valor mínimo** para garantir que o zoom a 200% ainda funcione.

### ❌ Evitar — quebra o zoom do usuário

```css
/* Unidade de viewport pura — viola WCAG 1.4.4 */
.title {
  font-size: clamp(1rem, 2.5vw, 3rem);
}
```

### ✅ Preferir — tipografia fluida e acessível

```css
/* Base em px + ajuste vw mínimo: zoom continua funcionando */
.title {
  font-size: clamp(1.75rem, 1.5rem + 0.5vw, 2.5rem);
}
```

### Negociando o `font-size` base com o usuário

Defina o tamanho base no `html` usando `clamp()` com unidades `em` nos extremos. Assim o design sugere um tamanho, mas o usuário mantém controle.

```css
html {
  /* Mínimo e máximo em em (relativo ao usuário), base em px */
  font-size: clamp(1em, 17px + 0.24vw, 1.125em);
}
```

### Escala tipográfica com `pow()`

Para projetos com escala tipográfica, use a função `pow()` nativa do CSS — sem ferramentas externas.

```css
html {
    --scale: 1.2;

    --small:    calc(1rem * pow(var(--scale), -1));
    --medium:   1rem;
    --large:    calc(1rem * pow(var(--scale), 1));
    --x-large:  calc(1rem * pow(var(--scale), 2));
    --xx-large: calc(1rem * pow(var(--scale), 3));

    /* Ajuste da proporção por breakpoint */
    @media (width > 50em) {
        --scale: 1.333; /* perfect fourth */
    }
}
```

---

## CSS

- Utilize classes semânticas.
- **Evite IDs** para estilização.
- **Evite `!important`** — resolva conflitos através de arquitetura CSS e especificidade adequada.
- Evite duplicação de código.
- Organize CSS **por componentes**.
- Priorize reutilização.

---

## Estrutura de Componentes

Ao criar um novo componente CSS, utilize o diretório:

```
./abril-pra-angola/assets/css/components/
```

Informe também o nome sugerido do arquivo.

### Exemplo

```
./abril-pra-angola/assets/css/components/event-card.css
```

---

## HTML

- Utilize **HTML5 semântico**.
- Priorize **acessibilidade**.
- Utilize elementos apropriados:
    - `<header>`, `<nav>`, `<main>`
    - `<section>`, `<article>`, `<aside>`
    - `<footer>`, `<figure>`, `<figcaption>`
    - `<time>`, `<address>`
- Utilize `<button>` para **ações**.
- Utilize `<a>` para **navegação**.
- Garanta navegação por teclado.
- Garanta contraste adequado.
- Utilize atributos `aria-*` apenas quando necessário.

---

## JavaScript

- Utilize **JavaScript puro**.
- Priorize **Fetch API**.
- Evite jQuery quando possível.
- Utilize `async/await`.
- Utilize JavaScript para **comportamento**.
- **Não** utilize JavaScript para resolver problemas que CSS moderno resolve sozinho.

---

## Performance

- Escreva CSS simples e previsível.
- Evite seletores excessivamente profundos.
- Evite animações desnecessárias.
- Priorize `transform` e `opacity` para animações.
- Respeite `prefers-reduced-motion`.

---

## Regra Principal

> Antes de criar uma media query, avalie se o problema pode ser resolvido com CSS Grid, `minmax()`, `auto-fit`, `auto-fill`, `clamp()` ou Container Queries.
>
> **Se puder ser resolvido sem breakpoint, prefira a solução fluida.**

---

# Backend Rules — WordPress

## Filosofia

- Escreva código WordPress idiomático — use a API do core antes de reinventar soluções.
- Prefira funções e hooks nativos do WordPress a soluções customizadas.
- Mantenha o `functions.php` limpo: ele apenas carrega módulos via `require_once`.
- Cada arquivo em `/inc` tem uma responsabilidade única e bem definida.
- Nunca edite arquivos de temas ou plugins de terceiros diretamente — use hooks e child themes.

---

## Estrutura de Arquivos

O `functions.php` é o ponto de entrada. Toda lógica fica em `/inc`, organizada por funcionalidade.

### Estrutura padrão

```
theme-name/
├── functions.php
└── inc/
    ├── hooks.php         # Actions e filters globais do tema
    ├── cpt.php           # Custom Post Types e Taxonomias
    ├── acf.php           # Configurações e campos ACF
    ├── api.php           # Registro de rotas REST e require dos controllers
    ├── enqueue.php       # Scripts e estilos
    ├── security.php      # Hardening, sanitização global
    └── helpers.php       # Funções utilitárias reutilizáveis
```

### `functions.php`

```php
<?php
// Carregamento de módulos
require_once get_template_directory() . '/inc/hooks.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/acf.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/api.php';
require_once get_template_directory() . '/inc/security.php';
require_once get_template_directory() . '/inc/helpers.php';
```

---

## Nomenclatura

- Use **prefixo fixo por projeto** em todas as funções, hooks, CPTs, meta keys e opções.
- O prefixo deve ser curto e único, derivado do nome do projeto.

### Padrão

```
{prefixo}_{contexto}_{descricao}
```

### Exemplos

```php
// Projeto: Abril pra Angola → prefixo: apla_
apla_register_event_cpt()
apla_get_event_date( $post_id )
apla_api_get_events()

// Hook customizado
do_action( 'apla_after_event_registration', $event_id, $user_id );
apply_filters( 'apla_event_card_data', $data, $post_id );
```

- **Funções:** `snake_case` com prefixo.
- **Classes:** `PascalCase` com prefixo. Ex: `Apla_Events_Controller`.
- **Constantes:** `UPPER_SNAKE_CASE` com prefixo. Ex: `APLA_VERSION`.
- **Meta keys e option names:** prefixo + underscore. Ex: `apla_event_date`.

---

## Hooks (Actions e Filters)

- Registre todas as actions e filters em `inc/hooks.php`.
- Sempre especifique `$priority` e `$accepted_args` quando relevante.
- Prefira `add_action` com callbacks nomeados a closures anônimas — facilita `remove_action`.
- Use `do_action` e `apply_filters` com prefixo do projeto para hooks customizados.

### ✅ Preferir

```php
// inc/hooks.php
add_action( 'init', 'apla_register_event_cpt' );
add_action( 'wp_enqueue_scripts', 'apla_enqueue_assets' );
add_filter( 'apla_event_card_data', 'apla_add_registration_count', 10, 2 );
```

### ❌ Evitar

```php
// Closure dificulta remove_action posterior
add_action( 'init', function() {
    // lógica aqui
} );
```

---

## Custom Post Types e Taxonomias

- Registre CPTs e taxonomias em `inc/cpt.php`.
- Sempre defina `rewrite`, `supports`, `show_in_rest` e `menu_icon`.
- Use `has_archive` com consciência — habilite apenas se houver template de arquivo.
- Defina labels completas para boa experiência no admin.

### Exemplo

```php
// inc/cpt.php
function apla_register_event_cpt() {
    register_post_type( 'apla_event', [
        'labels'       => apla_get_event_labels(),
        'public'       => true,
        'has_archive'  => true,
        'show_in_rest' => true,
        'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'rewrite'      => [ 'slug' => 'eventos' ],
        'menu_icon'    => 'dashicons-calendar-alt',
    ] );
}
add_action( 'init', 'apla_register_event_cpt' );
```

---

## ACF — Advanced Custom Fields

- Registre grupos de campos via PHP em `inc/acf.php` — nunca dependa exclusivamente da interface do admin para projetos em produção.
- Use `acf_add_local_field_group()` para versionamento dos campos junto ao tema.
- Sempre valide se o campo existe antes de usar: `get_field()` retorna `false` se vazio.
- Prefixe as `field keys` com o prefixo do projeto. Ex: `field_apla_event_date`.
- Para blocos Gutenberg via ACF, crie um diretório `/blocks/{nome-do-bloco}/`.

### Boas práticas

```php
// Sempre verifique antes de exibir
$date = get_field( 'apla_event_date', $post_id );
if ( $date ) {
    echo esc_html( $date );
}

// Para campos de relacionamento, evite loops sem verificação
$speakers = get_field( 'apla_event_speakers', $post_id );
if ( ! empty( $speakers ) && is_array( $speakers ) ) {
    foreach ( $speakers as $speaker ) {
        // ...
    }
}
```

---

## REST API Customizada

- Registre as rotas em `inc/api.php`.
- Cada recurso tem sua própria classe Controller em `/inc/api/`.
- Controllers seguem o padrão `{Prefixo}_{Recurso}_Controller`.
- Sempre defina `permission_callback` — nunca use `__return_true` em rotas que modificam dados.

### Estrutura

```
inc/
└── api/
    ├── class-apla-events-controller.php
    └── class-apla-registrations-controller.php
```

### `inc/api.php`

```php
<?php
require_once get_template_directory() . '/inc/api/class-apla-events-controller.php';
require_once get_template_directory() . '/inc/api/class-apla-registrations-controller.php';

add_action( 'rest_api_init', function() {
    ( new Apla_Events_Controller() )->register_routes();
    ( new Apla_Registrations_Controller() )->register_routes();
} );
```

### Controller base

```php
// inc/api/class-apla-events-controller.php
class Apla_Events_Controller {

    private string $namespace = 'apla/v1';
    private string $rest_base = 'events';

    public function register_routes(): void {
        register_rest_route( $this->namespace, '/' . $this->rest_base, [
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_items' ],
                'permission_callback' => '__return_true',
            ],
        ] );
    }

    public function get_items( WP_REST_Request $request ): WP_REST_Response|WP_Error {
        // lógica aqui
    }
}
```

---

## Segurança

### Nonces

- Use nonces em todos os formulários e requisições AJAX.
- Valide com `check_ajax_referer()` ou `wp_verify_nonce()` antes de qualquer processamento.

```php
// Gerar
wp_nonce_field( 'apla_register_event', 'apla_nonce' );

// Validar
if ( ! isset( $_POST['apla_nonce'] ) || ! wp_verify_nonce( $_POST['apla_nonce'], 'apla_register_event' ) ) {
    wp_die( 'Requisição inválida.' );
}
```

### Sanitização (entrada)

- Sanitize **sempre** antes de salvar no banco.
- Use a função de sanitização mais específica disponível.

| Contexto            | Função                        |
|---------------------|-------------------------------|
| Texto simples       | `sanitize_text_field()`       |
| E-mail              | `sanitize_email()`            |
| URL                 | `esc_url_raw()`               |
| HTML permitido      | `wp_kses_post()`              |
| Inteiro             | `absint()` ou `intval()`      |
| Slug                | `sanitize_title()`            |

### Escape (saída)

- Escape **sempre** na hora de exibir dados.
- Nunca use `echo` diretamente em variáveis sem escape.

```php
echo esc_html( $title );
echo esc_attr( $value );
echo esc_url( $link );
echo wp_kses_post( $content );
```

### Capabilities

- Verifique permissões antes de executar ações sensíveis.

```php
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Acesso negado.' );
}
```

---

## Banco de Dados

### WP_Query

- Prefira `WP_Query` a `query_posts()` — nunca use `query_posts()`.
- Sempre destrua queries secundárias com `wp_reset_postdata()`.
- Limite os campos retornados quando não precisar do objeto completo.

```php
$events = new WP_Query( [
    'post_type'      => 'apla_event',
    'posts_per_page' => 10,
    'post_status'    => 'publish',
    'fields'         => 'ids', // quando só precisar dos IDs
] );

if ( $events->have_posts() ) {
    while ( $events->have_posts() ) {
        $events->the_post();
        // ...
    }
    wp_reset_postdata();
}
```

### $wpdb — Queries diretas

- Use `$wpdb` apenas quando `WP_Query` ou funções nativas não resolverem.
- **Sempre** use `$wpdb->prepare()` para queries com parâmetros dinâmicos.
- Nunca interpole variáveis diretamente na query.

```php
global $wpdb;

// ✅ Correto
$result = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->posts} WHERE post_author = %d AND post_status = %s",
        $user_id,
        'publish'
    )
);

// ❌ Nunca fazer
$result = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE post_author = $user_id" );
```

---

## Performance

### Transients

- Use transients para cachear resultados de queries pesadas ou chamadas externas.
- Defina sempre um tempo de expiração.
- Prefixe a chave com o prefixo do projeto.

```php
function apla_get_upcoming_events(): array {
    $cache_key = 'apla_upcoming_events';
    $events    = get_transient( $cache_key );

    if ( false === $events ) {
        $query  = new WP_Query( [ 'post_type' => 'apla_event', 'posts_per_page' => 5 ] );
        $events = $query->posts;
        set_transient( $cache_key, $events, HOUR_IN_SECONDS );
    }

    return $events;
}
```

- Invalide o transient ao salvar o CPT relacionado:

```php
add_action( 'save_post_apla_event', function() {
    delete_transient( 'apla_upcoming_events' );
} );
```

### Enqueue

- Registre scripts e estilos em `inc/enqueue.php`.
- Carregue assets condicionalmente quando possível.
- Use `wp_localize_script()` para passar dados do PHP ao JavaScript.

```php
function apla_enqueue_assets(): void {
    wp_enqueue_style(
        'apla-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        APLA_VERSION
    );

    if ( is_singular( 'apla_event' ) ) {
        wp_enqueue_script(
            'apla-event',
            get_template_directory_uri() . '/assets/js/event.js',
            [],
            APLA_VERSION,
            true
        );

        wp_localize_script( 'apla-event', 'aplaEventData', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'apla_event_nonce' ),
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'apla_enqueue_assets' );
```

---

## Regra Principal

> Antes de escrever código customizado, verifique se o WordPress já resolve nativamente via hooks, funções do core ou WP_Query.
>
> **Se o core resolve, use o core.**

---

# Web Vitals & Qualidade — Meta: 100/100/100/100 + PWA

> **Objetivo não negociável:** toda página deve atingir nota **100** em Performance, Acessibilidade, SEO e Práticas Recomendadas no Google PageSpeed Insights (Lighthouse), nota máxima no GTmetrix e aprovação completa no WebPageTest — além de ser instalável como **PWA**.
>
> Antes de entregar qualquer template, componente ou feature, valide contra estes critérios.

---

## 1. Performance — Core Web Vitals

### Metas obrigatórias

| Métrica | Meta | O que mede |
|---------|------|------------|
| **LCP** | ≤ 2,5 s | Maior elemento visível carregado |
| **INP** | ≤ 200 ms | Resposta à interação do usuário |
| **CLS** | ≤ 0,1 | Estabilidade visual (sem saltos de layout) |
| **FCP** | ≤ 1,8 s | Primeiro pixel de conteúdo na tela |
| **TTFB** | ≤ 800 ms | Tempo até o primeiro byte do servidor |

---

### 1.1 LCP — Imagem Hero / Banner

- Sempre identifique o elemento LCP da página (normalmente a imagem do hero).
- No elemento LCP, **nunca** use `loading="lazy"` — use `loading="eager"` e `fetchpriority="high"`.
- Faça `<link rel="preload">` da imagem LCP no `<head>`.
- No WordPress, use `wp_get_attachment_image()` — ele já gera `srcset` e `sizes` automaticamente.

```html
<!-- ✅ Elemento LCP — sempre eager + fetchpriority -->
<img
  src="hero.webp"
  srcset="hero-480.webp 480w, hero-960.webp 960w, hero-1440.webp 1440w"
  sizes="100vw"
  width="1440"
  height="600"
  alt="Descrição real do conteúdo"
  fetchpriority="high"
  loading="eager"
  decoding="sync"
>
```

```html
<!-- No <head> — preload do LCP -->
<link
  rel="preload"
  as="image"
  href="hero-960.webp"
  imagesrcset="hero-480.webp 480w, hero-960.webp 960w, hero-1440.webp 1440w"
  imagesizes="100vw"
  fetchpriority="high"
>
```

```php
// WordPress — preload via wp_head
add_action( 'wp_head', 'apla_preload_hero_image', 1 );
function apla_preload_hero_image(): void {
    if ( ! is_front_page() ) return;
    $image_id  = get_theme_mod( 'apla_hero_image_id' );
    $image_src = wp_get_attachment_image_src( $image_id, 'full' );
    if ( ! $image_src ) return;
    echo '<link rel="preload" as="image" href="' . esc_url( $image_src[0] ) . '" fetchpriority="high">' . "\n";
}
```

---

### 1.2 CLS — Estabilidade Visual

- **Sempre** defina `width` e `height` em todas as imagens — o browser reserva o espaço antes do carregamento.
- Use `aspect-ratio` no CSS para elementos dinâmicos (embeds, vídeos, cards).
- Nunca insira conteúdo acima do fold de forma assíncrona sem reservar o espaço previamente.
- Fontes: use `font-display: swap` ou `font-display: optional` — nunca omita `font-display`.

```css
/* ✅ Reservar espaço para imagens e embeds */
img {
  width: 100%;
  height: auto;
  aspect-ratio: attr(width) / attr(height);
}

.embed-video {
  aspect-ratio: 16 / 9;
  width: 100%;
}
```

---

### 1.3 INP — Interatividade

- Nenhum event handler deve bloquear a thread principal por mais de **50 ms**.
- Use `debounce` em inputs e scroll handlers.
- Prefira `requestAnimationFrame` para atualizações visuais.
- Divida tarefas longas com `scheduler.postTask()` ou `setTimeout(..., 0)`.

```js
// ✅ Debounce em handlers de input
function debounce( fn, delay = 200 ) {
  let timer;
  return ( ...args ) => {
    clearTimeout( timer );
    timer = setTimeout( () => fn( ...args ), delay );
  };
}

input.addEventListener( 'input', debounce( handleSearch ) );
```

---

### 1.4 Imagens — Regras Gerais

- **Formato:** sempre WebP como formato principal; JPEG/PNG apenas como fallback via `<picture>`.
- **Lazy loading:** `loading="lazy"` em **todas** as imagens fora do fold (exceto o LCP).
- **Decoding:** `decoding="async"` em imagens não críticas.
- **Alt:** sempre presente e descritivo; vazio (`alt=""`) apenas para imagens puramente decorativas.
- **Dimensões:** `width` e `height` obrigatórios — sem exceção.

```html
<!-- ✅ Imagem padrão fora do fold -->
<picture>
  <source srcset="foto.webp" type="image/webp">
  <img
    src="foto.jpg"
    srcset="foto-480.jpg 480w, foto-960.jpg 960w"
    sizes="(width < 48rem) 100vw, 50vw"
    width="960"
    height="640"
    alt="Mestre João demonstrando golpe de capoeira angola"
    loading="lazy"
    decoding="async"
  >
</picture>
```

```php
// WordPress — sempre use wp_get_attachment_image() para srcset automático
echo wp_get_attachment_image( $image_id, 'large', false, [
    'loading' => 'lazy',
    'decoding' => 'async',
    'class'   => 'section__image',
] );
```

---

### 1.5 Fontes

- Hospede fontes localmente — nunca carregue do Google Fonts sem `preconnect`.
- Use `font-display: swap` para fontes de texto; `font-display: optional` para fontes decorativas.
- Faça `<link rel="preload">` apenas das fontes usadas acima do fold.
- Limite a variações utilizadas — não carregue pesos/estilos não usados.

```html
<!-- ✅ Preload da fonte crítica -->
<link rel="preload" href="/assets/fonts/inter-regular.woff2" as="font" type="font/woff2" crossorigin>

<!-- Fallback Google Fonts com preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
```

```css
@font-face {
  font-family: 'Inter';
  src: url('/assets/fonts/inter-regular.woff2') format('woff2');
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}
```

---

### 1.6 CSS — Entrega Crítica

- CSS acima do fold deve ser **inlined** no `<head>` (critical CSS).
- CSS não crítico carregado com `rel="preload"` + `onload` trick ou via plugin de cache.
- Nunca use `@import` em CSS de produção — aumenta o número de round-trips.
- Minifique todo CSS em produção.

```html
<!-- ✅ CSS crítico inline -->
<style>/* critical CSS aqui */</style>

<!-- CSS não crítico — carregado de forma não bloqueante -->
<link rel="preload" href="/assets/css/main.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="/assets/css/main.css"></noscript>
```

---

### 1.7 JavaScript — Sem Bloqueio de Render

- Todo script não crítico deve ter `defer` ou `type="module"` — nunca omita.
- Nunca use `document.write()`.
- No WordPress: sempre passe `true` como último parâmetro do `wp_enqueue_script()` (in footer).
- Remova jQuery do frontend se não for estritamente necessário.
- Nunca carregue bibliotecas inteiras quando apenas uma função é necessária.

```php
// ✅ WordPress — script no footer com defer
wp_enqueue_script(
    'apla-main',
    get_template_directory_uri() . '/assets/js/main.js',
    [],       // sem dependência de jQuery
    APLA_VERSION,
    [ 'in_footer' => true, 'strategy' => 'defer' ]  // WP 6.3+
);
```

---

### 1.8 Cache — WordPress (TTFB)

- Use **transients** para todas as queries pesadas (ver seção Backend).
- Configure cache de página (WP Super Cache, W3 Total Cache, ou servidor Nginx/OPcache).
- Use `Cache-Control: public, max-age=31536000, immutable` para assets com hash no nome.
- Adicione versão (hash ou `APLA_VERSION`) em todos os assets para cache busting eficiente.

---

## 2. Acessibilidade — WCAG 2.1 AA (meta: 100)

### 2.1 Contraste

- Texto normal: **razão mínima 4,5:1** contra o fundo.
- Texto grande (≥ 18px normal / ≥ 14px bold): **razão mínima 3:1**.
- Elementos de interface (ícones, bordas de input): **razão mínima 3:1**.
- Use [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/) para validar.
- **Nunca** use `color` como único meio de transmitir informação.

---

### 2.2 Navegação por Teclado

- **Todos** os elementos interativos devem ser alcançáveis e ativáveis via teclado.
- `focus-visible` deve ser sempre visível — nunca remova `outline` sem substituir por estilo equivalente.
- Implemente **skip link** no topo de toda página.
- Ordem de `tabindex` deve seguir a ordem visual lógica.

```html
<!-- ✅ Skip link — primeiro elemento do body -->
<a href="#main-content" class="skip-link">Ir para o conteúdo principal</a>
```

```css
.skip-link {
  position: absolute;
  inset-block-start: -100%;
  inset-inline-start: 1rem;
  padding: 0.5rem 1rem;
  background: var(--color-primary);
  color: #fff;
  z-index: 9999;

  &:focus {
    inset-block-start: 1rem;
  }
}

/* ✅ Focus visível — nunca remova, sempre customize */
:focus-visible {
  outline: 3px solid var(--color-primary);
  outline-offset: 3px;
}
```

---

### 2.3 HTML Semântico e ARIA

- `<html lang="pt-BR">` obrigatório em todo documento.
- `<main id="main-content">` obrigatório — destino do skip link.
- Hierarquia de headings **linear e sem saltos**: H1 → H2 → H3…
- **Um único `<h1>`** por página — sempre o título principal do conteúdo.
- Ícones sem texto visível **devem** ter `aria-label` ou `aria-labelledby`.
- Imagens decorativas: `alt=""` e `aria-hidden="true"`.
- Use `aria-*` apenas quando o HTML semântico nativo não resolver.
- Botões de toggle: `aria-expanded`, `aria-controls`.
- Campos de formulário: sempre `<label>` associado via `for` + `id`.

```html
<!-- ✅ Ícone sem texto -->
<button type="button" aria-label="Fechar menu">
  <svg aria-hidden="true" focusable="false">...</svg>
</button>

<!-- ✅ Campo com label explícito -->
<label for="campo-email">E-mail</label>
<input type="email" id="campo-email" name="email" autocomplete="email" required>
```

---

### 2.4 Preferências do Usuário

- Sempre respeite `prefers-reduced-motion` — desative ou minimize animações.
- Implemente suporte a `prefers-color-scheme` quando o design permitir.
- Nunca use `font-size` em `px` fixo — respeite o zoom do usuário (ver seção Tipografia).

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
```

---

## 3. SEO — Meta: 100

### 3.1 Tags Obrigatórias por Página

Toda página pública deve ter:

```html
<head>
  <!-- Charset e Viewport -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Title: 50–60 caracteres, único por página -->
  <title>Abril pra Angola 2027 — Festival de Capoeira Angola</title>

  <!-- Description: 120–160 caracteres -->
  <meta name="description" content="Festival internacional de Capoeira Angola. Oficinas, shows e celebração cultural em São Paulo. Inscrições abertas.">

  <!-- Canonical -->
  <link rel="canonical" href="https://www.abrilpraangola.com.br/pagina/">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="Abril pra Angola 2027">
  <meta property="og:description" content="Festival internacional de Capoeira Angola.">
  <meta property="og:image" content="https://www.abrilpraangola.com.br/assets/images/og-image.jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:url" content="https://www.abrilpraangola.com.br/">
  <meta property="og:locale" content="pt_BR">
  <meta property="og:site_name" content="Abril pra Angola">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="Abril pra Angola 2027">
  <meta name="twitter:description" content="Festival internacional de Capoeira Angola.">
  <meta name="twitter:image" content="https://www.abrilpraangola.com.br/assets/images/og-image.jpg">
</head>
```

---

### 3.2 Dados Estruturados (Schema.org / JSON-LD)

- Use **JSON-LD** (preferido pelo Google) — nunca misture marcação com HTML visual.
- Implemente o tipo de Schema adequado para cada tipo de conteúdo.
- Valide sempre com [Google Rich Results Test](https://search.google.com/test/rich-results).

```html
<!-- ✅ Evento -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "Abril pra Angola 2027",
  "startDate": "2027-04-10",
  "endDate": "2027-04-13",
  "eventStatus": "https://schema.org/EventScheduled",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "location": {
    "@type": "Place",
    "name": "Chácara Angola",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "São Paulo",
      "addressCountry": "BR"
    }
  },
  "image": "https://www.abrilpraangola.com.br/assets/images/og-image.jpg",
  "description": "Festival internacional de Capoeira Angola.",
  "organizer": {
    "@type": "Organization",
    "name": "Abril pra Angola",
    "url": "https://www.abrilpraangola.com.br"
  }
}
</script>
```

```php
// WordPress — JSON-LD dinâmico via wp_head
add_action( 'wp_head', 'apla_output_schema_jsonld' );
function apla_output_schema_jsonld(): void {
    if ( ! is_singular( [ 'oficineiro', 'homenageado' ] ) ) return;

    $post_id = get_the_ID();
    $schema  = [
        '@context' => 'https://schema.org',
        '@type'    => 'Person',
        'name'     => get_the_title( $post_id ),
        'image'    => get_the_post_thumbnail_url( $post_id, 'large' ),
        'description' => get_the_excerpt( $post_id ),
        'url'      => get_permalink( $post_id ),
    ];

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
```

---

### 3.3 Links e Navegabilidade

- Todo link `<a>` deve ter texto descritivo — nunca "clique aqui" ou "leia mais" sem contexto.
- Links externos: sempre `rel="noopener noreferrer"` + `target="_blank"`.
- Links internos: texto que descreva o destino.
- Sitemap XML: gerado automaticamente (Yoast SEO / Rank Math) e referenciado no `robots.txt`.

```html
<!-- ❌ Evitar -->
<a href="/oficineiros/mestre-joao">Clique aqui</a>

<!-- ✅ Preferir -->
<a href="/oficineiros/mestre-joao">Ver perfil de Mestre João</a>

<!-- ✅ Link externo -->
<a href="https://instagram.com/mestrejoao" rel="noopener noreferrer" target="_blank">
  Instagram de Mestre João
</a>
```

---

### 3.4 robots.txt e Sitemap

```
# robots.txt mínimo
User-agent: *
Allow: /
Disallow: /wp-admin/
Disallow: /wp-includes/
Sitemap: https://www.abrilpraangola.com.br/sitemap_index.xml
```

---

## 4. Práticas Recomendadas — Meta: 100

### 4.1 HTTPS e Headers de Segurança

- **HTTPS obrigatório** em todos os ambientes (staging e produção).
- Configure os seguintes headers HTTP no servidor (Nginx/Apache):

```nginx
# Nginx — headers de segurança
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-Frame-Options "SAMEORIGIN" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "camera=(), microphone=(), geolocation=()" always;
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' https://www.googletagmanager.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; frame-src https://www.youtube.com https://player.vimeo.com;" always;
```

---

### 4.2 Console e Erros

- **Zero erros** no console do browser em produção.
- **Zero warnings** referentes a APIs deprecated.
- Remova todos os `console.log()` antes do deploy — use uma variável de ambiente ou plugin de build.
- Nunca use APIs que o Lighthouse marque como deprecated.

```js
// ✅ Log apenas em desenvolvimento
if ( 'development' === process.env.NODE_ENV ) {
  console.log( 'debug:', data );
}
```

---

### 4.3 Links Externos e Segurança

- Todo `target="_blank"` **deve** ter `rel="noopener noreferrer"` — sem exceção.
- Valide URLs antes de renderizá-las (use `esc_url()` no PHP, `URL` API no JS).

```php
// ✅ WordPress — escape obrigatório
echo '<a href="' . esc_url( $url ) . '" rel="noopener noreferrer" target="_blank">';
```

---

### 4.4 Recursos Modernos

- Não use APIs que o browser marcará como deprecated no Lighthouse.
- Prefira `navigator.geolocation`, `IntersectionObserver`, `ResizeObserver` às alternativas legadas.
- Não use `document.write()`, `alert()`, `confirm()` em produção.
- Use `<meta http-equiv="Content-Security-Policy">` ou header de servidor — não ambos.

---

## 5. PWA — Progressive Web App

### 5.1 Critérios de Instalabilidade (Lighthouse)

Para que o Lighthouse marque o site como PWA instalável, são obrigatórios:

| Requisito | Detalhe |
|-----------|---------|
| HTTPS | Obrigatório em produção |
| `manifest.json` | Com campos obrigatórios |
| Ícone 192×192 | PNG maskable |
| Ícone 512×512 | PNG maskable |
| `start_url` | Deve responder offline |
| Service Worker | Registrado e controlando a página |
| `display` | `standalone`, `minimal-ui` ou `fullscreen` |

---

### 5.2 Web App Manifest

Crie em `abril-pra-angola/manifest.json`:

```json
{
  "name": "Abril pra Angola",
  "short_name": "Abril Angola",
  "description": "Festival internacional de Capoeira Angola",
  "start_url": "/",
  "scope": "/",
  "display": "standalone",
  "orientation": "portrait-primary",
  "theme_color": "#c8a96e",
  "background_color": "#1a1a1a",
  "lang": "pt-BR",
  "icons": [
    {
      "src": "/assets/images/pwa/icon-192.png",
      "sizes": "192x192",
      "type": "image/png",
      "purpose": "any maskable"
    },
    {
      "src": "/assets/images/pwa/icon-512.png",
      "sizes": "512x512",
      "type": "image/png",
      "purpose": "any maskable"
    }
  ],
  "screenshots": [
    {
      "src": "/assets/images/pwa/screenshot-desktop.jpg",
      "sizes": "1280x720",
      "type": "image/jpeg",
      "form_factor": "wide"
    },
    {
      "src": "/assets/images/pwa/screenshot-mobile.jpg",
      "sizes": "390x844",
      "type": "image/jpeg",
      "form_factor": "narrow"
    }
  ]
}
```

---

### 5.3 Tags no `<head>` para PWA

```html
<!-- Manifest -->
<link rel="manifest" href="/manifest.json">

<!-- Theme color (barra do browser no mobile) -->
<meta name="theme-color" content="#c8a96e">

<!-- Apple / iOS -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Abril Angola">
<link rel="apple-touch-icon" href="/assets/images/pwa/icon-192.png">
```

```php
// WordPress — enqueue do manifest e meta tags
add_action( 'wp_head', 'apla_pwa_head_tags', 1 );
function apla_pwa_head_tags(): void {
    echo '<link rel="manifest" href="' . esc_url( get_template_directory_uri() . '/manifest.json' ) . '">' . "\n";
    echo '<meta name="theme-color" content="#c8a96e">' . "\n";
    echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="apple-mobile-web-app-title" content="Abril Angola">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url( get_template_directory_uri() . '/assets/images/pwa/icon-192.png' ) . '">' . "\n";
}
```

---

### 5.4 Service Worker — Estratégia Cache-First

Crie em `abril-pra-angola/sw.js`:

```js
const CACHE_NAME   = 'apla-v1';
const OFFLINE_URL  = '/offline/';
const STATIC_ASSETS = [
  '/',
  OFFLINE_URL,
  '/assets/css/main.css',
  '/assets/js/main.js',
  '/assets/images/pwa/icon-192.png',
  '/assets/images/pwa/icon-512.png',
];

// Instala e pré-cacheia assets estáticos
self.addEventListener( 'install', ( event ) => {
  event.waitUntil(
    caches.open( CACHE_NAME ).then( ( cache ) => cache.addAll( STATIC_ASSETS ) )
  );
  self.skipWaiting();
} );

// Limpa caches antigos na ativação
self.addEventListener( 'activate', ( event ) => {
  event.waitUntil(
    caches.keys().then( ( keys ) =>
      Promise.all( keys.filter( ( k ) => k !== CACHE_NAME ).map( ( k ) => caches.delete( k ) ) )
    )
  );
  self.clients.claim();
} );

// Estratégia: Network First para HTML, Cache First para assets
self.addEventListener( 'fetch', ( event ) => {
  if ( event.request.mode === 'navigate' ) {
    // Páginas HTML — Network First
    event.respondWith(
      fetch( event.request ).catch( () => caches.match( OFFLINE_URL ) )
    );
    return;
  }

  // Assets estáticos — Cache First
  event.respondWith(
    caches.match( event.request ).then(
      ( cached ) => cached ?? fetch( event.request ).then( ( response ) => {
        const clone = response.clone();
        caches.open( CACHE_NAME ).then( ( cache ) => cache.put( event.request, clone ) );
        return response;
      } )
    )
  );
} );
```

---

### 5.5 Registro do Service Worker

```js
// assets/js/main.js — registrar SW apenas em produção
if ( 'serviceWorker' in navigator ) {
  window.addEventListener( 'load', () => {
    navigator.serviceWorker
      .register( '/sw.js', { scope: '/' } )
      .catch( ( err ) => console.warn( 'SW registration failed:', err ) );
  } );
}
```

---

## 6. Checklist de Validação — antes de cada deploy

Antes de qualquer entrega, valide **todos** os itens abaixo:

### Performance
- [ ] LCP ≤ 2,5 s (verificar no Lighthouse)
- [ ] CLS ≤ 0,1 (sem saltos de layout)
- [ ] INP ≤ 200 ms
- [ ] Imagem LCP com `fetchpriority="high"` e `loading="eager"`
- [ ] Todas as demais imagens com `loading="lazy"`, `width` e `height`
- [ ] Fontes com `font-display: swap`
- [ ] Scripts com `defer` ou `type="module"`
- [ ] CSS minificado
- [ ] Imagens em WebP

### Acessibilidade
- [ ] Contraste ≥ 4,5:1 em todo texto normal
- [ ] Skip link presente e funcional
- [ ] `<html lang="pt-BR">` presente
- [ ] `<main id="main-content">` presente
- [ ] Único `<h1>` por página
- [ ] Todos os inputs com `<label>` associado
- [ ] Todos os ícones interativos com `aria-label`
- [ ] `focus-visible` visível em todos os elementos interativos
- [ ] `prefers-reduced-motion` respeitado

### SEO
- [ ] `<title>` único e entre 50–60 caracteres
- [ ] `<meta name="description">` entre 120–160 caracteres
- [ ] `<link rel="canonical">` presente
- [ ] Open Graph completo (`og:title`, `og:description`, `og:image`, `og:url`)
- [ ] JSON-LD com Schema.org adequado ao tipo de conteúdo
- [ ] Todos os links com texto descritivo
- [ ] Sitemap gerado e acessível

### Práticas Recomendadas
- [ ] HTTPS ativo
- [ ] Headers de segurança configurados
- [ ] Zero erros no console
- [ ] Nenhum `console.log()` em produção
- [ ] `rel="noopener noreferrer"` em todos os links `target="_blank"`
- [ ] Nenhuma API deprecated em uso

### PWA
- [ ] `manifest.json` válido (verificar no Lighthouse)
- [ ] Ícones 192×192 e 512×512 maskable
- [ ] Service Worker registrado
- [ ] Página offline funcional
- [ ] `<meta name="theme-color">` presente
- [ ] `<link rel="apple-touch-icon">` presente

---

## Regra Principal de Qualidade

> Não existe "entregar depois e otimizar depois". Performance, Acessibilidade, SEO, Práticas Recomendadas e PWA são requisitos de **entrada** — não de finalização.
>
> **Todo componente, template e feature já nasce com nota 100.**
