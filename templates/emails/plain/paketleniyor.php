<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }
    ?>
<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <?php _e( 'Siparişiniz paketleniyor.', 'woocommerce' ); ?><?php printf( __( 'Sipariş Numarası: %s', 'woocommerce' ), $order->get_order_number() ); ?>

<?php do_action( 'woocommerce_email_footer', $email ); ?>