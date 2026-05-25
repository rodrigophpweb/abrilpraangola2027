<?php
/**
 * Imagem destacada da página home como fundo do hero.
 * Passada como CSS custom property para o stylesheet do componente.
 * Fallback: fundo escuro sólido quando não há imagem.
 */
$front_page_id  = (int) get_option( 'page_on_front' );
$hero_image_url = $front_page_id
    ? get_the_post_thumbnail_url( $front_page_id, 'full' )
    : '';

// CSS custom property definida inline — acessível pelo stylesheet
$hero_style = $hero_image_url
    ? 'style="--hero-bg-image: url(' . esc_url( $hero_image_url ) . ');"'
    : '';
?>

<section
    class="event-countdown"
    aria-labelledby="event-countdown-title"
    <?php echo $hero_style; ?>
>
    <header class="event-countdown__header">
        <p class="event-countdown__eyebrow">Abril pra Angola 2027</p>
        <h2 id="event-countdown-title">A contagem regressiva já começou</h2>
        <p class="event-countdown__text">
            Prepare-se para quatro dias de oficinas, vivências e muita ancestralidade na capoeira.
        </p>
        <time class="event-countdown__date" datetime="2027-04-29T09:00:00">
            29 de abril a 02 de maio de 2027
        </time>
    </header>

    <ul class="event-countdown__list" aria-label="Contagem regressiva para o início do evento">
        <li class="event-countdown__item">
            <time id="countdown-days" datetime="P0D">00</time>
            <span>Dias</span>
        </li>
        <li class="event-countdown__item">
            <time id="countdown-hours" datetime="PT0H">00</time>
            <span>Horas</span>
        </li>
        <li class="event-countdown__item">
            <time id="countdown-minutes" datetime="PT0M">00</time>
            <span>Minutos</span>
        </li>
        <li class="event-countdown__item">
            <time id="countdown-seconds" datetime="PT0S">00</time>
            <span>Segundos</span>
        </li>
    </ul>

    <footer class="event-countdown__cta">
        <a href="/inscricao/" class="event-countdown__button">
            Quero me inscrever
        </a>
    </footer>
</section>
