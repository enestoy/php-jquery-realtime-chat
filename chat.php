<?php
include('fonksiyon.php');
$chat = new chat;
$chat->oturumkontrol($veri, false);
$chat->renklerebak($veri);

?>



<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PHP jQuery Chat</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Custom JS -->
  <script src="chat.js"></script>

  <script src="jscolor.js"></script>

  <script>
    $(document).ready(function() {

      $('#kapsayici').hide();

      setInterval(function() {
        $("#yaziyor").load("fonksiyon.php?chat=dosyaoku");
      }, 2000);


      $("#sohbetayar a").click(function() {

        var gelendeger = $(this).attr("sectionId");

        $.post("fonksiyon.php?chat=sohbetayar", {
          "secenek": gelendeger
        }, function(gelenveri) {

          $("#sohbetayar").html(gelenveri).fadeIn();

          setInterval(function() {
            window.location.reload();
          }, 2000);

        });


      });


      $("#arkabtn").click(function() {

        $.ajax({
          type: "POST",
          url: "fonksiyon.php?chat=arkarenk",
          data: $("#arkaplan").serialize(),
          success: function(gelenveri) {

            $("#arkaplandegistir").html(gelenveri).fadeIn();
            setInterval(function() {
              window.location.reload();
            }, 2000);
          }
        })
      });

      $("#yazibtn").click(function() {

        $.ajax({
          type: "POST",
          url: "fonksiyon.php?chat=yazirenk",
          data: $("#yazirenk").serialize(),
          success: function(gelenveri) {

            $("#yazidegistir").html(gelenveri).fadeIn();
            setInterval(function() {
              window.location.reload();
            }, 2000);
          }
        })
      });


      var sayac = 0;

      $('body').delegate('#gonder', "keyup change", function() {

        var text = $("#gonder").val();
        var uzunluk = text.length;
        var uyead = $("#gonder").attr("sectionId");

        if (uzunluk > 0 && sayac == 0) {

          $.get("fonksiyon.php?chat=ortak", {
            "uyead": uyead
          }, function() {


            setInterval(function() {
              $("#yaziyor").load("fonksiyon.php?chat=dosyaoku");
            }, 2000);


            sayac = 1;
          });



        } else if (uzunluk == 0)

        {


          $.get("fonksiyon.php?chat=ortak", {
            "temizle": uyead
          }, function() {



            setInterval(function() {
              $("#yaziyor").load("fonksiyon.php?chat=dosyaoku");
            }, 2000);

            sayac = 0;
          });


        }



      });


      $('#ozellikackapa').click(function() {

        $('#kapsayici').slideToggle();

      });
    });
  </script>

  <style>
    body {
      background-color: #f3f3f3;
    }

    #kivir {
      border-radius: 10px;
      border: 1px solid #e0e0e0;
      min-height: 400px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    #renk {
      border-radius: 10px;
      border: 1px solid #e0e0e0;
      min-height: 50px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
    }

    #konusmalar {
      overflow-y: scroll;
      height: 250px;
      background-color: #fafafa;
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 10px;
    }

    textarea {
      resize: none;
    }

    .kisiler-baslik {
      background-color: #0d6efd;
      color: white;
      font-weight: bold;
      padding: 0.5rem;
      border-radius: 5px 5px 0 0;
    }

    .kisiler-listesi {
      min-height: 290px;
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 0 0 5px 5px;
      padding: 10px;
    }

    .cikis {
      padding: 10px;
      background-color: #fff;
      border-top: 1px solid #dee2e6;
    }
  </style>

</head>

<body>

  <div class="container">
    <div class="row">

      <div class="col-md-8 col-lg-6 mx-auto mt-5 bg-white" id="kivir">

        <div class="row">

          <div class="col-12 border-bottom py-2">
            <h3 class="text-secondary fw-bold">PHP jQuery Chat Uygulaması</h3>
          </div>

          <!-- Kişiler Bölmesi -->
          <div class="col-md-3 text-start mt-3">
            <div class="kisiler-baslik text-center">KİŞİLER</div>
            <div class="kisiler-listesi">
              <?php $chat->kisigetir($veri); ?>
            </div>
            <div class="cikis text-center">
              <a href="fonksiyon.php?chat=cikis" class="btn btn-sm btn-danger">Çıkış Yap</a>
            </div>
          </div>

          <!-- Sohbet Alanı -->
          <div class="col-md-9 mt-3">
            <div class="row">
              <div class="col-12 mb-2">
                <div id="konusmalar">
                  <!-- Yazışmalar buraya gelecek -->
                </div>
              </div>

              <div class="col-12">
                <form id="mesajgonder">
                  <textarea id="gonder" name="mesaj" maxlength='100' cols="10" rows="3" class="form-control mt-2 " sectionId="<?php echo $_COOKIE["kisiad"]; ?>"></textarea>

                </form>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- Renk Ayarı Alanı -->
    <div class="row mt-2">
      <div class="col-md-8 col-lg-6 mx-auto bg-white" id="renk">

        <div class="row text-center">

          <div class="col-md-12">
            <div class="row text-left">
              <div class="col-md-9 border-right bg-light text-danger p-1" id="yaziyor"></div>
              <div class="col-md-3 text-right p-2">
                <a class="btn btn-danger btn-sm p-1 m-1 text-white" id="ozellikackapa">Özellik Aç\Kapa</a>
              </div>

            </div>

          </div>

        </div>

        <div class="row text-center" id="kapsayici">

          <div class="col-md-4 border border-right" id="arkaplandegistir">
            <form id="arkaplan">
              <label class="mt-2">Arka Plan Değiştir</label>
              <input type="text" name="arkaplankod" class="form-control mt-2 jscolor" value="<?php echo $chat->arkaplan; ?>">
              <input type="button" id="arkabtn" value="Değistir" class="btn btn-primary btn-sm mt-2 mb-1">

            </form>

          </div>
          <div class="col-md-4 border border-right" id="yazidegistir">
            <form id="yazirenk">
              <label class="mt-2">Yazı Renk Değiştir</label>
              <input type="text" name="yazirenkkod" class="form-control mt-2 jscolor" value="<?php echo $chat->yazirenk; ?>">
              <input type="button" id="yazibtn" value="Değistir" class="btn btn-primary btn-sm mt-2 mb-1">

            </form>
          </div>
          <div class="col-md-4" id="sohbetayar">
            <label class="mt-2">Ayarlar</label><br>
            <a class="btn btn-sm btn-dark mt-2 mb-1 text-white" sectionId="temizle">Sohbeti Temizle</a>
            <a class="btn btn-sm btn-secondary mt-2 mb-1 text-white" sectionId="kaydet">Sohbeti Kaydet</a>
          </div>
        </div>
      </div>
    </div>
    
  </div>

</body>

</html>