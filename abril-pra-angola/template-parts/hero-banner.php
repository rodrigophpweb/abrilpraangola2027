<section class="event-countdown" aria-labelledby="event-countdown-title">
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

<style>
    .event-countdown {
        padding: 80px 20px;
        background: #111;
        color: #fff;
        font-family: Arial, sans-serif;
        text-align: center;

        & .event-countdown__header {
            max-width: 760px;
            margin: 0 auto 48px;
        }

        & .event-countdown__eyebrow {
            margin-bottom: 12px;
            color: #d4af37;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        & h2 {
            margin: 0 0 16px;
            font-size: clamp(32px, 5vw, 56px);
            line-height: 1.1;
        }

        & .event-countdown__text {
            margin: 0 auto 18px;
            max-width: 620px;
            color: #ddd;
            font-size: 18px;
            line-height: 1.6;
        }

        & .event-countdown__date {
            display: inline-block;
            color: #fff;
            font-size: 16px;
            font-weight: 700;
        }

        & .event-countdown__list {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            max-width: 900px;
            margin: 0 auto;
            padding: 0;
            list-style: none;
        }

        & .event-countdown__item {
            position: relative;
            overflow: hidden;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            min-width: 0;
            min-height: 180px;
            padding: 28px 16px;

            background: #1c1c1c;
            border: 1px solid rgba(212, 175, 55, 0.45);
            border-radius: 18px;

            transition:
                    transform .25s ease,
                    border-color .25s ease,
                    box-shadow .25s ease,
                    background .25s ease;

            &::before {
                content: "";
                position: absolute;
                inset: 0;
                background:
                        radial-gradient(
                                circle at top left,
                                rgba(212, 175, 55, .18),
                                transparent 45%
                        );
                opacity: 0;
                transition: opacity .25s ease;
            }

            &:hover {
                transform: translateY(-8px) scale(1.02);
                border-color: rgba(212, 175, 55, .9);
                box-shadow:
                        0 18px 40px rgba(212, 175, 55, .15),
                        inset 0 0 0 1px rgba(212, 175, 55, .08);
            }

            &:hover::before {
                opacity: 1;
            }

            & time,
            & span {
                position: relative;
                z-index: 2;
            }

            & time {
                display: block;
                color: #d4af37;
                font-size: clamp(42px, 6vw, 72px);
                font-weight: 800;
                line-height: 1;
                transition: transform .25s ease;
            }

            & span {
                display: block;
                margin-top: 12px;
                color: #fff;
                font-size: 15px;
                font-weight: 700;
                letter-spacing: 1px;
                text-transform: uppercase;
            }

            &:hover time {
                animation: countdownPulse .45s ease;
            }
        }

        & .event-countdown__cta {
            margin-top: 42px;
            justify-content: center;
            background-color: transparent;
        }

        & .event-countdown__button {
            position: relative;
            overflow: hidden;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            min-height: 58px;
            padding: 16px 36px;

            border-radius: 999px;
            background: #d4af37;
            color: #111;
            text-decoration: none;

            font-size: 16px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;

            transition:
                    transform .2s ease,
                    box-shadow .2s ease,
                    opacity .2s ease;

            box-shadow:
                    0 8px 24px rgba(212, 175, 55, .20);

            &::after {
                content: "";
                position: absolute;
                top: 0;
                left: -80%;

                width: 40%;
                height: 100%;

                background:
                        linear-gradient(
                                120deg,
                                transparent,
                                rgba(255, 255, 255, .55),
                                transparent
                        );

                transform: skewX(-20deg);
            }

            &:hover {
                transform: translateY(-4px) scale(1.04);
                box-shadow:
                        0 16px 36px rgba(212, 175, 55, .35);
            }

            &:hover::after {
                animation: buttonShine .8s ease;
            }

            &:focus-visible {
                outline: 3px solid #fff;
                outline-offset: 4px;
            }
        }

        @media (max-width: 768px) {
            & .event-countdown__list {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 420px) {
            & .event-countdown__list {
                grid-template-columns: 1fr;
            }
        }
    }

    @keyframes countdownPulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.12);
        }

        100% {
            transform: scale(1);
        }
    }

    @keyframes buttonShine {
        from {
            left: -80%;
        }

        to {
            left: 140%;
        }
    }

    /* ==========================
   ENTRY ANIMATIONS
========================== */

    .event-countdown {
        & .event-countdown__header,
        & .event-countdown__item,
        & .event-countdown__cta {
            opacity: 0;
            transform: translateY(30px);
        }

        &.is-visible {

            & .event-countdown__header {
                animation:
                        fadeSlideUp .8s ease forwards;
            }

            & .event-countdown__item:nth-child(1) {
                animation:
                        fadeSlideUp .7s ease .15s forwards;
            }

            & .event-countdown__item:nth-child(2) {
                animation:
                        fadeSlideUp .7s ease .3s forwards;
            }

            & .event-countdown__item:nth-child(3) {
                animation:
                        fadeSlideUp .7s ease .45s forwards;
            }

            & .event-countdown__item:nth-child(4) {
                animation:
                        fadeSlideUp .7s ease .6s forwards;
            }

            & .event-countdown__cta {
                animation:
                        fadeSlideUp .7s ease .8s forwards;
            }
        }
    }

    @keyframes fadeSlideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .event-countdown {
            & .event-countdown__header,
            & .event-countdown__item,
            & .event-countdown__cta {
                opacity: 1;
                transform: none;
                animation: none;
            }
        }
    }
</style>

<script>
    const eventDate =
        new Date('2027-04-29T09:00:00').getTime();

    const countdownElements = {
        days:       document.getElementById('countdown-days'),
        hours:      document.getElementById('countdown-hours'),
        minutes:    document.getElementById('countdown-minutes'),
        seconds:    document.getElementById('countdown-seconds')
    };

    const datetimeFormats = {
        days:       value => `P${value}D`,
        hours:      value => `PT${value}H`,
        minutes:    value => `PT${value}M`,
        seconds:    value => `PT${value}S`
    };

    function updateCountdown() {
        const now = Date.now();
        const distance = eventDate - now;

        const timeValues = {
            days: distance <= 0
                ? 0
                : Math.floor(distance / (1000 * 60 * 60 * 24)),

            hours: distance <= 0
                ? 0
                : Math.floor(
                    (distance / (1000 * 60 * 60)) % 24
                ),

            minutes: distance <= 0
                ? 0
                : Math.floor(
                    (distance / (1000 * 60)) % 60
                ),

            seconds: distance <= 0
                ? 0
                : Math.floor(
                    (distance / 1000) % 60
                )
        };

        Object.entries(timeValues).forEach(
            ([unit, value]) => {
                const element =
                    countdownElements[unit];

                element.textContent =
                    String(value).padStart(2, '0');

                element.setAttribute(
                    'datetime',
                    datetimeFormats[unit](value)
                );
            }
        );
    }

    updateCountdown();

    setInterval(updateCountdown, 1000);
    const countdownSection =
        document.querySelector('.event-countdown');

    window.addEventListener('load', () => {
        setTimeout(() => {
            countdownSection.classList.add(
                'is-visible'
            );
        }, 150);
    });
</script>