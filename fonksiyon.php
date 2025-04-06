<?php


try {
    $veri = new PDO("mysql:host=localhost;port=3307;dbname=education_chat;charset=utf8", "root", "");
    $veri->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($e->getMessage());
}

class chat
{

    public $arkaplan, $yazirenk;

    function kisigetir($veri)
    {
        $uye = $veri->prepare("SELECT * FROM kisiler");
        $uye->execute();

        if ($uye->rowCount() == 0) {
            echo '<div class="alert alert-warning">Hiç kullanıcı bulunamadı.</div>';
        }

        while ($geldi = $uye->fetch(PDO::FETCH_ASSOC)):

            $ad = htmlspecialchars($geldi["ad"]);

            if ($geldi["durum"] == 1):
                echo '<span class="text-success">' . $ad . ' - Online </span><br>';
            else:
                echo '<span class="text-danger">' . $ad . ' - Offline </span><br>';
            endif;

        endwhile;
    }


    function giriskontrol($veri, $kulad, $sifre)
    {

        $sor = $veri->prepare("SELECT * FROM kisiler WHERE ad = :ad AND sifre = :sifre");
        $sor->execute([
            'ad' => $kulad,
            'sifre' => $sifre
        ]);
        $verilerim = $sor->fetch(PDO::FETCH_ASSOC);

        if ($sor->rowCount() == 0):
            echo '<div class="alert alert-danger">Bilgiler Hatalıdır.</div>';
            header("refresh:2;url=index.php");
        else:
            $sor2 = $veri->prepare("UPDATE kisiler SET durum = 1 WHERE ad = :ad");
            $sor2->execute(['ad' => $kulad]);

            echo '<div class="alert alert-success">Giriş Yapılıyor.</div>';
            header("refresh:2;url=chat.php");

            setcookie("kisiad", $kulad, time() + 3600); // 1 saatlik cookie
        endif;
    }


    function oturumkontrol($veri, $durum = false)
    {
        if (isset($_COOKIE["kisiad"])):

            $kisiad = $_COOKIE["kisiad"];
            $sor = $veri->prepare("SELECT * FROM kisiler WHERE ad = :ad");
            $sor->execute(['ad' => $kisiad]);
            $verilerim = $sor->fetch(PDO::FETCH_ASSOC);

            if ($sor->rowCount() == 0):
                header("Location: index.php");
                exit;
            else:
                if ($durum == true):
                    header("Location: chat.php");
                    exit;
                endif;
            endif;

        else:
            if ($durum == false):
                header("Location: index.php");
                exit;
            endif;
        endif;
    }


    function renklerebak($veri)
    {
        $kisiad = $_COOKIE["kisiad"];
        $sor = $veri->prepare("SELECT * FROM kisiler WHERE ad = :ad");
        $sor->execute(['ad' => $kisiad]);
        $verilerim = $sor->fetch(PDO::FETCH_ASSOC);

        $this->arkaplan = $verilerim["arkarenk"];
        $this->yazirenk = $verilerim["yazirenk"];
    }
}

$chat = $_GET["chat"] ?? null;

switch ($chat):

    case "oku":
        if (file_exists("konusmalar.txt")) {
            $dosya = fopen("konusmalar.txt", "r");
            while (!feof($dosya)):
                echo fgets($dosya);
            endwhile;
            fclose($dosya);
        }
        break;

    case "ekle":
        if (!isset($_COOKIE["kisiad"]) || !isset($_POST["mesaj"])) {
            exit("Geçersiz istek");
        }

        $kisiad = $_COOKIE["kisiad"];

        $sor2 = $veri->prepare("SELECT * FROM kisiler WHERE ad = :ad");
        $sor2->execute(['ad' => $kisiad]);
        $sonuc = $sor2->fetch(PDO::FETCH_ASSOC);

        if ($sonuc) {
            $mesaj = htmlspecialchars(strip_tags($_POST["mesaj"]));
            $dosya = fopen("konusmalar.txt", "a");
            fwrite(
                $dosya,
                '<span class="pb-5" style="color:#' . $sonuc["yazirenk"] . '"><kbd style="background-color:#' . $sonuc["arkarenk"] . '">' . $kisiad . '</kbd> ' . $mesaj . '</span><br>'
            );
            fclose($dosya);
        }
        break;

    case "cikis":
        if (!isset($_COOKIE["kisiad"])) {
            exit("Zaten çıkış yapılmış.");
        }

        $kisiad = $_COOKIE["kisiad"];

        $sor2 = $veri->prepare("UPDATE kisiler SET durum = 0 WHERE ad = :ad");
        $sor2->execute(['ad' => $kisiad]);

        setcookie("kisiad", $kisiad, time() - 3600);
        header("Location:index.php");
        break;

    case "sohbetayar":
        if ($_POST):

            $istek = $_POST["secenek"];

            if ($istek == "temizle"):

                unlink("konusmalar.txt");
                touch("konusmalar.txt");

                echo '<div class="alert alert-success mt-3">Sohbet Temizlendi.</div>';

            elseif ($istek == "kaydet"):

                copy("konusmalar.txt", "kaydedilenler/" . date("d.m.Y") . "-konusma.txt");
                echo '<div class="alert alert-success mt-3">Sohbet Depolandı.</div>';

            endif;

        endif;


        break;

    case "arkarenk":

        if ($_POST):
            $gelenrenk = $_POST["arkaplankod"];
            $kisiad = $_COOKIE["kisiad"];
            $sor = $veri->prepare("UPDATE kisiler SET arkarenk = :arkarenk WHERE ad = :ad");
            $sor->execute([
                'arkarenk' => $gelenrenk,
                'ad' => $kisiad
            ]);

            echo '<div class="alert alert-success mt-3">Arkaplan Renk Değiştirildi.</div>';

        endif;


        break;

    case "yazirenk":
        if ($_POST):
            $gelenrenk = $_POST["yazirenkkod"];
            $kisiad = $_COOKIE["kisiad"];
            $sor = $veri->prepare("UPDATE kisiler SET yazirenk = :yazirenk WHERE ad = :ad");
            $sor->execute([
                'yazirenk' => $gelenrenk,
                'ad' => $kisiad
            ]);

            echo '<div class="alert alert-success mt-3">Yazı Rengi Değiştirildi.</div>';
        endif;
        break;


    case "ortak":

        if ($_GET["uyead"] != "") :


            fwrite(fopen("kisiler.txt", "a"), '<span class="pb-5">' . $_GET["uyead"] . ' Yazıyor...</span><br>');


        endif;




        if ($_GET["temizle"] != "") :

            $dosya = "kisiler.txt";

            $ac = fopen($dosya, "r");
            $oku = fread($ac, filesize($dosya));

            $str = str_replace('<span class="pb-5">' . $_GET["temizle"] . ' Yazıyor...</span><br>', "", $oku);

            $yaz = "kisiler.txt";
            $yazd = fopen($yaz, "w");
            fwrite($yazd, $str);
            fclose($yazd);



        endif;



        break;

    case "dosyaoku":

        $dosya = fopen("kisiler.txt", "r");
        while (!feof($dosya)):
            $satir = fgets($dosya);
            print($satir);
        endwhile;
        fclose($dosya);

        break;



endswitch;
