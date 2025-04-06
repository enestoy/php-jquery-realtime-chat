<?php
include('fonksiyon.php');
$chat = new chat;
$chat->oturumkontrol($veri,true);
?>



<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Giriş</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery CDN -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      background-color: #f0f2f5;
    }

    .login-card {
      margin-top: 10%;
      padding: 2rem;
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .login-header {
      border-bottom: 1px solid #dee2e6;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>

  <div class="container d-flex justify-content-center align-items-center">
    <div class="col-md-6 col-lg-4 login-card">
      
      <?php 
      @$buton = $_POST["buton"];
      if (!$buton): 
      ?>
      
      <div class="login-header text-center mb-3">
        <h3>Chat Giriş</h3>
      </div>
      
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <div class="mb-3">
          <input type="text" name="kulad" class="form-control" required placeholder="Kullanıcı Adınız" autofocus>
        </div>
        <div class="mb-3">
          <input type="password" name="sifre" class="form-control" required placeholder="Şifreniz">
        </div>
        <div class="d-grid">
          <input type="submit" name="buton" class="btn btn-primary" value="GİRİŞ">
        </div>
      </form>

      <?php 
      else:
        @$sifre = htmlspecialchars(strip_tags($_POST["sifre"]));
        @$kulad = htmlspecialchars(strip_tags($_POST["kulad"]));
        
        if ($sifre == "" || $kulad == "") {
          echo '<div class="alert alert-danger mt-3">Bilgiler boş olamaz</div>';
          header("refresh:2;url=index.php");
        } else {
          $chat->giriskontrol($veri,$kulad,$sifre);
          echo '<div class="alert alert-success mt-3">Giriş başarılı (örnek çıktı)</div>';
        }
      endif;
      ?>
      
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
