<?php
    if(!class_exists('WC_Paketleniyor_Email')){
        class WC_Paketleniyor_Email extends WC_Email {

            public $recipient;
            public function __construct() {
                $this->id = 'wc-gar-yik-paket';
                $this->title = 'Paketleniyor';
                $this->description = 'Sipariş paketleme aşamasına geçtiğinde müşterilere email gönder.';
                $this->template_base = yik_plugin_dir . 'templates/';
                $this->template_html = 'emails/paketleniyor.php';
                $this->template_plain = 'emails/plain/paket-hazirlaniyor.php';

                $this->subject = 'Siparişiniz Paketleniyor';
                $this->heading = 'Siparişiniz Paketleniyor';

                parent::__construct();
            }

            public function trigger( $order_id ) {
                if ( $order_id ) {
                    $this->object = wc_get_order( $order_id );
                    $this->recipient = $this->object->get_billing_email();
                    $this->placeholders['{order_number}'] = $this->object->get_order_number();
                    parent::trigger( $order_id );
                }
            }
        }
    }

    if(!class_exists('WC_Kargoda_Email')){
        class WC_Kargoda_Email extends WC_Email {
            public function __construct() {
                $this->id = 'wc-gar-yik-kargo';
                $this->title = 'Kargoya Verildi';
                $this->description = 'Sipariş kargoya verildiğinde müşterilere email gönder.';
                $this->template_base = yik_plugin_dir . 'templates/';


                $this->template_html = 'emails/kargoda.php';
                $this->template_plain = 'emails/plain/kargoda.php';

                $this->subject = 'Siparişiniz Kargoya Verildi';
                $this->heading = 'Siparişiniz Kargoya Verildi';

                parent::__construct();
            }

            public function trigger( $order_id ) {
                if ( $order_id ) {
                    $this->object = wc_get_order( $order_id );
                    $this->recipient = $this->object->get_billing_email();
                    $this->placeholders['{order_number}'] = $this->object->get_order_number();
                    parent::trigger( $order_id );
                }
            }
        }
    }

    if(!class_exists('WC_Teslim_Edildi_Email')){
        class WC_Teslim_Edildi_Email extends WC_Email {
            public function __construct() {
                $this->id = 'wc-gar-yik-teslim';
                $this->title = 'Teslim Edildi';
                $this->description = 'Sipariş teslim edildiğinde müşterilere email gönder.';
                $this->template_base = yik_plugin_dir . 'templates/';
                $this->template_html = 'emails/teslim-edildi.php';
                $this->template_plain = 'emails/plain/teslim-edildi.php';
                $this->subject = sprintf(__('Siparişiniz Teslim Edildi - #%s', 'woocommerce'), '{order_number}');
                $this->heading = 'Siparişiniz Teslim Edildi';
                parent::__construct();
            }

            public function trigger( $order_id ) {
                if ( $order_id ) {
                    $this->object = wc_get_order( $order_id );
                    $this->recipient = $this->object->get_billing_email();
                    $this->placeholders['{order_number}'] = $this->object->get_order_number();
                    parent::trigger( $order_id );
                }
            }
        }
    }