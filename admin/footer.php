<script>
    jQuery('.takip_kargo').click(function (e) {
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
</script>