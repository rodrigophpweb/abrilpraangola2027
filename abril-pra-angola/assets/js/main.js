/**
 * Abril pra Angola — main.js
 * Comportamentos globais do tema
 */

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
    function getPrecoBase() {
        const opt = selectPacote.options[ selectPacote.selectedIndex ];
        return parseFloat( opt?.dataset?.preco ) || 0;
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
        const preco = getPrecoBase();
        const opt   = getOpcaoAtual();
        if ( opt?.value && preco > 0 ) {
            valorPreco.textContent = formatBRL( preco );
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

    function atualizarValorTotal() {
        const pagamento = selectPagamento.value;
        const preco     = getPrecoBase();
        const transp    = getTransporte();
        const total     = preco + transp;

        // Exibir total só quando for depósito ou pix e houver pacote selecionado
        if ( ( pagamento === 'deposito' || pagamento === 'pix' ) && preco > 0 ) {
            if ( valorTotalDisplay ) valorTotalDisplay.textContent = formatBRL( total );
            if ( valorTotalTranspMsg ) valorTotalTranspMsg.style.display = transp > 0 ? 'inline' : 'none';
            if ( boxValorTotal ) boxValorTotal.style.display = 'block';
        } else {
            if ( boxValorTotal ) boxValorTotal.style.display = 'none';
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

} )();
