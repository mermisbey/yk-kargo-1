<?php

    if (!defined('ABSPATH')) {
        exit;
    }

    $order_id = sanitize_text_field($_REQUEST['order_id']);

    if (empty($order_id)) {
        die("Sipariş id girilmedi.");
    }

    $order = wc_get_order($order_id);

    if ($order->get_customer_id() != get_current_user_id() && !current_user_can('administrator') && !current_user_can('shop_manager')) {
        die("Yetkiniz yok");
    }

    $invoice_key = get_post_meta($order_id, "invoice_key", true);


    if ($order->get_payment_method() == "cod") {
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

    $sonuc = $yurtici->queryShipment($invoice_key, 1, true, false);


    $son_durum = $sonuc->ShippingDeliveryVO->shippingDeliveryDetailVO->operationMessage;

    $teslimmi = $sonuc->ShippingDeliveryVO->shippingDeliveryDetailVO->errMessage;

    if ($teslimmi == "Silme işlemi başarıyla tamamlanmıştır.") {
        $son_durum = "Teslim Edildi";
    }


    if ($son_durum == "Kargo Teslimattadır.") {
        $son_durum = "Kargoya Verildi.";
    }


    $tum_hareketler = $sonuc->ShippingDeliveryVO->shippingDeliveryDetailVO->shippingDeliveryItemDetailVO->invDocCargoVOArray;

?>

<div class="ReactModal__Overlay ReactModal__Overlay--after-open solo-modal solo-modal--position-center solo-modal--size-medium">
    <div class="ReactModal__Content ReactModal__Content--after-open solo-modal__body animated fadeIn cargo-tracking-modal"
         tabindex="-1" role="dialog" aria-modal="true">
        <div class="solo-modal__header">
            <div class="solo-modal__header__swipeable-line" aria-label="Swipeable" role="button"></div>
            <h5 id="ezrjrcl09462pv1sxrmw1e" class="solo-modal__header__title">Kargo Detay</h5>

            <button type="button" tabindex="0" class="solo-modal__header__close-button" aria-label="Kapat"
                    id="kargo-detay-kapat">
                <svg width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true" focusable="false"
                     color="currentColor">
                    <defs>
                        <path id="horbm5quhaokfo32wwbo8p" d="M0 0h24v24H0z"></path>
                    </defs>
                    <g fill="none" fill-rule="evenodd">
                        <mask id="1cbu80llfmph74qkurf9jn" fill="#fff">
                            <use xlink:href="#horbm5quhaokfo32wwbo8p"></use>
                        </mask>
                        <g fill="currentColor" mask="url(#1cbu80llfmph74qkurf9jn)">
                            <path d="M14.045 12.158a.222.222 0 010-.315l5.567-5.566a1.334 1.334 0 00-1.888-1.886l-5.567 5.563a.222.222 0 01-.314 0L6.277 4.391A1.333 1.333 0 004.39 6.277l5.566 5.566a.222.222 0 010 .315L4.39 17.725a1.333 1.333 0 101.887 1.885l5.566-5.567a.222.222 0 01.314 0l5.567 5.567a1.333 1.333 0 101.886-1.885l-5.565-5.567z"></path>
                        </g>
                    </g>
                </svg>
            </button>

        </div>
        <div class="solo-modal__content" id="03w2zd8nnpp2jqho9pfm4k8">
            <div class="singular-cargo-tracking">
                <div class="singular-cargo-tracking__tracking-info">
                    <div class="singular-cargo-tracking__tracking-info__cargo-logo">
                        <img src="<?php echo yik_plugin_url ?>/etiket-sablonu/images/yurtici-logo.jpg" height="70"
                             alt="">
                    </div><?php
                        if (!empty(get_post_meta($order_id, "tracking_code", true))) {
                            ?>
                            <div class="singular-cargo-tracking__tracking-info__code">
                            <div class="tracking-number"><h5 class="tracking-number__title">Kargo Takip Numarası</h5>
                                <button type="button" class="tracking-number__code" aria-label="Takip numarası"><?php
                                        echo esc_html(get_post_meta($order_id, "tracking_code", true))
                                    ?>
                                </button>
                            </div>
                            </div><?php
                        }
                    ?>
                </div><?php
                    $order_items = $order->get_items();
                ?>
                <div class="singular-cargo-tracking__tracking-container">
                    <div class="singular-cargo-tracking__tracking">

                        <div class="singular-transactions">
                            <div class="singular-transactions__line">
                                <div class="road"></div>
                                <div class="truck truck--delivered"></div>
                            </div>
                            <div class="singular-transactions__transaction-list"><?php

                                    if (is_array($tum_hareketler)) {

                                        $hareket_titles = [];
                                        foreach ($tum_hareketler as $key => $hareket) {

                                            if ($key == 0) {
                                                $hareket_titles[] = "Kargoya Verildi";
                                            }
                                            if ($hareket->eventName == "Kargo Yüklendi" || $hareket->eventName == "Kargo İndirildi") {
                                                $hareket_titles[] = "Transfer sürecinde";
                                            }
                                            if ($hareket->eventName == "Teslim Edildi") {
                                                $hareket_titles[] = "Teslim Edildi";
                                            }

                                        }

                                        $hareket_titles = array_unique($hareket_titles);

                                        if ($son_durum == "Teslim Edildi") { ?>
                                            <div class="singular-transactions__transaction-line singular-transactions__transaction-line--active">
                                                <div class="singular-transactions__transaction-line__icon-container">
                                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                         class="singular-transactions__transaction-list__success-icon"
                                                         color="currentColor">
                                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                                              d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12C23.992 5.376 18.624.008 12 0zm6.927 8.2l-6.845 9.289c-.336.446-.97.535-1.416.199l-.014-.011-4.888-3.908a1 1 0 011.25-1.562l4.076 3.261 6.227-8.451a1 1 0 111.61 1.183z"
                                                              fill="currentColor"></path>
                                                    </svg>
                                                </div>
                                                <span>Teslim Edildi</span>
                                            </div>
                                            <?php

                                        }

                                        foreach ($hareket_titles as $titles) {

                                            ?>
                                            <div class="singular-transactions__transaction-line singular-transactions__transaction-line--active">
                                            <div class="singular-transactions__transaction-line__icon-container">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                     class="singular-transactions__transaction-list__success-icon"
                                                     color="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12C23.992 5.376 18.624.008 12 0zm6.927 8.2l-6.845 9.289c-.336.446-.97.535-1.416.199l-.014-.011-4.888-3.908a1 1 0 011.25-1.562l4.076 3.261 6.227-8.451a1 1 0 111.61 1.183z"
                                                          fill="currentColor"></path>
                                                </svg>
                                            </div>
                                            <span><?php
                                                    echo esc_html($titles);
                                                ?></span>
                                            </div><?php
                                        }
                                    } else { ?>


                                        <div class="singular-transactions__transaction-line singular-transactions__transaction-line--active">
                                            <div class="singular-transactions__transaction-line__icon-container">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                     class="singular-transactions__transaction-list__success-icon"
                                                     color="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                          d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12C23.992 5.376 18.624.008 12 0zm6.927 8.2l-6.845 9.289c-.336.446-.97.535-1.416.199l-.014-.011-4.888-3.908a1 1 0 011.25-1.562l4.076 3.261 6.227-8.451a1 1 0 111.61 1.183z"
                                                          fill="currentColor"></path>
                                                </svg>
                                            </div>
                                            <span>Kargo Barkodu Oluşturuldu</span>
                                        </div>

                                <?php

                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="singular-cargo-tracking__order-detail">
                        <div class="singular-cargo-tracking__products-container-desktop">
                            <div class="singular-cargo-tracking__products-images"><?php

                                    foreach ($order_items as $item_id => $item) {

                                        $product = $item->get_product(); ?>

                                        <img src="<?php echo get_the_post_thumbnail_url($product->get_id()) ?>">
                                        <?php
                                    }
                                ?>
                            </div>
                            <span class="singular-cargo-tracking__products-count">Paketinizde <br> <?php echo count($order_items) ?> ürün bulunmaktadır.</span>
                        </div>
                        <div class="singular-cargo-tracking__address-container">
                            <div class="singular-cargo-tracking__address">
                                <span class="singular-cargo-tracking__address-title">TESLİMAT ADRESİ</span>
                                <span class="singular-cargo-tracking__address-detail">

                                    <?php
                                        if (empty($order->get_shipping_address_1())) {
                                            echo esc_html($order->get_billing_address_1()) . " " . esc_html($order->get_billing_address_2());
                                        } else {
                                            echo esc_html($order->get_shipping_address_1()) . " " . esc_html($order->get_shipping_address_2());
                                        }
                                    ?></span>
                                <span class="singular-cargo-tracking__address-detail"><?php
                                        if (empty($order->get_shipping_city())) {
                                            echo esc_html($order->get_billing_city()) . "/" . WC()->countries->get_states()['TR'][esc_html($order->get_billing_state())];
                                        } else {
                                            echo esc_html($order->get_shipping_city()) . "/" . WC()->countries->get_states()['TR'][esc_html($order->get_shipping_state())];
                                        }
                                    ?></span>
                                <span class="singular-cargo-tracking__address-customer">

                                    <?php
                                        if (empty($order->get_shipping_first_name())) {
                                            echo esc_html($order->get_billing_first_name()) . " " . esc_html($order->get_billing_last_name());

                                        } else {
                                            echo esc_html($order->get_shipping_first_name()) . " " . esc_html($order->get_shipping_last_name());
                                        }

                                    ?> - <?php

                                        if (!empty($order->get_shipping_phone())) {
                                            echo esc_html($order->get_shipping_phone());
                                        } else {
                                            echo esc_html($order->get_billing_phone());
                                        }


                                    ?></span>
                            </div>
                        </div>
                        <div class="singular-cargo-tracking__address-container" style="margin-top: 20px">
                            <div class="singular-cargo-tracking__address">
                                <span class="singular-cargo-tracking__address-title">VARIŞ ŞUBESİ</span>
                                <span class="singular-cargo-tracking__address-detail">Yurtiçi Kargo</span>
                                <span class="singular-cargo-tracking__address-detail"><?php echo esc_html($sonuc->ShippingDeliveryVO->shippingDeliveryDetailVO->shippingDeliveryItemDetailVO->arrivalUnitName) ?></span>
                            </div>
                        </div>
                    </div>
                </div><?php
                    if ($son_durum != "Teslim Edildi" && is_array($tum_hareketler)) {

                        ?>
                        <div class="singular-cargo-tracking__detailed-transaction">

                        <h5 class="singular-cargo-tracking__detailed-transaction-title">Detaylı kargo
                            hareketleri</h5><?php

                        $tum_hareketler = array_reverse($tum_hareketler);
                        foreach ($tum_hareketler as $hareket) {
                            $tarih = $hareket->eventDate;
                            $saat = $hareket->eventTime;

                            $son_tarih = substr($tarih, 0, 4) . "-" . substr($tarih, 4, 2) . "-" . substr($tarih, 6, 2) . " " . substr($saat, 0, 2) . ":" . substr($saat, 2, 2) . ":" . substr($saat, 4, 2);


                            $son_tarih = date("d.m.Y H:i:s", strtotime($son_tarih));
                            ?>
                            <div class="singular-cargo-tracking__detailed-transaction__container">
                            <div class="detailed-delivery-transaction">
                                <div class="detailed-delivery-transaction__transaction-line">
                                    <div class="detailed-delivery-transaction__transaction-detail"><span
                                                class="detailed-delivery-transaction__transaction-description"><?php echo esc_html($hareket->eventName) ?></span><span
                                                class="detailed-delivery-transaction__transaction-date"><?php echo esc_html($son_tarih) ?></span>
                                    </div>
                                    <div class="detailed-delivery-transaction__transaction-unit-container"><span
                                                class="detailed-delivery-transaction__transaction-Unit"><?php echo esc_html($hareket->unitName) ?></span>
                                    </div>
                                </div>

                            </div>
                            </div><?php
                        }
                        ?>

                        </div><?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>
