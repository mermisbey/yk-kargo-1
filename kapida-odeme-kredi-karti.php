<?php
    if (!defined('ABSPATH')) {
        exit;
    }
    add_action('plugins_loaded', 'gar_yik_init_gateway_class');
    function gar_yik_init_gateway_class()
    {
        class WC_GAR_YIK_Gateway extends WC_Payment_Gateway {

            public function __construct() {
                $this->id                 = 'gar_yik_kapida_odeme_kredi_karti';
                $this->icon               = '';
                $this->method_title       = 'Kapıda Ödeme (Kredi Kartı)';
                $this->method_description = 'Ödemenizi kargo teslimatında kredi kartı ile yapın.';
                $this->has_fields         = false;

                // Ayarları yapılandırma
                $this->init_form_fields();
                $this->init_settings();

                // Ayar değerlerini alalım
                $this->title        = $this->get_option( 'title' );
                $this->description  = $this->get_option( 'description' );
                $this->enabled      = $this->get_option( 'enabled' );
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            }

            // Ayarları yapılandırma
            public function init_form_fields() {
                $this->form_fields = array(
                    'enabled' => array(
                        'title'   => 'Aktif',
                        'type'    => 'checkbox',
                        'label'   => 'Bu ödeme yöntemini etkinleştir.',
                        'default' => 'yes'
                    ),
                    'title' => array(
                        'title'       => 'Başlık',
                        'type'        => 'text',
                        'default'     => 'Kapıda Ödeme (Kredi Kartı)'
                    ),
                    'description' => array(
                        'title'       => 'Açıklama',
                        'type'        => 'textarea',
                        'default'     => 'Ödemenizi kargo teslimatında kredi kartı ile yapın.'
                    )
                );
            }

            // Ödeme seçeneğini gösterme
            public function payment_fields() {
                if ( $this->description ) {
                    echo wpautop( wp_kses_post( $this->description ) );
                }
            }

            // Siparişin ödendiği işlemi yapma
            public function process_payment( $order_id ) {
                $order = wc_get_order( $order_id );
                $order->update_status( 'processing', __( 'Sipariş ödendi. Hazırlanıyor durumuna geçildi.', 'example-payment-gateway' ) );
                return array(
                    'result'   => 'success',
                    'redirect' => $this->get_return_url( $order )
                );
            }
        }
    }

    add_filter('woocommerce_payment_gateways', 'gar_yik_add_gateway_class');
    function gar_yik_add_gateway_class($gateways)
    {
        $gateways[] = 'WC_GAR_YIK_Gateway';
        return $gateways;
    }

