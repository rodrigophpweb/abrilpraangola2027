<footer>
    <nav>
        <h6>Todos os direitos reservados - Abril pra Angola 2024</h6>
        <small>Desenvolvido por: <a href="https://programadorweb.com.br" class="footer-link" target="_blank" rel="noopener noreferrer">Programador Web</a></small>
    </nav>

    <?php
    $wa_phone   = function_exists( 'get_field' ) ? get_field( 'whatsapp_numero', 'option' )    : '';
    $wa_message = function_exists( 'get_field' ) ? get_field( 'whatsapp_mensagem', 'option' )  : '';
    $wa_color   = function_exists( 'get_field' ) ? get_field( 'whatsapp_cor_botao', 'option' ) : '';

    $wa_phone   = ! empty( $wa_phone )   ? $wa_phone   : '5511983929196';
    $wa_message = ! empty( $wa_message ) ? $wa_message : 'Seja Bem-Vindo ao Abril pra Angola, é um prazer imenso falar com você? O que podemos te ajudar sobre o evento?';
    $wa_color   = ! empty( $wa_color )   ? $wa_color   : 'var(--color-primary-0)';

    $wa_phone_clean = preg_replace( '/\D/', '', $wa_phone );
    $wa_url         = 'https://wa.me/' . $wa_phone_clean . '?text=' . rawurlencode( $wa_message );
    ?>
    <a href="<?php echo esc_url( $wa_url ); ?>"
       class="whatsapp-float"
       style="background-color: <?php echo esc_attr( $wa_color ); ?>;"
       target="_blank"
       rel="noopener noreferrer"
       aria-label="<?php esc_attr_e( 'Fale conosco pelo WhatsApp', 'abril-pra-angola' ); ?>">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>

</footer>
<?php wp_footer(); ?>
</body>
</html>

