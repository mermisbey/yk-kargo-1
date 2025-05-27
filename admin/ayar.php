<?php
    if (!defined('ABSPATH')) {
        exit;
    }
    require_once "header.php";

    if (isset($_REQUEST['submit'])) {
        update_option("gar_yik_api_kullanici_adi_1", sanitize_text_field($_REQUEST['yurtici_api_kullanici_adi_1']));
        update_option("gar_yik_api_kullanici_adi_2", sanitize_text_field($_REQUEST['yurtici_api_kullanici_adi_2']));
        update_option("gar_yik_api_kullanici_sifre_1", sanitize_text_field($_REQUEST['yurtici_api_kullanici_sifre_1']));
        update_option("gar_yik_api_kullanici_sifre_2", sanitize_text_field($_REQUEST['yurtici_api_kullanici_sifre_2']));
        update_option("gar_yik_barkod_ebat", sanitize_text_field($_REQUEST['yurtici_barkod_ebat']));
        update_option("gar_yik_kapida_odeme", sanitize_text_field($_REQUEST['gar_yik_kapida_odeme']));


        $kapida_odeme_nakit = get_option('woocommerce_cod_settings');
        $kapida_odeme_kredi_karti = get_option('woocommerce_gar_yik_kapida_odeme_kredi_karti_settings');


        if ($_REQUEST['gar_yik_kapida_odeme'] == 0) {
            $kapida_odeme_nakit['enabled'] = "no";
            $kapida_odeme_kredi_karti['enabled'] = "no";
        } else {
            $kapida_odeme_nakit['enabled'] = "yes";
            $kapida_odeme_kredi_karti['enabled'] = "yes";
        }

        update_option('woocommerce_cod_settings', $kapida_odeme_nakit);
        update_option('woocommerce_gar_yik_kapida_odeme_kredi_karti_settings', $kapida_odeme_kredi_karti);


    }
?>
<div class="wrap">

    <form method="post" action="" novalidate="novalidate" style="margin-top: 30px" class="ayarForm">
        <h1 class="wp-heading-inline" style="margin-bottom: 20px; text-align: center; width: 100%">Yurtiçi Kargo
            Webservis Ayarları</h1>

        <legend><span class="number">1</span> GÖ (Normal)</legend>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row"><label for="yurtici_api_kullanici_adi_1">Api Kullanıcı Adı</label></th>
                <td>
                    <input name="yurtici_api_kullanici_adi_1" type="text" id="blogname"
                           value="<?php echo esc_html(get_option("gar_yik_api_kullanici_adi_1")) ?>"
                           class="regular-text">
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="yurtici_api_kullanici_sifre_1">Api Kullanıcı Şifre</label></th>
                <td>
                    <input name="yurtici_api_kullanici_sifre_1" type="password" id="blogname"
                           value="<?php echo esc_html(get_option("gar_yik_api_kullanici_sifre_1")) ?>"
                           class="regular-text">
                </td>
            </tr>


            <tr>
                <th scope="row"><label for="gar_yik_kapida_odeme">Kapıda Ödeme</label></th>
                <td>


                    <button id="kapida_odeme" type="button"
                            class="btn btn-toggle <?php if (esc_html(get_option('gar_yik_kapida_odeme')) == 1) {
                                echo 'active';
                            } ?>" data-toggle="button"
                            aria-pressed="false" autocomplete="off">
                        <div class="handle"></div>
                    </button>


                    <input type="hidden" name="gar_yik_kapida_odeme" id=""
                           value="<?php echo esc_html(get_option("gar_yik_kapida_odeme")) ?>">

                </td>
            </tr>


            </tbody>
        </table>


        <?php
            if (esc_html(get_option('gar_yik_kapida_odeme')) == 0) {
                $class = " gizle";
            }
        ?>
        <div id="tahsilatli" class="<?php echo $class ?>">
            <legend style="margin-top: 20px"><span class="number">2</span> GÖ (Tahsilatlı Teslimat)</legend>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row"><label for="yurtici_api_kullanici_adi_2">Api Kullanıcı Adı</label></th>
                    <td>
                        <input name="yurtici_api_kullanici_adi_2" type="text" id="blogname"
                               value="<?php echo esc_html(get_option("gar_yik_api_kullanici_adi_2")) ?>"
                               class="regular-text">
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="yurtici_api_kullanici_sifre_2">Api Kullanıcı Şifre</label></th>
                    <td>
                        <input name="yurtici_api_kullanici_sifre_2" type="password" id="blogname"
                               value="<?php echo esc_html(get_option("gar_yik_api_kullanici_sifre_2")) ?>"
                               class="regular-text">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row"><label for="yurtici_barkod_ebat">Kargo Çıktı Etiketi</label></th>
                <td>
                    <select name="yurtici_barkod_ebat" id="" class="form-control">
                        <option value="a4" <?php if (esc_html(get_option("gar_yik_barkod_ebat")) == "a4") {
                            echo "selected";
                        } ?>>A4 (210 x 297 mm)
                        </option>
                        <option value="a5" <?php if (esc_html(get_option("gar_yik_barkod_ebat")) == "a5") {
                            echo "selected";
                        } ?>>A5 (148 x 210 mm)
                        </option>
                        <option value="a6" <?php if (esc_html(get_option("gar_yik_barkod_ebat")) == "a6") {
                            echo "selected";
                        } ?>>A6 (105 x 148 mm)
                        </option>
                        <option value="a7" <?php if (esc_html(get_option("gar_yik_barkod_ebat")) == "a7") {
                            echo "selected";
                        } ?>>A7 (74 x 105 mm)
                        </option>
                    </select>
                </td>
            </tr>

            </tbody>
        </table>


        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button2 button-primary" value="Değişiklikleri kaydet">
        </p>


        <table width="100%">
            <tr>
                <td>
                    <div class="iletisim">
                        <p>
                            <strong>
                                Garsoft Yurtiçi Kargo Eklentisi ile alakalı her türlü soru, şikayet ve önerileriniz için
                                yazabilirsiniz.
                            </strong>
                        </p>

                        <p>Yazılan tüm mesajlara en geç 2 saat içerisinde dönüş sağlıyorum.</p>

                        <p>
                            <a href="https://api.whatsapp.com/send?phone=905458414139" target="_blank"><img
                                        src="https://cdn.r10.net/editor/24410/1997859032.jpg"></a>
                        </p>


                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="eklenti-bilgi">
                        <h2>Önemli:</h2>

                        <code>curl <?php echo admin_url("admin-ajax.php?action=barkod_guncelle") ?> > /dev/null
                            2>&1</code>

                        <p>Oluşturduğunuz barkod, yurtiçi şubesi tarafından okutulduğunda siparişinizin otomatik olarak
                            kargoya verildi
                            olarak işaretlenebilmesi için yukarıdaki adresi
                            cronjob olarak eklemeniz gerekmektedir. </p>
                        <p>
                            Cronjob saatini kargolarınız şubenizde saat kaçta okutuluyorsa o saate ayarlayın.
                        </p>

                        <p>
                            Cronjob nasıl oluşturulur öğrenmek için <a
                                    href="https://blog.omurtech.com/cron-job-nedir-cpanel-cron-job-olusturma-24"
                                    target="_blank">TIKLAYIN</a>
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </form>


    <hr>


</div><?php

    require_once "footer.php";
?>


<script>

    jQuery(document).ready(function ($) {
        jQuery('#kapida_odeme').click(function (e) {
            jQuery(this).toggleClass('active');

            if (jQuery(this).hasClass('active')) {
                jQuery('#tahsilatli').show();

                jQuery('[name="gar_yik_kapida_odeme"]').val(1);

            } else {
                jQuery('#tahsilatli').hide();
                jQuery('[name="gar_yik_kapida_odeme"]').val(0);

            }
        })
    });

</script>

