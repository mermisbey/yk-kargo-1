<?php
    if (!defined('ABSPATH')) {
        exit;
    }
    require_once "header.php";

?>
<div class="wrap">


    <table style="max-width: 1000px; margin-left: auto; margin-right:auto; margin-top: 40px" width="100%">

        <tr>
            <td>
                <div class="eklenti-bilgi">
                    <h2>Garsoft Yurtiçi Kargo Eklentisi</h2>

                    <p>
                        Bu eklenti Halil İbrahim Garbetoğlu tarafından geliştirilmiştir. Eklentiyi kullanmaya başlayabilmek için ayarlar bölümünden
                        Yurtiçi Kargo tarafından size iletilen bilgileri girmeniz gerekmektedir.
                    </p>

                    <p>Eklentimiz ücretsiz olup sürekli geliştirilmeye devam edilmektedir. Eklenti geliştirme maliyetini karşılayabilmek için premium sürüm çıkardım.</p>
                    <p>Premium sürümde gönderi istatistiklerinizi görebilir, tüm gönderilerinizin takibini tek ekran üzerinden yapabilirsiniz.</p>
                    <p>Premium sürümü için tek sefere mahsus 2.000 TL ödüyorsunuz. Gelecek olan tüm göncellemeleri de ücretsiz alıyorsunuz.</p>
                    <p>Premium sürümünü almak için Whatsapp'dan yazabilirsiniz. </p>
                </div>
            </td>
        </tr>

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

    </table>

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

