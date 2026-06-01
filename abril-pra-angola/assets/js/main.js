/**
 * Abril pra Angola — main.js
 * Comportamentos globais do tema
 */

/* ────────────────────────────────────────────────────
   NAVEGAÇÃO POR ÂNCORAS — Smooth Scroll com offset
   • Na home: intercepta cliques no nav e rola suavemente
     compensando a altura do header fixo.
   • Em páginas internas: os links levam para /#secao;
     o browser navega para a home e ao carregar, o JS
     rola suavemente para a secao correta.
──────────────────────────────────────────────────── */
( function () {
    'use strict';

    // Lê a altura atual do header via CSS custom property
    function getHeaderHeight() {
        const raw = getComputedStyle( document.documentElement )
                        .getPropertyValue( '--header-height' )
                        .trim();
        return parseInt( raw, 10 ) || 182;
    }

    // Rola suavemente até o elemento, compensando o header
    function scrollToAnchor( target ) {
        if ( ! target ) return;
        const offsetTop = target.getBoundingClientRect().top
                        + window.scrollY
                        - getHeaderHeight()
                        - 16; // 16px de buffer extra
        window.scrollTo( { top: Math.max( 0, offsetTop ), behavior: 'smooth' } );
    }

    // ── Força o header a aparecer durante scroll por âncora ──
    // Sinaliza ao módulo do header para não esconder enquanto
    // o scroll suave por âncora estiver em andamento.
    function lockHeaderVisible( duration ) {
        window._abrilAnchorScrolling = true;
        const siteHeader = document.querySelector( '.site-header' );
        if ( siteHeader ) {
            siteHeader.classList.remove( 'is-hidden' );
        }
        clearTimeout( window._abrilAnchorScrollingTimer );
        window._abrilAnchorScrollingTimer = setTimeout( function () {
            window._abrilAnchorScrolling = false;
        }, duration || 1000 );
    }

    // ── Intercepta cliques nos itens do menu ─────────────
    document.querySelectorAll( '.site-nav__list a[href*="#"]' ).forEach( function ( link ) {
        link.addEventListener( 'click', function ( e ) {
            const href      = link.getAttribute( 'href' ) || '';
            const hashIndex = href.indexOf( '#' );
            if ( hashIndex === -1 ) return;

            const hash     = href.slice( hashIndex + 1 );
            const basePath = href.slice( 0, hashIndex ); // pode ser '', '/', ou URL completa

            // Verifica se a âncora pertence à página atual
            const currentOrigin   = window.location.origin;
            const currentPathname = window.location.pathname;
            const linkIsLocal     = basePath === ''
                                 || basePath === '/'
                                 || basePath === currentPathname
                                 || basePath === currentOrigin + currentPathname
                                 || basePath === currentOrigin + '/';

            if ( ! linkIsLocal ) return; // deixa o browser navegar para outra página

            const target = document.getElementById( hash );
            if ( ! target ) return; // elemento não existe nesta página → browser navega

            e.preventDefault();

            // Mantém o header visível durante todo o scroll suave
            lockHeaderVisible( 1000 );

            scrollToAnchor( target );

            // Atualiza o hash na URL sem recarregar
            if ( history.pushState ) {
                history.pushState( null, '', '#' + hash );
            }
        } );
    } );

    // ── Ao carregar a página com hash na URL ─────────────
    // (caso: usuário veio de página interna com link /#secao)
    function handleInitialHash() {
        const hash = window.location.hash.slice( 1 );
        if ( ! hash ) return;
        const target = document.getElementById( hash );
        if ( ! target ) return;
        // Pequeno delay para garantir que o layout está pronto
        setTimeout( function () {
            lockHeaderVisible( 1200 );
            scrollToAnchor( target );
        }, 350 );
    }

    // Executa após carregamento completo (imagens, fontes)
    window.addEventListener( 'load', handleInitialHash );

} )();

( function () {
    'use strict';

    /* ────────────────────────────────────────────────
       HEADER — Esconde ao fazer scroll para baixo,
       aparece ao fazer scroll para cima
    ──────────────────────────────────────────────────── */
    const header    = document.querySelector( '.site-header' );
    let lastScrollY = window.scrollY;
    let ticking     = false;

    function updateHeader() {
        const currentScrollY = window.scrollY;

        // Adiciona sombra ao sair do topo
        header.classList.toggle( 'is-scrolled', currentScrollY > 10 );

        // Durante scroll por âncora: mantém o header visível
        if ( window._abrilAnchorScrolling ) {
            header.classList.remove( 'is-hidden' );
            lastScrollY = currentScrollY;
            ticking     = false;
            return;
        }

        // Esconde ao descer, mostra ao subir
        if ( currentScrollY > lastScrollY && currentScrollY > 182 ) {
            header.classList.add( 'is-hidden' );
        } else {
            header.classList.remove( 'is-hidden' );
        }

        lastScrollY = currentScrollY;
        ticking     = false;
    }

    window.addEventListener( 'scroll', function () {
        if ( ! ticking ) {
            window.requestAnimationFrame( updateHeader );
            ticking = true;
        }
    }, { passive: true } );


    /* ────────────────────────────────────────────────
       MENU MOBILE — Toggle hamburguer
    ──────────────────────────────────────────────────── */
    const toggleBtn = document.querySelector( '.site-header__toggle' );
    const body      = document.body;

    if ( toggleBtn ) {
        toggleBtn.addEventListener( 'click', function () {
            const isOpen = body.classList.toggle( 'menu-open' );

            // Actualiza o atributo ARIA para acessibilidade
            toggleBtn.setAttribute( 'aria-expanded', isOpen );
            toggleBtn.setAttribute(
                'aria-label',
                isOpen ? 'Fechar menu de navegação' : 'Abrir menu de navegação'
            );

            // Impede o scroll do body enquanto o menu está aberto
            body.style.overflow = isOpen ? 'hidden' : '';
        } );

        // Fecha o menu ao clicar num link
        document.querySelectorAll( '.site-nav__list a' ).forEach( function ( link ) {
            link.addEventListener( 'click', function () {
                body.classList.remove( 'menu-open' );
                body.style.overflow = '';
                toggleBtn.setAttribute( 'aria-expanded', 'false' );
                toggleBtn.setAttribute( 'aria-label', 'Abrir menu de navegação' );
            } );
        } );

        // Fecha o menu ao pressionar Escape
        document.addEventListener( 'keydown', function ( e ) {
            if ( e.key === 'Escape' && body.classList.contains( 'menu-open' ) ) {
                body.classList.remove( 'menu-open' );
                body.style.overflow = '';
                toggleBtn.setAttribute( 'aria-expanded', 'false' );
                toggleBtn.setAttribute( 'aria-label', 'Abrir menu de navegação' );
                toggleBtn.focus();
            }
        } );
    }

} )();


/* ────────────────────────────────────────────────────
   COUNTDOWN — Hero Banner
   Atualiza o contador regressivo a cada segundo.
──────────────────────────────────────────────────── */
( function () {
    'use strict';

    const countdownSection = document.querySelector( '.event-countdown' );
    if ( ! countdownSection ) return;

    const eventDate = new Date( '2027-04-29T09:00:00' ).getTime();

    const els = {
        days:    document.getElementById( 'countdown-days' ),
        hours:   document.getElementById( 'countdown-hours' ),
        minutes: document.getElementById( 'countdown-minutes' ),
        seconds: document.getElementById( 'countdown-seconds' ),
    };

    function formatDatetime( unit, value ) {
        switch ( unit ) {
            case 'days':
                return `P${ value }D`;
            case 'hours':
                return `PT${ value }H`;
            case 'minutes':
                return `PT${ value }M`;
            case 'seconds':
                return `PT${ value }S`;
            default:
                return '';
        }
    }

    function updateCountdown() {
        const distance = eventDate - Date.now();

        const values = {
            days:    distance <= 0 ? 0 : Math.floor( distance / 86400000 ),
            hours:   distance <= 0 ? 0 : Math.floor( ( distance / 3600000 ) % 24 ),
            minutes: distance <= 0 ? 0 : Math.floor( ( distance / 60000 ) % 60 ),
            seconds: distance <= 0 ? 0 : Math.floor( ( distance / 1000 ) % 60 ),
        };

        Object.entries( values ).forEach( ( [ unit, value ] ) => {
            const el = els[ unit ];
            if ( ! el ) return;
            el.textContent = String( value ).padStart( 2, '0' );
            el.setAttribute( 'datetime', formatDatetime( unit, value ) );
        } );
    }

    updateCountdown();
    setInterval( updateCountdown, 1000 );

    window.addEventListener( 'load', function () {
        setTimeout( function () {
            countdownSection.classList.add( 'is-visible' );
        }, 150 );
    } );

} )();


/* ────────────────────────────────────────────────────
   FORMULÁRIO DE INSCRIÇÃO — Pacote / Pagamento
   Exibe preço do pacote selecionado e link Mercado Pago
   dinamicamente conforme a seleção do utilizador.
──────────────────────────────────────────────────── */
( function () {
    'use strict';

    // ── Referências ───────────────────────────────────
    const selectPacote    = document.getElementById( 'pacote_id' );
    const selectPagamento = document.getElementById( 'forma_pagamento' );
    const boxPreco        = document.getElementById( 'pacote-preco' );
    const valorPreco      = document.getElementById( 'pacote-preco-valor' );
    const boxMP           = document.getElementById( 'link-mercado-pago' );
    const btnMP           = document.getElementById( 'btn-mercado-pago' );
    const checkTransporte = document.querySelector( 'input[name="transporte"]' );
    const hiddenValorTotal = document.getElementById( 'valor_total_hidden' );

    // Alergia
    const selAlimento     = document.getElementById( 'alergia_alimento' );
    const wrapAlimento    = document.getElementById( 'alergia_alimento_wrap' );
    const selRemedio      = document.getElementById( 'alergia_remedio' );
    const wrapRemedio     = document.getElementById( 'alergia_remedio_wrap' );
    const inputAlimentoDesc = document.getElementById( 'alergia_alimento_desc' );
    const inputRemedioDesc  = document.getElementById( 'alergia_remedio_desc' );

    // PIX
    const boxPix          = document.getElementById( 'box-pix' );
    const pixQrImg        = document.getElementById( 'pix-qrcode-img' );
    const pixChaveDisplay = document.getElementById( 'pix-chave-display' );
    const pixPayloadDisplay = document.getElementById( 'pix-payload-display' );
    const pixCopiaColaWrap  = document.getElementById( 'pix-copia-cola-wrap' );
    const btnCopiarChave    = document.getElementById( 'pix-copiar-chave' );
    const btnCopiarPayload  = document.getElementById( 'pix-copiar-payload' );

    // Total
    const boxValorTotal         = document.getElementById( 'box-valor-total' );
    const valorTotalDisplay     = document.getElementById( 'valor-total-display' );
    const valorTotalTranspMsg   = document.getElementById( 'valor-total-transporte-msg' );
    const valorTotalCartaoMsg   = document.getElementById( 'valor-total-cartao-msg' );

    // Deposito
    const boxDeposito = document.getElementById( 'box-deposito' );

    if ( ! selectPacote || ! selectPagamento ) return;

    // ── Máscara Celular ───────────────────────────────
    const inputCelular = document.getElementById( 'celular' );
    if ( inputCelular ) {
        inputCelular.addEventListener( 'input', function () {
            let v = this.value.replace( /\D/g, '' ).substring( 0, 11 );
            if ( v.length > 10 ) {
                v = v.replace( /^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3' );
            } else if ( v.length > 6 ) {
                v = v.replace( /^(\d{2})(\d{4})(\d{0,4})$/, '($1) $2-$3' );
            } else if ( v.length > 2 ) {
                v = v.replace( /^(\d{2})(\d{0,5})$/, '($1) $2' );
            } else if ( v.length > 0 ) {
                v = v.replace( /^(\d{0,2})$/, '($1' );
            }
            this.value = v;
        } );
    }

    // ── Alergia — mostrar/ocultar campos de texto ─────
    function toggleAlergia( select, wrap, input ) {
        if ( ! select || ! wrap ) return;
        const mostrar = select.value === 'sim';
        wrap.style.display = mostrar ? 'block' : 'none';
        if ( input ) input.required = mostrar;
    }

    if ( selAlimento ) {
        selAlimento.addEventListener( 'change', function () {
            toggleAlergia( selAlimento, wrapAlimento, inputAlimentoDesc );
        } );
    }
    if ( selRemedio ) {
        selRemedio.addEventListener( 'change', function () {
            toggleAlergia( selRemedio, wrapRemedio, inputRemedioDesc );
        } );
    }

    // ── Helpers de Preço ─────────────────────────────
    function getPrecoAvista() {
        const opt = selectPacote.options[ selectPacote.selectedIndex ];
        return parseFloat( opt?.dataset?.precoAvista ) || 0;
    }

    function getPrecoCartao() {
        const opt = selectPacote.options[ selectPacote.selectedIndex ];
        return parseFloat( opt?.dataset?.precoCartao ) || 0;
    }

    function isCartao() {
        return selectPagamento.value === 'cartao';
    }

    function getPrecoBase() {
        return isCartao() ? getPrecoCartao() : getPrecoAvista();
    }

    function getTransporte() {
        return checkTransporte && checkTransporte.checked ? 70 : 0;
    }

    function getTotal() {
        return getPrecoBase() + getTransporte();
    }

    function formatBRL( valor ) {
        return 'R$ ' + valor.toLocaleString( 'pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 } );
    }

    // ── PIX Payload (EMV / Pix Copia e Cola) ─────────
    function pixCampo( id, valor ) {
        const tam = String( valor.length ).padStart( 2, '0' );
        return id + tam + valor;
    }

    function crc16Ccitt( str ) {
        let crc = 0xFFFF;
        for ( let i = 0; i < str.length; i++ ) {
            crc ^= str.charCodeAt( i ) << 8;
            for ( let j = 0; j < 8; j++ ) {
                crc = ( crc & 0x8000 ) ? ( ( crc << 1 ) ^ 0x1021 ) : ( crc << 1 );
                crc &= 0xFFFF;
            }
        }
        return crc.toString( 16 ).padStart( 4, '0' ).toUpperCase();
    }

    function gerarPixPayload( chave, nome, cidade, valor ) {
        const gui = pixCampo( '00', 'BR.GOV.BCB.PIX' ) + pixCampo( '01', chave );
        const merchantAccount = pixCampo( '26', gui );

        let payload = pixCampo( '00', '01' );
        payload += merchantAccount;
        payload += pixCampo( '52', '0000' );
        payload += pixCampo( '53', '986' );
        if ( valor > 0 ) {
            payload += pixCampo( '54', valor.toFixed( 2 ) );
        }
        payload += pixCampo( '58', 'BR' );
        payload += pixCampo( '59', nome.substring( 0, 25 ) );
        payload += pixCampo( '60', cidade.substring( 0, 15 ) );
        payload += pixCampo( '62', pixCampo( '05', '***' ) );
        payload += '6304';
        payload += crc16Ccitt( payload );
        return payload;
    }

    // ── Atualizar interface ───────────────────────────
    function getOpcaoAtual() {
        return selectPacote.options[ selectPacote.selectedIndex ];
    }

    function atualizarPreco() {
        const opt        = getOpcaoAtual();
        const precoBase  = getPrecoBase();
        if ( opt?.value && precoBase > 0 ) {
            const label = isCartao() ? 'no cartão' : 'à vista';
            valorPreco.textContent = formatBRL( precoBase ) + ' (' + label + ')';
            boxPreco.style.display = 'block';
        } else {
            boxPreco.style.display = 'none';
        }
    }

    function atualizarLinkMP() {
        const opt    = getOpcaoAtual();
        const linkMp = opt?.dataset?.linkMp;
        if ( selectPagamento.value === 'cartao' && linkMp && linkMp.startsWith( 'http' ) ) {
            btnMP.href          = linkMp;
            boxMP.style.display = 'block';
        } else {
            boxMP.style.display = 'none';
        }
    }

    function atualizarValorTotal() {
        const pagamento = selectPagamento.value;
        const preco     = getPrecoBase();
        const transp    = getTransporte();
        const total     = preco + transp;
        const cartao    = isCartao();

        // Exibir total quando houver pacote e forma de pagamento selecionados
        if ( pagamento && preco > 0 ) {
            if ( valorTotalDisplay )   valorTotalDisplay.textContent = formatBRL( total );
            if ( valorTotalTranspMsg ) valorTotalTranspMsg.style.display = transp > 0 ? 'inline' : 'none';
            if ( valorTotalCartaoMsg ) valorTotalCartaoMsg.style.display = cartao ? 'inline' : 'none';
            if ( boxValorTotal )       boxValorTotal.style.display = 'block';

            // Atualiza campo hidden para envio ao servidor
            if ( hiddenValorTotal ) hiddenValorTotal.value = total.toFixed( 2 );
        } else {
            if ( boxValorTotal ) boxValorTotal.style.display = 'none';
            if ( hiddenValorTotal ) hiddenValorTotal.value = '0';
        }
    }

    function atualizarPix() {
        if ( selectPagamento.value !== 'pix' ) {
            if ( boxPix ) boxPix.style.display = 'none';
            return;
        }

        const pix = ( typeof abrilData !== 'undefined' ) ? abrilData : {};
        const chave  = pix.pixChave  || '';
        const nome   = pix.pixNome   || 'Abril pra Angola';
        const cidade = pix.pixCidade || 'Sao Paulo';

        if ( ! chave ) {
            if ( boxPix ) {
                boxPix.innerHTML = '<p>⚠️ Chave PIX não configurada. Entre em contato com os organizadores.</p>';
                boxPix.style.display = 'block';
            }
            return;
        }

        const total   = getTotal();
        const payload = gerarPixPayload( chave, nome, cidade, total );
        const qrUrl   = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' + encodeURIComponent( payload );

        if ( pixChaveDisplay )  pixChaveDisplay.textContent  = chave;
        if ( pixPayloadDisplay ) pixPayloadDisplay.textContent = payload;
        if ( pixQrImg ) {
            pixQrImg.src          = qrUrl;
            pixQrImg.style.display = 'block';
        }
        if ( pixCopiaColaWrap ) pixCopiaColaWrap.style.display = 'block';
        if ( boxPix ) boxPix.style.display = 'block';
    }

    function atualizarDeposito() {
        if ( boxDeposito ) {
            boxDeposito.style.display = selectPagamento.value === 'deposito' ? 'block' : 'none';
        }
    }


    function atualizarTudo() {
        atualizarPreco();
        atualizarLinkMP();
        atualizarDeposito();
        atualizarValorTotal();
        atualizarPix();
    }

    // ── Copiar para clipboard ─────────────────────────
    function copiar( texto, btn ) {
        navigator.clipboard.writeText( texto ).then( function () {
            const orig = btn.textContent;
            btn.textContent = '✅ Copiado!';
            setTimeout( function () { btn.textContent = orig; }, 2000 );
        } );
    }

    if ( btnCopiarChave ) {
        btnCopiarChave.addEventListener( 'click', function () {
            copiar( pixChaveDisplay?.textContent || '', btnCopiarChave );
        } );
    }
    if ( btnCopiarPayload ) {
        btnCopiarPayload.addEventListener( 'click', function () {
            copiar( pixPayloadDisplay?.textContent || '', btnCopiarPayload );
        } );
    }

    // ── Listeners ─────────────────────────────────────
    selectPacote.addEventListener( 'change', atualizarTudo );
    selectPagamento.addEventListener( 'change', atualizarTudo );
    if ( checkTransporte ) {
        checkTransporte.addEventListener( 'change', atualizarTudo );
    }

    // ── Inicialização ─────────────────────────────────
    // Atualiza a UI ao carregar a página caso haja pacote
    // pré-selecionado (ex: vindo de ?pacote=ID da seção de ingressos)
    if ( selectPacote.value ) {
        atualizarTudo();
    }

} )();


/* ────────────────────────────────────────────────────
   CAROUSEL DE PATROCINADORES
   Navegação responsiva: 2 por slide no mobile, 4 no desktop.
   Suporte a setas, bullets, teclado e swipe.
──────────────────────────────────────────────────── */
( function () {
    'use strict';

    const carousel = document.querySelector( '.sponsors-carousel' );
    if ( ! carousel ) return;

    // ── Coleta todos os sponsor-item independente do agrupamento PHP ──
    const allItems      = Array.from( carousel.querySelectorAll( '.sponsor-item' ) );
    if ( allItems.length === 0 ) return;

    const perPageDesktop = parseInt( carousel.dataset.perPage, 10 )       || 4;
    const perPageMobile  = parseInt( carousel.dataset.perPageMobile, 10 ) || 2;
    const mobileBreak    = 768; // px

    const track    = carousel.querySelector( '.sponsors-carousel__track' );
    const btnPrev  = carousel.querySelector( '.sponsors-carousel__btn--prev' );
    const btnNext  = carousel.querySelector( '.sponsors-carousel__btn--next' );
    const navEl    = carousel.parentElement
                        ? carousel.parentElement.querySelector( '.sponsors-carousel__nav' )
                        : null;
    const bulletsContainer = navEl
                        ? navEl.querySelector( '.sponsors-carousel__bullets' )
                        : null;

    let currentPage = 0;
    let lastPerPage = null;
    let slidesArr   = [];
    let bulletsArr  = [];

    function getPerPage() {
        return window.innerWidth < mobileBreak ? perPageMobile : perPageDesktop;
    }

    // ── Reconstrói os slides no DOM com novo per-page ──────────────
    function buildSlides( perPage ) {
        track.innerHTML = '';
        slidesArr = [];

        const totalPages = Math.ceil( allItems.length / perPage );

        for ( let p = 0; p < totalPages; p++ ) {
            const slide = document.createElement( 'li' );
            slide.className = 'sponsors-carousel__slide' + ( p === 0 ? ' is-active' : '' );
            slide.setAttribute( 'role', 'group' );
            slide.setAttribute( 'aria-roledescription', 'slide' );
            slide.setAttribute( 'aria-label', 'Página ' + ( p + 1 ) + ' de ' + totalPages );
            slide.setAttribute( 'data-page', String( p + 1 ) );
            slide.setAttribute( 'aria-hidden', p === 0 ? 'false' : 'true' );

            const group = document.createElement( 'ul' );
            group.className = 'sponsors-carousel__group';
            group.setAttribute( 'role', 'list' );

            const start = p * perPage;
            const end   = Math.min( start + perPage, allItems.length );
            for ( let i = start; i < end; i++ ) {
                group.appendChild( allItems[ i ] );
            }

            slide.appendChild( group );
            track.appendChild( slide );
            slidesArr.push( slide );
        }

        return totalPages;
    }

    // ── Reconstrói os bullets ─────────────────────────────────────
    function buildBullets( totalPages ) {
        if ( ! bulletsContainer ) return;
        bulletsContainer.innerHTML = '';
        bulletsArr = [];

        const showNav = totalPages > 1;
        if ( navEl ) navEl.style.display = showNav ? '' : 'none';
        if ( btnPrev ) btnPrev.style.display = showNav ? '' : 'none';
        if ( btnNext ) btnNext.style.display = showNav ? '' : 'none';

        if ( ! showNav ) return;

        for ( let p = 0; p < totalPages; p++ ) {
            const li  = document.createElement( 'li' );
            li.className = 'sponsors-carousel__bullet-item';
            li.setAttribute( 'role', 'listitem' );

            const btn = document.createElement( 'button' );
            btn.className = 'sponsors-carousel__bullet' + ( p === 0 ? ' is-active' : '' );
            btn.type = 'button';
            btn.setAttribute( 'data-page', String( p + 1 ) );
            btn.setAttribute( 'aria-label', 'Ir para página ' + ( p + 1 ) );
            btn.setAttribute( 'aria-current', p === 0 ? 'true' : 'false' );

            ( function ( pageIndex ) {
                btn.addEventListener( 'click', function () {
                    goToPage( pageIndex );
                } );
            } )( p );

            li.appendChild( btn );
            bulletsContainer.appendChild( li );
            bulletsArr.push( btn );
        }
    }

    // ── Navega para uma página ────────────────────────────────────
    function goToPage( page ) {
        const total = slidesArr.length;
        page = Math.max( 0, Math.min( page, total - 1 ) );
        currentPage = page;

        track.style.transform = 'translateX( -' + ( page * 100 ) + '% )';

        slidesArr.forEach( function ( slide, i ) {
            const active = i === page;
            slide.classList.toggle( 'is-active', active );
            slide.setAttribute( 'aria-hidden', active ? 'false' : 'true' );
        } );

        bulletsArr.forEach( function ( bullet, i ) {
            const active = i === page;
            bullet.classList.toggle( 'is-active', active );
            bullet.setAttribute( 'aria-current', active ? 'true' : 'false' );
        } );

        if ( btnPrev ) {
            btnPrev.disabled = page === 0;
            btnPrev.classList.toggle( 'is-disabled', page === 0 );
        }
        if ( btnNext ) {
            btnNext.disabled = page === total - 1;
            btnNext.classList.toggle( 'is-disabled', page === total - 1 );
        }
    }

    // ── Inicializa / reinicializa o carousel ──────────────────────
    function init() {
        const perPage = getPerPage();
        if ( perPage === lastPerPage ) return; // nada mudou
        lastPerPage = perPage;
        currentPage = 0;

        track.style.transform = 'translateX( 0 )';
        const totalPages = buildSlides( perPage );
        buildBullets( totalPages );
        goToPage( 0 );
    }

    // ── Setas prev / next ─────────────────────────────────────────
    if ( btnPrev ) {
        btnPrev.addEventListener( 'click', function () {
            goToPage( currentPage - 1 );
        } );
    }
    if ( btnNext ) {
        btnNext.addEventListener( 'click', function () {
            goToPage( currentPage + 1 );
        } );
    }

    // ── Teclado (←/→) ────────────────────────────────────────────
    carousel.addEventListener( 'keydown', function ( e ) {
        if ( e.key === 'ArrowLeft' )  goToPage( currentPage - 1 );
        if ( e.key === 'ArrowRight' ) goToPage( currentPage + 1 );
    } );

    // ── Swipe (touch) ─────────────────────────────────────────────
    let touchStartX = 0;

    carousel.addEventListener( 'touchstart', function ( e ) {
        touchStartX = e.changedTouches[ 0 ].clientX;
    }, { passive: true } );

    carousel.addEventListener( 'touchend', function ( e ) {
        const delta = touchStartX - e.changedTouches[ 0 ].clientX;
        if ( Math.abs( delta ) < 50 ) return;
        if ( delta > 0 ) goToPage( currentPage + 1 );
        else             goToPage( currentPage - 1 );
    }, { passive: true } );

    // ── Resize: reinicializa se cruzar o breakpoint ───────────────
    let resizeTimer;
    window.addEventListener( 'resize', function () {
        clearTimeout( resizeTimer );
        resizeTimer = setTimeout( init, 150 );
    } );

    // ── Estado inicial ────────────────────────────────────────────
    init();

} )();

