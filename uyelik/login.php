<?php
session_start(); // Oturumu başlat

include("baglanti.php");

//error mesajları
$username_err = "";
$password_err = "";
//zaten giriş yapıp yapmadığını kontrol et
if (isset($_SESSION["user_id"])) {
    if ($_SESSION["kullanici_tipi"] == "uye") {
        header("Location: index.php");
    } elseif ($_SESSION["kullanici_tipi"] == "admin") {
        header("Location: admin_index.php");
    }
    exit;
}

// logout
if (isset($_GET["logout"]) && $_GET["logout"] == "true") {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

if (isset($_POST["giris"])) {
    if (empty($_POST["kullaniciadi"])) {
        $username_err = "Kullanıcı Adı Boş Geçilemez!";
    } else {
        $username = $_POST["kullaniciadi"];
    }

    if (empty($_POST["parola"])) {
        $password_err = "Parola Boş Geçilemez";
    } else {
        $password = $_POST["parola"];
    }

    if (isset($username) && isset($password)) {
        $secim = "SELECT * FROM kullanicilar WHERE kullanici_adi ='$username'";
        $calistir = mysqli_query($baglanti_uyelik, $secim);
        $kayitsayisi = mysqli_num_rows($calistir);

        if ($kayitsayisi > 0) {
            $ilgilikayit = mysqli_fetch_assoc($calistir);
            if (password_verify($password, $ilgilikayit['parola'])) {
                $_SESSION["user_id"] = $ilgilikayit["user_id"];
                $_SESSION["kullanici_adi"] = $ilgilikayit["kullanici_adi"];
                $_SESSION["kullanici_tipi"] = $ilgilikayit["kullanici_tipi"];

                if ($_SESSION["kullanici_tipi"] == "uye") {
                    header("Location: index.php");
                } elseif ($_SESSION["kullanici_tipi"] == "admin") {
                    header("Location: admin_index.php");
                }
                exit;
            } else {
                $password_err = "Parola doğrulama hatası!";
            }
        } else {
            $username_err = "Böyle bir kullanıcı bulunamadı.";
        }

        mysqli_close($baglanti_uyelik);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giriş Yap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <div class="container p-5">
        <div class="card p-5">
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Kullanıcı Ad</label>
                    <input type="text" class="form-control <?php if (!empty($username_err)) echo "is-invalid"; ?>"
                        id="exampleInputEmail1" name="kullaniciadi">
                    <div id="validationServer04Feedback" class="invalid-feedback">
                        <?php echo $username_err; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Parola</label>
                    <input type="password"
                        class="form-control <?php if (!empty($password_err)) echo "is-invalid"; ?>"
                        id="exampleInputPassword1" name="parola">
                    <div id="validationServer04Feedback" class="invalid-feedback">
                        <?php echo $password_err; ?>
                    </div>
                </div>

                <button type="submit" name="giris" class="btn btn-primary">Giriş</button>
            </form>
        </div>
    </div>

</body>

</html>
