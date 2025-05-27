<?php
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

?>
<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

    <p><?php _e( 'Siparişiniz kargoya verildi. Yakında size ulaşacak!', 'woocommerce' ); ?></p>

    <p><?php printf( __( 'Sipariş Numarası: %s', 'woocommerce' ), $order->get_order_number() ); ?></p>

<?php do_action( 'woocommerce_email_footer', $email ); ?>