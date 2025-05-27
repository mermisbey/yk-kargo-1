<?php
    if (!defined('ABSPATH')) {
        exit;
    }
    require_once "header.php";

    ini_set('display_errors', 'Off');
    ini_set('error_reporting', E_ALL);
    define('WP_DEBUG', false);
    define('WP_DEBUG_DISPLAY', false);
?>

    <div class="garsoft">
        <div id="yurtici-kargo-takibi"></div>
        <div id="yurticiLoading"
             class="yurticiLoading ReactModal__Overlay ReactModal__Overlay--after-open solo-modal solo-modal--position-center solo-modal--size-medium">
            <div class="loading">Yükleniyor</div>
        </div>

        <table class="navbarTable">
            <thead>
            <tr>
                <td>Durum</td>
                <td>Sipariş No</td>
                <td>Alıcı</td>
                <td>Adres</td>
                <td>Tarih</td>
                <td>Takip No</td>
                <td>İşlemler</td>
            </tr>
            </thead>

            <tbody>

            <?php

                $active_page = isset($_REQUEST['paged']) ? $_REQUEST['paged'] : 1;
                $per_paged = 25;


                $yurtici = new yurtici();

                $args = array(
                    'post_type' => 'shop_order',
                    'posts_per_page' => 3,
                    'paged' => $active_page,
                    'orderby' => 'date',
                    'order' => 'desc',
                    'meta_key' => 'tracking_company',
                    'meta_value' => 'yurtici'
                );
                $query = new WP_Query($args);

                $total = $query->found_posts;

                $total_page = ceil($total / $per_paged);


                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $post_id = $query->post->ID;
                        $post = get_post($post_id);
                        $order = wc_get_order($post_id);


                        $invoice_key = get_post_meta($post_id, "invoice_key", true);
                        if (!empty($invoice_key)) {
                            $order = new WC_Order($post_id);

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
                            $result = $yurtici->queryShipment(get_post_meta($post->ID, "invoice_key", true), 1, true, false);
                            $son_durum = $result->ShippingDeliveryVO->shippingDeliveryDetailVO->operationMessage;
                            $teslimmi = $result->ShippingDeliveryVO->shippingDeliveryDetailVO->errMessage;


                            if ($teslimmi == "Silme işlemi başarıyla tamamlanmıştır.") {
                                $son_durum = "Teslim Edildi";
                            }


                            if ($son_durum == "Kargo Teslimattadır.") {
                                $son_durum = "Kargoya Verildi";
                            }

                        }


                        ?>

                        <tr>
                            <td style="text-align: center">
                                <?php
                                    switch ($son_durum) {
                                        case "Kargo İşlem Görmemiş.":
                                            $icon = "fa fa-barcode";
                                            $text = "Barkod oluşturuldu.";
                                            break;
                                        case "Kargoya Verildi":
                                            $icon = "fa fa-truck";
                                            $text = "Kargo yolda";
                                            break;

                                        case "Teslim Edildi":
                                            $icon = "fa fa-check";
                                            $text = "Teslim Edildi";
                                            break;

                                        default:
                                            break;

                                    }
                                ?>
                                <i class="<?php echo $icon ?>" title="<?php echo $text ?>"></i>
                            </td>
                            <td style="text-align: center"><?php echo $post_id ?></td>
                            <td><?php echo $order->get_billing_first_name() . " " . $order->get_billing_last_name() ?></td>
                            <td><?php echo $order->get_billing_address_1() . " " . $order->get_billing_address_2() ?></td>
                            <td><?php echo wp_date('d F Y H:i:s', strtotime($order->get_date_modified())) ?></td>
                            <td><?php echo get_post_meta($order->get_id(), 'tracking_code', true) ?></td>
                            <td>

                                <a class="takip_kargo" title="Kargo Takibi"
                                   href="<?php echo admin_url('admin-ajax.php?action=kargoDetay&order_id=' . $post->ID) ?>">
                                    <i class="fas fa-truck"></i>

                                </a>

                            </td>
                        </tr>

                        <?php
                    }
                } ?>
            </tbody>

        </table>

        <?php
            if ($total_page > 1) {
                ?>
                <div class="pagination" style="margin-top:20px">
                <ul>
                    <li><a href="#"></a></li><?php
                        for ($i = 1; $i <= $total_page; $i++) {

                            if ($i == 1) {
                                $url = 'admin.php?page=gar_yik_yurtici_kargo&tab=garsoft-gonderi-listesi';
                            } else {
                                $url = 'admin.php?page=gar_yik_yurtici_kargo&tab=garsoft-gonderi-listesi&paged=' . $i;
                            }

                            if ($i == $active_page) {
                                $class = ' class="active"';
                            } else {
                                $class = "";
                            }

                            echo '<li' . $class . '><a href="' . $url . '"></a></li>';
                        }
                    ?>
                    <li><a href="#"></a></li>
                </ul>
                </div><?php
            }
        ?>

        <div class="alert alert-danger" role="alert" style="max-width: 1200px; margin-left: auto; margin-right: auto; margin-top: 20px">
            Gönderilerin tamamını görebilmek için eklentinin premium versiyonunu almanız gerekmektedir.
        </div>

    </div>

<?php
    require_once "footer.php";
