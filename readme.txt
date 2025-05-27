=== Garsoft Yurtiçi Kargo Entegrasyonu ===
Contributors: garsoft
Tags: yurtici kargo, woocommerce
Tested up to: 6.4.2
Stable tag: 3.4
Requires at least: 4.7
Requires PHP: 8.0
License: LGPL v3.0
License URI: https://www.gnu.org/licenses/lgpl-3.0.en.html



== Description ==
- Garsoft entegrasyon ekibi tarafından geliştirilen Woocommerce Yurtiçi Kargo entegrasyonu ile siparişlerinizi otomatik olarak Yurtiçi Kargo sistemine aktarabilirsiniz.
- Bu modül ile kapıda ödeme ile gelen siparişlerinizi de yurtiçi kargo sisteminize tahsilatlı olarak otomatik aktarabilirsiniz.
- Sipariş detay sayfasında barkodlarınızı tek tek oluşturabileceğiniz gibi, siparişlerim sayfasından topluca barkod çıktısı alabilirsiniz.
- Barkod çıktılarını A4-A5-A6 ve A7 ebatlarında alabilirsiniz.
- Yurtiçi kargo sistemine aktarılan siparişlerinizin durumu paketlendi durumuna otomatik olarak geçmekte, Yurtiçi kargo şubesinde barkodlarınız okunduğunda ise kargoya verildi olarak güncellenmektedir.
- Müşterileriniz kargoya verilen siparişlerin kargo takibini yurtiçi kargo sitesine gitmeden sizin siteniz üzerinden yapabilmektedir.
- Modülümüz woocommerce ile %100 uyumludur.

Requirements
PHP 5.4 and greater.
WooCommerce 3.5 requires WordPress 3.5+


== Frequently Asked Questions  ==

= Woocommerce Yurtiçi Kargo Eklentisi Ücretsiz mi?
Eklentimiz tamamen ücretsiz olup lisans ücreti adı altında sizden ücret talep edilmez.

= Eklentiyi geliştirmeye devam edecek misiniz?
Evet eklentiyi geliştirmeye devam edeceğiz. Sonraki versiyonlarında ekstra özellikler ekleyeceğiz. Bir sonraki versiyonda olmasını istediğiniz özellikleri yorum yazarak belirtebilirsiniz.

= Toplu Barkod Çıktısı Nasıl Alabilirim?
wp-admin > woocommerce -> siparişlerim sayfasına gidin. Barkod çıktısı almak istediğiniz siparişleri işaretleyin. Toplu işlemler menüsünden "Toplu Kargo Barkodu Oluştur" seçeneğini seçerek uygulaya basınız. Seçtiğiniz siparişlerin barkodları yeni sayfada açılacaktır.

= Kargo Takibi Nasıl Yapılır?
Kargo takibini kayıtlı müşteriler e ticaret sitenize giriş yaptıktan sonra siparişlerim sayfasından görüntüleyebilir. Admin kullanıcısı ise siparişin detayına girdiğinde barkod çıktısı aldığı ikon'un yanında oluşan ikona tıklayarak o siparişe ait kargo haretketlerini görüntüleyebilir.


== Screenshots ==

1. Yurtiçi Kargo Etiket Örneği
2. Eklenti Ayarları Sayfası
3. Barkod Çıktısı Alma
4. Toplu Barkod Çıktısı Alma
5. Admin Detaylı Kargo Hareketleri
6. Müşteri Detaylı Kargo Hareketleri
7. Müşteri Siparişlerim Sayfası


== Installation ==
- İndirdiğiniz dosyaları herhangi bir FTP programı ile public_html/wp-content/plugins klasörüne yükleyin.
- wp-admin > Eklentiler bölümünden Garsoft Yurtiçi Kargo Entegrasyonu eklentisini etkin hale getirin.
- wp-admin > Yurtiçi Kargo bölümünden Api kullanıcı adı, şifre ve Kargo Çıktı Ebatını seçerek ayarları kayıt edin.
- sipariş durumlarının siteniz üzerinden anlık olarak değişebilmesi için Yurtiçi Kargo ayarlar bölümünde Bilgi kısmında yazan url'yi cpanel üzerinden cronjob olarak ayarlayın.



== Changelog ==

= 3.2 =
 * Son güncellemede oluşan email problemi düzeltildi.

= 3.0 =
 * Sipariş için barkod oluşturulduğunda ve kargo teslim edildiğinde müşteriye mail gönderme fonksiyonu eklendi.
 * Tüm gönderilerin takibini tek sayfa üzerinden yapabileceğiniz gönderi listesi sayfası oluşturuldu.
 * Eklenti ayarları tasarımı değiştirildi.


= 2.1 =
 * Artık yöneticiler de detaylı kargo hareketlerini görebiliyor.
 * Sipariş detay sayfasındaki barkod yazdırma ekranı görsel olarak iyileştirildi.

= 2.0 =
 * Tahsilatlı gönderilerinizi de artık yurtiçi kargo sistemine aktarabiliyorsunuz.

= 1.0 =
 * Initial Release