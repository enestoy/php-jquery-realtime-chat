$(document).ready(function() {
    // Mesajları sürekli oku
    $("#konusmalar").load("fonksiyon.php?chat=oku");
    setInterval(function() {
        $("#konusmalar").load("fonksiyon.php?chat=oku");
    }, 2000);

    // Enter'a basınca mesajı gönder
    $("#gonder").keydown(function(e) {
        if (e.keyCode == 13 && !e.shiftKey) { // Shift + Enter'ı engelle
            e.preventDefault(); // Enter'ın varsayılan yeni satır ekleme davranışını engelle

            let text = $(this).val();
            let max = $(this).attr("maxlength");

            if (text.length > 5 && text.length < max) {
                $.post("fonksiyon.php?chat=ekle", $("#mesajgonder").serialize(), function() {
                    $("#gonder").val(""); // Mesajı gönderdikten sonra textarea'yı temizle
                    $("#konusmalar").load("fonksiyon.php?chat=oku"); // Yeni mesajları yükle
                    $("#konusmalar").scrollTop($("#konusmalar")[0].scrollHeight); // En son mesaja kaydır
                });
            } else {
                $("#gonder").val(""); // Eğer mesaj çok kısa ise textarea'yı temizle
            }
        }
    });

    // Form submit olayını engelle
    $("#mesajgonder").submit(function(e){
        e.preventDefault(); // Formun varsayılan submit davranışını engelle
    });
});
