<?php

    if (!defined('ABSPATH')) {
        exit;
    }


    $args = array(
        'post_type' => 'shop_order',
        'post_status' => array('wc-gar-yik-paket', 'wc-gar-yik-kargo'),
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'asc',
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $post_id = $query->post->ID;
            $post = get_post($post_id);
            $invoice_key = get_post_meta($post_id, "invoice_key", true);
            if (!empty($invoice_key)) {
                $order = new WC_Order($post_id);

                if($order->get_payment_method()=="cod"){
                    $kullaniciadi = get_option("gar_yik_api_kullanici_adi_2");
                    $sifre = get_option("gar_yik_api_kullanici_sifre_2");
                } else {
                    $kullaniciadi = get_option("gar_yik_api_kullanici_adi_1");
                    $sifre = get_option("gar_yik_api_kullanici_sifre_1");
                }

                $yurticiparams = [
                    "wsUserName" => $kullaniciadi,
                    "wsPassword" => $sifre,
                    "userLanguage" => "TR"
                ];

                $yurtici = new yurtici($yurticiparams);
                $result = $yurtici->queryShipment(get_post_meta($post->ID, "invoice_key", true), 1);
                $sonuc = $result->ShippingDeliveryVO->shippingDeliveryDetailVO->operationMessage;

                $kargo_takip_no = $result->ShippingDeliveryVO->shippingDeliveryDetailVO->shippingDeliveryItemDetailVO->trackingUrl;
                $teslim_durumu = $result->ShippingDeliveryVO->shippingDeliveryDetailVO->operationMessage;
                if($teslim_durumu=="Kargo teslim edilmiştir.") {
                    if (!empty($order)) {
                        $order->update_status('wc-gar-yik-teslim');
                    }
                } elseif (!empty($kargo_takip_no)) {


                    $kargo_takip_no = explode("?code=", $kargo_takip_no)[1];
                    update_post_meta($post_id, "tracking_code", $kargo_takip_no);
                    update_post_meta($post_id, "tracking_company", "yurtici");
                    $order = new WC_Order($post_id);
                    if (!empty($order)) {
                        $order->update_status('wc-gar-yik-kargo');
                    }
                }
            }
        }
    }

