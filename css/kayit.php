<?php

include("baglanti.php");

if(isset($_POST["kaydet"]))
{
    $name = $_POST["kullaniciadi"];
    $email = $_POST["email"];
    $password = $_POST["parola"];

    $ekle = "INSERT INTO kullaniciler (kullanici_adi, email, parola) VALUES ('$name', '$email', '$password')";
    $calistirekle = mysqli_query($baglanti, $ekle);

    if ($calistirekle) {
        echo '<div class="alert alert-success" role="alert">
       Kayıt işleminiz başarıyla yapıldı.
      </div>';
    }
    else{
        echo '<div class="alert alert-danger" role="alert">
  Kayıt esnasında bir hata oluştu: ' . mysqli_error($baglanti) . '
</div>';
    }   

    mysqli_close($baglanti);
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Üyelik Giriş İşlemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  </head>
  <body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  
  <div class="container p-5">
    <div class="card p-5">
        <form method="POST">

            <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Kullanıcı Ad

            <input type="text" class="form-control" id="exampleInputEmail1" name="kullaniciadi">
        </div>


        <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" name="email">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" name="parola">
        </div>

        <button type="submit" name ="kaydet" class="btn btn-primary">kaydet</button>
        </form>
    </div>
  </div>



  </body>
</html>