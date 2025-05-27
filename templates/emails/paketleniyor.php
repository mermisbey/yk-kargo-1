<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

// E-postanın başlığı ve içeriği burada tanımlanabilir.
?>
<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <p><?php _e( 'Siparişiniz hazırlanıyor', 'woocommerce' ); ?></p>

    <p><?php printf( __( 'Sipariş Numarası: %s', 'woocommerce' ), $order->get_order_number() ); ?></p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>