<?php


    if (!defined('ABSPATH')) {
        exit;
    }

    if (!current_user_can('administrator') && !current_user_can('shop_manager')) {
        die("yetkisiz giriş");
    }


    echo '<div class="page">';

    if (!extension_loaded('soap')) {
        echo '<div class="alert alert-danger" role="alert">SOAP MODÜLÜ AKTİF DEĞİL. Hosting Firmanızla iletişime geçin.</div>';
        exit;
    }

    $barkodlar = explode(",", sanitize_text_field($_REQUEST['order_id']));


    foreach ($barkodlar as $key => $order_id) {
        global $woocommerce;

        $order = wc_get_order($order_id);

        update_post_meta($order_id, "tracking_company", "yurtici");


        $order_items = $order->get_items();
        $items = [];
        foreach ($order_items as $item_id => $item) {

            $product = $item->get_product();
            $items[] = [
                'name' => $item->get_name(),
                'quantity' => $item->get_quantity(),
                'price' => $product->get_price(),
            ];
        }

        if (!empty($order->get_shipping_first_name())) {
            $shipping_info = [
                'company' => $order->get_shipping_company(),
                'first_name' => $order->get_shipping_first_name(),
                'last_name' => $order->get_shipping_last_name(),
                'phone' => $order->get_shipping_phone(),
                'address_1' => $order->get_shipping_address_1(),
                'address_2' => $order->get_shipping_address_2(),
                'postcode' => $order->get_shipping_postcode(),
                'city' => $order->get_shipping_city(),
                'state' => WC()->countries->get_states()[$order->get_shipping_country()][$order->get_shipping_state()],
                'country' => WC()->countries->get_countries()[$order->get_shipping_country()],
            ];
        } else {
            $shipping_info = [
                'company' => $order->get_billing_company(),
                'first_name' => $order->get_billing_first_name(),
                'last_name' => $order->get_billing_last_name(),
                'email' => $order->get_billing_email(),
                'phone' => $order->get_billing_phone(),
                'address_1' => $order->get_billing_address_1(),
                'address_2' => $order->get_billing_address_2(),
                'postcode' => $order->get_billing_postcode(),
                'city' => $order->get_billing_city(),
                'state' => WC()->countries->get_states()[$order->get_billing_country()][$order->get_billing_state()],
                'country' => WC()->countries->get_countries()[$order->get_billing_country()],
            ];
        }

        $order_info = [
            'billing' => [
                'company' => $order->get_billing_company(),
                'first_name' => $order->get_billing_first_name(),
                'last_name' => $order->get_billing_last_name(),
                'email' => $order->get_billing_email(),
                'phone' => $order->get_billing_phone(),
                'address_1' => $order->get_billing_address_1(),
                'address_2' => $order->get_billing_address_2(),
                'postcode' => $order->get_billing_postcode(),
                'city' => $order->get_billing_city(),
                'state' => WC()->countries->get_states()[$order->get_billing_country()][$order->get_billing_state()],
                'country' => WC()->countries->get_countries()[$order->get_billing_country()],
            ],
            'shipping' => $shipping_info,
            'items' => $items,
            'order' => [
                'total' => $order->get_total()
            ]

        ];

        $order_length = strlen($order_id);
        $zero_count = 10 - $order_length;

        $zero = "";
        for ($i = 1; $i <= $zero_count; $i++) {
            $zero .= 0;
        }

        $cargokey = "999" . $zero . $order_id;


        if (empty(get_post_meta($order_id, "kargo_key", true))) {

            $invoice_key = substr(md5($order_info['shipping']['first_name']), 0, 10) . "-" . $order_id;

            if (empty($order_info['shipping']['phone'])) {
                $shipping_phone = $order_info['billing']['phone'];
            } else {
                $shipping_phone = $order_info['shipping']['phone'];
            }


            if ($order->get_payment_method() == "cod" || $order->get_payment_method() == "gar_yik_kapida_odeme_kredi_karti") {
                $kullaniciadi = get_option("gar_yik_api_kullanici_adi_2");
                $sifre = get_option("gar_yik_api_kullanici_sifre_2");

                if ($order->get_payment_method() == "cod") {
                    $ttCollectionType = 0;
                } else {
                    $ttCollectionType = 1;
                }
                $params = array(
                    'cargoKey' => $cargokey,
                    'invoiceKey' => $invoice_key,
                    'receiverCustName' => $order_info['shipping']['first_name'] . " " . $order_info['shipping']['last_name'],
                    'receiverAddress' => $order_info['shipping']['address_1'] . " " . $order_info['shipping']['address_2'],
                    'cityName' => $order_info['shipping']['state'],
                    'townName' => $order_info['shipping']['city'],
                    'receiverPhone1' => $shipping_phone,
                    'receiverPhone2' => "",
                    'receiverPhone3' => "",
                    'emailAddress' => $order_info['billing']['email'],
                    'taxOfficeId' => '',
                    'taxNumber' => "",
                    'taxOfficeName' => "",
                    'desi' => "",
                    'kg' => "",
                    'cargoCount' => '',
                    'waybillNo' => "",
                    'specialField1' => "",
                    'specialField2' => "",
                    'specialField3' => "",
                    'ttInvoiceAmount' => number_format($order->get_total(), "2", ".", ""),
                    'ttDocumentId' => $zero . $order_id,
                    'ttCollectionType' => $ttCollectionType,
                    'ttDocumentSaveType' => 1,
                    'dcSelectedCredit' => "",
                    'dcCreditRule' => '',
                    'description' => "",
                    'orgGeoCode' => "",
                    'privilegeOrder' => "",
                    'custProdId' => "",
                    'orgReceiverCustId' => "",
                );

            } else {

                /*
                 * Normal Gönderi
                 */
                $kullaniciadi = get_option("gar_yik_api_kullanici_adi_1");
                $sifre = get_option("gar_yik_api_kullanici_sifre_1");
                $params = array(
                    'cargoKey' => $cargokey,
                    'invoiceKey' => $invoice_key,
                    'receiverCustName' => $order_info['shipping']['first_name'] . " " . $order_info['shipping']['last_name'],
                    'receiverAddress' => $order_info['shipping']['address_1'] . " " . $order_info['shipping']['address_2'],
                    'cityName' => $order_info['shipping']['state'],
                    'townName' => $order_info['shipping']['city'],
                    'receiverPhone1' => $shipping_phone,
                    'receiverPhone2' => "",
                    'receiverPhone3' => "",
                    'emailAddress' => $order_info['billing']['email'],
                    'taxOfficeId' => '',
                    'taxNumber' => "",
                    'taxOfficeName' => "",
                    'desi' => "",
                    'kg' => "",
                    'cargoCount' => '',
                    'waybillNo' => "",
                    'specialField1' => "",
                    'specialField2' => "",
                    'specialField3' => "",
                    'ttInvoiceAmount' => "",
                    'ttDocumentId' => '',
                    'ttCollectionType' => "",
                    'ttDocumentSaveType' => "",
                    'dcSelectedCredit' => "",
                    'dcCreditRule' => '',
                    'description' => "",
                    'orgGeoCode' => "",
                    'privilegeOrder' => "",
                    'custProdId' => "",
                    'orgReceiverCustId' => "",
                );
            }

            $yurticiparams = ["wsUserName" => $kullaniciadi, "wsPassword" => $sifre, "userLanguage" => "TR"];

            $yurtici = new yurtici($yurticiparams);

            try {
                $result = $yurtici->createShipment($params);
            } catch (Exception $e) {
                $error = $e->getMessage();


                echo '<div class="alert alert-danger" role="alert">' . $e->getMessage() . '</div>';
                exit;
            }

            if (isset($result->ShippingOrderResultVO->errCode)) {
                echo '<div class="alert alert-danger" role="alert">' . $result->ShippingOrderResultVO->outResult . '</div>';
                exit;
            }

            $kargo_key = $result->ShippingOrderResultVO->shippingOrderDetailVO->cargoKey;
            $invoiceKey = $result->ShippingOrderResultVO->shippingOrderDetailVO->invoiceKey;


            if (!empty($kargo_key)) {
                if (!empty($order)) {
                    $order->update_status('wc-gar-yik-paket');
                }

                update_post_meta($order_id, "kargo_key", $kargo_key);
                update_post_meta($order_id, "invoice_key", $invoiceKey);

            }

        } else {
            $kargo_key = get_post_meta($order_id, "kargo_key", true);
        }

        if (!empty($kargo_key)) {
            if ($key % 3 == "" && $key != 0) {
                $class = "break";
            } else {
                $class = "";
            }

            ?>

            <div class="label-info <?php echo esc_html($class) ?>">
                <div class="info-header">
                    <div class="shipment-address">
                        <div class="inner">

                            <p class="header"> Alıcı Bilgileri </p>
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td width="100">Sipariş No</td>
                                    <td><?php echo esc_html($order_id) ?></td>
                                </tr>
                                <tr>
                                    <td>Ad - Soyad</td>
                                    <td><?php echo esc_html($order_info['shipping']['first_name']) . " " . esc_html($order_info['shipping']['last_name']) ?></td>
                                </tr>
                                <tr>
                                    <td>Telefon</td>
                                    <td><?php
                                            if (!empty(esc_html($order_info['shipping']['phone']))) {
                                                echo esc_html($order_info['shipping']['phone']);
                                            } else {
                                                echo esc_html($order_info['billing']['phone']);
                                            }
                                        ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Adres</td>
                                    <td><?php echo esc_html($order_info['shipping']['address_1']) . " " . esc_html($order_info['shipping']['address_2']) ?></td>
                                </tr>

                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold"> <?php echo esc_html($order_info['shipping']['city']) . "/" . esc_html($order_info['shipping']['state']) ?></td>
                                </tr>
                                </tbody>
                            </table><!----></div>
                    </div>
                    <div class="shipment-barcode">
                        <div class="inner barkod">

                            <div class="app-barcode">
                                <img class="kargo-logo" width="220"
                                     src="<?php echo yik_plugin_url ?>assets/images/yurtici-kargo-logo.png">
                                <img class="barkod-resim"
                                     src="<?php echo admin_url("admin-ajax.php?action=barkod_generator&kargo_key=" . esc_html($kargo_key)) ?>">
                                <span><?php echo esc_html($kargo_key); ?></span>

                            </div><!----></div>
                    </div>
                </div>

            </div>
            <?php
        }

    }
    echo '</div>';