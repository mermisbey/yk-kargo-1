<?php
    /*
        Plugin Name: Garsoft Yurtiçi Kargo Entegrasyonu
        Plugin URI: https://wordpress.org/plugins/garsoft-yurtici-kargo-entegrasyonu/
        Description: Bu eklenti ile aldığınız siparişleri yurtiçi kargo sistemine otomatik olarak aktarabilirsiniz.
        Version: 3.4
        Author: garsoft
        Author URI: https://www.garsoft.com.tr/
        License: GNU
        */


    if (!defined('ABSPATH')) {
        exit;
    }
    require_once "class/class-yurtici.php";
    require_once "class/class-barcode.php";
   // include 'kapida-odeme-kredi-karti.php';

    define("yik_plugin_url", plugin_dir_url(__FILE__));
    define("yik_plugin_dir", plugin_dir_path(__FILE__));
    function kargoMenu()
    {
        add_menu_page(
            'Yurtiçi Kargo',
            'Yurtiçi Kargo',
            'manage_options',
            'gar_yik_yurtici_kargo',
            'garYikYurticiKargo',
            yik_plugin_url . 'assets/images/yk-icon.png',
            '1');
        add_menu_page(
            'Barkod Yazdır',
            'Barkod Yazdır',
            'manage_options',
            'gar-yik-barkod-yazdir',
            'yurticiBarkodOlustur',
            '',
            '1',);

        remove_menu_page("gar-yik-barkod-yazdir");

    }

    add_action('admin_menu', 'kargoMenu');



    function garYikYurticiKargo(){
        require_once "admin/pages.php";
    }




    function garYikYurticiKargoAddMetabox()
    {
        add_meta_box(
            'yurtici_kargo',
            'Yurtiçi Kargo',
            'yurticiKargoMetaBox',
            'shop_order',
            'side',
            'high'
        );
    }

    add_action('add_meta_boxes', 'garYikYurticiKargoAddMetabox');


    function yurticiKargoMetaBox()
    {
        global $post;
        ?>

        <div id="yurtici-kargo-takibi"></div>
        <div id="yurticiLoading"
             class="yurticiLoading ReactModal__Overlay ReactModal__Overlay--after-open solo-modal solo-modal--position-center solo-modal--size-medium">
            <div class="loading">Yükleniyor</div>
        </div>

        <div class="yurtici-logo">
            <img src="<?php echo yik_plugin_url ?>/assets/images/yurtici-kargo-wp-admin-logo.jpg">
        </div>
        <div class="butonlar">
            <a id="barkod_cikti" target="_blank" type="submit" class="printa" title="Barkod Çıktı Al"
               href="<?php echo esc_html(admin_url("/admin.php?page=gar-yik-barkod-yazdir&order_id=" . $post->ID)) ?>">
                <span class="dashicons dashicons-printer fs-40"></span>
            </a><?php
                if (empty(get_post_meta($post->ID, "kargo_key", true))) {
                    $class = ' gizle';
                } else {
                    $class = "";
                }
            ?>
            <a id="takip_kargo" class="printa<?php echo $class ?>" title="Kargo Takibi"
               href="<?php echo admin_url('admin-ajax.php?action=kargoDetay&order_id=' . $post->ID) ?>">
                <span class="dashicons dashicons-location fs-40"></span>
            </a>
        </div>


        <script>
            jQuery('#takip_kargo').click(function (e) {
                jQuery("#yurticiLoading").toggleClass("yurticiLoading");
                e.preventDefault();
                jQuery.ajax({
                    url: '' + jQuery(this).attr('href') + '',
                    type: 'POST',
                    data: {},
                    success: function (data) {
                        jQuery("#yurticiLoading").toggleClass("yurticiLoading");
                        jQuery("#yurtici-kargo-takibi").html(data);
                        jQuery("#kargo-detay-kapat").click(function (e) {
                            jQuery("#yurtici-kargo-takibi").html("");
                        });

                    },
                });

            })

            jQuery('#barkod_cikti').click(function (e) {
                jQuery("#takip_kargo").removeClass('gizle');
            })
        </script>

        <?php


    }


    add_action('wp_ajax_barkod_generator', 'barkod_generator');
    add_action('wp_ajax_nopriv_barkod_generator', 'barkod_generator');

    function barkod_generator()
    {
        require_once "barkod-generator.php";
        wp_die();
    }


    add_action('wp_ajax_barkod_guncelle', 'barkodGuncelle');
    add_action('wp_ajax_nopriv_barkod_guncelle', 'barkodGuncelle');

    function barkodGuncelle()
    {
        require_once "barkod-guncelle.php";
        wp_die();
    }


    add_filter('woocommerce_my_account_my_orders_actions', 'add_kargo_button_in_order', 10, 2);
    function add_kargo_button_in_order($actions, $order)
    {
        $order_id = $order->get_id();
        $tracking_code = get_post_meta($order->get_id(), 'tracking_code', true);
        $action_slug = 'kargo-durumu';

        if (!empty($tracking_code)) {
            $actions[$action_slug] = array(
                'url' => $order_id,
                'name' => 'Kargo Hareketleri',
                'id' => "test"
            );

            return $actions;
        } else {
            return $actions;
        }
    }


    add_action('woocommerce_after_account_orders', 'action_after_account_orders_js');
    function action_after_account_orders_js()
    {
        $action_slug = 'kargo-durumu';
        ?>
        <div id="yurtici-kargo-takibi"></div>
        <div id="yurticiLoading"
             class="yurticiLoading ReactModal__Overlay ReactModal__Overlay--after-open solo-modal solo-modal--position-center solo-modal--size-medium">
            <div class="loading">Yükleniyor</div>
        </div>
        <script>
            jQuery(function ($) {
                $('a.<?php echo esc_html($action_slug); ?>').each(function () {
                    $(this).attr('target', '_blank');
                });

                $('a.<?php echo esc_html($action_slug); ?>').click(function (e) {
                    e.preventDefault();
                    $("#yurticiLoading").toggleClass("yurticiLoading");
                    var order_id = $(this).attr("href").replace("http://", "");
                    $.ajax({
                        url: '<?php echo admin_url("admin-ajax.php") ?>',
                        type: 'POST',
                        data: {
                            "action": "kargoDetay",
                            "order_id": order_id,

                        },
                        success: function (data) {
                            $("#yurticiLoading").toggleClass("yurticiLoading");
                            $("#yurtici-kargo-takibi").html(data);
                            $("#kargo-detay-kapat").click(function (e) {
                                $("#yurtici-kargo-takibi").html("");
                            });
                        },
                    });
                });

            });
        </script>
        <?php
    }

    add_action('wp_ajax_kargoDetay', 'kargoDetay');
    add_action('wp_ajax_nopriv_kargoDetay', 'kargoDetay');

    function kargoDetay()
    {
        require_once "kargo-detay.php";
        wp_die();
    }


    add_action('wp_ajax_yurtici_barkod_olustur', 'yurticiBarkodOlustur');
    add_action('wp_ajax_nopriv_yurtici_barkod_olustur', 'yurticiBarkodOlustur');
    function yurticiBarkodOlustur()
    {
        if (!empty(sanitize_text_field($_REQUEST['order_id']))) {
            $_SESSION['barkodlar'] = [sanitize_text_field($_REQUEST['order_id'])];
        }

        if (!empty(get_option("gar_yik_barkod_ebat"))) {
            require "etiket-sablonu/print.php";
        }

        wp_die();
    }

    add_filter('bulk_actions-edit-shop_order', function ($bulk_actions) {
        $bulk_actions['toplu-barkod'] = "Toplu Kargo Barkodu Oluştur";
        return $bulk_actions;
    });

    add_action('handle_bulk_actions-edit-shop_order', 'wpse29822_page_bulk_actions_handle', 10, 3);
    function wpse29822_page_bulk_actions_handle($redirect_to, $doaction, $post_ids)
    {
        if ($doaction == 'toplu-barkod') {

            $barkodlar = "";
            foreach ($_REQUEST['post'] as $order_id) {
                $barkodlar .= sanitize_text_field($order_id) . ",";
            }
            $barkodlar = rtrim($barkodlar, ",");

            if (!empty(get_option("gar_yik_barkod_ebat"))) {

                return admin_url("edit.php?post_type=shop_order&yonlendir=printBarkod&barkodlar=$barkodlar");

            }
        }
        return $redirect_to;
    }


    function garYikAddFrontCss()
    {
        global $wp;

        if (is_account_page()) {
            wp_enqueue_style('yurtici-css', plugins_url('assets/css/yurtici.css', __FILE__), array(), '1.1.2');
        }


    }

    add_action('wp_enqueue_scripts', 'garYikAddFrontCss');


    function garYikAddAdminCss($hook)
    {
        wp_enqueue_style('yurtici-css', plugins_url('assets/css/admin.css', __FILE__), array(), '1.2.5');
        wp_enqueue_style('yurtici-front-css', plugins_url('assets/css/yurtici.css', __FILE__), array(), '1.2.3');

        if (isset($_REQUEST['page'])) {
            if (!empty(get_option("gar_yik_barkod_ebat") && $_REQUEST['page'] == "gar-yik-barkod-yazdir")) {
                wp_enqueue_style("gar-yik-etiket", plugin_dir_url(__FILE__) . 'etiket-sablonu/css/' . get_option("gar_yik_barkod_ebat") . '.css', array(), '1.1.4');
                wp_enqueue_style("gar-yik-admin-hide", plugin_dir_url(__FILE__) . 'etiket-sablonu/css/admin-hide.css');
            }
        }
    }

    add_action('admin_enqueue_scripts', 'garYikAddAdminCss');


/**
 * WooCommerce ödeme ağ geçitlerini ve ilgili dosyaları yükler.
 * Bu fonksiyon, WooCommerce sınıflarının yüklendiğinden emin olmak için
 * 'plugins_loaded' hook'una bağlanır.
 */
function gar_yik_load_payment_gateway_files() {
    // Önce WooCommerce'in aktif olup olmadığını kontrol et
    if ( ! class_exists( 'WooCommerce' ) ) {
        // WooCommerce aktif değilse bir uyarı göster (isteğe bağlı ama önerilir)
        add_action( 'admin_notices', 'gar_yik_woocommerce_missing_notice' );
        return; // WooCommerce yoksa devam etme
    }

    // WooCommerce aktifse ve WC_Payment_Gateway sınıfı tanımlıysa dosyayı dahil et.
    // kapida-odeme-kredi-karti.php dosyası kendi içinde gerekli hook'ları
    // (plugins_loaded, woocommerce_payment_gateways) zaten barındırıyor.
    // Bizim burada sadece dosyayı doğru zamanda dahil etmemiz yeterli.
    if ( class_exists( 'WC_Payment_Gateway' ) ) {
        $gateway_file_path = plugin_dir_path( __FILE__ ) . 'kapida-odeme-kredi-karti.php';
        if ( file_exists( $gateway_file_path ) ) {
            require_once $gateway_file_path;
        } else {
            // Opsiyonel: Dosya bulunamazsa loglama veya admin uyarısı
            error_log( 'Garsoft Yurtiçi Kargo: kapida-odeme-kredi-karti.php dosyası bulunamadı.' );
            add_action( 'admin_notices', function() {
                echo '<div class="error"><p>' . esc_html__( 'Garsoft Yurtiçi Kargo: Kapıda ödeme ağ geçidi dosyası bulunamadı. Lütfen eklenti dosyalarını kontrol edin.', 'garsoft-yurtici-kargo-entegrasyonu' ) . '</p></div>';
            });
        }
    } else {
        // Bu durum nadir olmalı, WC aktifse WC_Payment_Gateway de olmalı.
        // Ama yine de bir kontrol ve uyarı eklenebilir.
        add_action( 'admin_notices', function() {
            echo '<div class="error"><p>' . esc_html__( 'Garsoft Yurtiçi Kargo: WooCommerce ödeme altyapısı tam yüklenemedi. Lütfen WooCommerce kurulumunuzu kontrol edin.', 'garsoft-yurtici-kargo-entegrasyonu' ) . '</p></div>';
        });
    }
}
add_action( 'plugins_loaded', 'gar_yik_load_payment_gateway_files', 20 ); // 20 gibi bir öncelik vererek WC'den sonra çalışmasını garantilemeye çalışalım.

/**
 * WooCommerce'in eksik olduğuna dair admin uyarısı gösterir.
 */
function gar_yik_woocommerce_missing_notice() {
    ?>
    <div class="error"><p><?php
        echo sprintf(
            /* translators: %s: WooCommerce Plugin URL */
            esc_html__( 'Garsoft Yurtiçi Kargo eklentisinin sorunsuz çalışması için %s eklentisinin kurulu ve aktif olması gerekmektedir.', 'garsoft-yurtici-kargo-entegrasyonu' ),
            '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank" rel="noopener noreferrer">WooCommerce</a>'
        );
    ?></p></div>
    <?php
}


    register_activation_hook(__FILE__, 'garYikAktivate');
    function garYikAktivate()
    {
        update_option("gar_yik_barkod_ebat", "a4");
        update_option("gar_yik_kapida_odeme", "0");
        $kapida_odeme_nakit = get_option('woocommerce_cod_settings');
        $kapida_odeme_kredi_karti = get_option('woocommerce_gar_yik_kapida_odeme_kredi_karti_settings');

        $kapida_odeme_nakit['title'] = "Kapıda Ödeme (Nakit)";
        $kapida_odeme_nakit['enabled'] = "yes";
        $kapida_odeme_kredi_karti['enabled'] = "yes";

        update_option('woocommerce_cod_settings', $kapida_odeme_nakit);
        update_option('woocommerce_gar_yik_kapida_odeme_kredi_karti_settings', $kapida_odeme_kredi_karti);

    }


    add_action('init', 'garYikRegisterCustomOrderStatus');

    function garYikRegisterCustomOrderStatus()
    {
        register_post_status('wc-gar-yik-paket', array(
            'label' => 'Paketleniyor',
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Paketleniyor <span class="count">(%s)</span>', 'Paketleniyor <span class="count">(%s)</span>')
        ));

        register_post_status('wc-gar-yik-kargo', array(
            'label' => 'Kargoda',
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Kargoya Verildi <span class="count">(%s)</span>', 'Kargoya Verildi <span class="count">(%s)</span>')
        ));

        register_post_status('wc-gar-yik-teslim', array(
            'label' => 'Teslim Edildi',
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('Teslim Edildi <span class="count">(%s)</span>', 'Teslim Edildi <span class="count">(%s)</span>')
        ));

    }


    function garYikCustomOrderStatus($order_statuses)
    {
        $new_order_statuses = array();
        foreach ($order_statuses as $key => $status) {
            $new_order_statuses[$key] = $status;
            if ('wc-processing' === $key) {
                $new_order_statuses['wc-gar-yik-paket'] = 'Paketleniyor';
                $new_order_statuses['wc-gar-yik-kargo'] = 'Kargoda';
                $new_order_statuses['wc-gar-yik-teslim'] = 'Teslim Edildi';
            }
        }
        return $new_order_statuses;
    }

    add_filter('wc_order_statuses', 'garYikCustomOrderStatus');


    add_action('admin_footer', 'my_inline_script');

    function my_inline_script()
    {
        if (isset($_REQUEST['yonlendir'])) {

            if (!empty(sanitize_text_field($_REQUEST['yonlendir']))) {

                ?>
                <script>
                    jQuery(document).ready(function ($) {
                        window.open("<?php echo admin_url("admin.php?page=gar-yik-barkod-yazdir&order_id=" . $_REQUEST['barkodlar']) ?>", "_blank");
                    });
                </script>
                <?php
            }
        }
    }


    add_action('admin_notices', 'display_custom_error_message');

    function display_custom_error_message()
    {

        if (!isset($_REQUEST['page']) || $_REQUEST['page'] != "gar_yik_yurtici_kargo") {
            if (empty(get_option("gar_yik_api_kullanici_adi_1"))) {
                echo '<div class="error"><p>Garsoft Yurtiçi Kargo api ayarlarını yapmak için <a href="' . admin_url('admin.php?page=gar_yik_yurtici_kargo') . '">tıklayın</a>. </p></div>';
            }
        }
    }


    function gar_yik_plugin_actions($links, $file)
    {
        if ($file == 'garsoft-yurtici-kargo-entegrasyonu/index.php' && function_exists('admin_url')) {
            $settings_link = '<a href="' . admin_url('admin.php?page=gar_yik_yurtici_kargo') . '">Ayarlar</a>';
            array_unshift($links, $settings_link); // before other links
        }
        return $links;
    }

    add_filter('plugin_action_links', 'gar_yik_plugin_actions', 10, 2);


//    add_filter('woocommerce_email_actions', 'gar_yik_custom_email_actions', 20, 1);
//    function gar_yik_custom_email_actions($action)
//    {
//        $actions[] = 'woocommerce_order_status_wc-gar-yik-paket';
//        $actions[] = 'woocommerce_order_status_wc-gar-yik-kargo';
//        $actions[] = 'woocommerce_order_status_gar-yik-teslim';
//        return $actions;
//    }
//
//
//    function gar_yik_custom_add_woocommerce_email($email_classes)
//    {
//        require_once('class/class-woocommerce-email.php');
//        $email_classes['WC_Paketleniyor_Email'] = new WC_Paketleniyor_Email();
//        $email_classes['WC_Kargoda_Email'] = new WC_Kargoda_Email();
//        $email_classes['WC_Teslim_Edildi_Email'] = new WC_Teslim_Edildi_Email();
//        return $email_classes;
//    }
//
//    add_filter('woocommerce_email_classes', 'gar_yik_custom_add_woocommerce_email');




    if(!class_exists('GarsoftMng')){
        function gar_yik_custom_shop_order_column($columns)
        {
            $new_columns = [];
            foreach ($columns as $key => $column){
                $new_columns[$key] = $column;

                if($key=="order_status"){
                    $new_columns['kargo'] = 'Kargo';
                }
            }

            return $new_columns;
        }

        add_filter('manage_edit-shop_order_columns', 'gar_yik_custom_shop_order_column');

        function gar_yik_custom_shop_order_column_content($column)
        {
            global $post;
            if ('kargo' === $column) {
                // Sipariş ID'sini alın
                $order_id = $post->ID;

                switch (get_post_meta($order_id, 'tracking_company', true)) {
                    case 'yurtici';
                        echo '<img src="'.yik_plugin_url.'assets/images/yk-icon-beyaz.png" width="50">';
                        break;

                    default:
                        break;
                }

            }
        }

        add_action('manage_shop_order_posts_custom_column', 'gar_yik_custom_shop_order_column_content');
    }


