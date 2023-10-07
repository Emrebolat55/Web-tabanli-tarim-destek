<?php

include("baglanti.php");

// Kullanıcı adı error mesajları
$username_err = "";
$email_err = "";
$parolatkr_err = "";
$parola_err = "";
$username = "";

if (isset($_POST["kaydet"])) {
    if (empty($_POST["kullaniciadi"])) {
        $username_err = "Kullanıcı Adı Boş Geçilemez!";
    } else if (strlen($_POST["kullaniciadi"]) < 6) {
        $username_err = "Kullanıcı Adı Minimum 6 Harften Oluşmalıdır!";
    } else if (!preg_match('/^[a-z\d_]{5,20}$/i', $_POST["kullaniciadi"])) {
        $username_err = "Kullanıcı adı büyük küçük harf ve rakamdan oluşmalıdır.";
    } else {
        $username = $_POST["kullaniciadi"];
    }

    if (empty($_POST["email"])) {
        $email_err = "E-mail Alanı Bos Bırakılamaz";
    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Geçersiz E-mail Formatı";
    } else {
        $email = $_POST["email"];
    }

    if (empty($_POST["parola"])) {
        $parola_err = "Parola Boş Geçilemez";
    } else {
        $password = password_hash($_POST["parola"], PASSWORD_DEFAULT);
    }

    if (empty($_POST["parolatkr"])) {
        $parolatkr_err = "Parola Tekrar Boş Geçilemez";
    } else {
        $parolatkr = $_POST["parolatkr"];
        if ($_POST["parola"] != $parolatkr) {
            $parolatkr_err = "Parolalar Birbiriyle Eşleşmiyor";
        }
    }

    if (isset($username) && isset($email) && isset($password) && empty($username_err) && empty($email_err) && empty($parola_err) && empty($parolatkr_err)) {
        // Kullanıcı adı ve e-posta varlığını kontrol et
        $existingUserQuery = "SELECT * FROM kullanicilar WHERE kullanici_adi = '$username' OR email = '$email'";
        $existingUserResult = mysqli_query($baglanti_uyelik, $existingUserQuery); // Uyelik veritabanına bağlan

        if ($existingUserResult) {
            if (mysqli_num_rows($existingUserResult) > 0) {
                while ($row = mysqli_fetch_assoc($existingUserResult)) {
                    if ($row['kullanici_adi'] === $username) {
                        $username_err = "Bu kullanıcı adı zaten kullanılıyor.";
                    }
                    if ($row['email'] === $email) {
                        $email_err = "Bu e-posta adresi zaten kullanılıyor.";
                    }
                }
            } else {
                $ekle = "INSERT INTO kullanicilar (kullanici_adi, email, parola) VALUES ('$username', '$email', '$password')";
                $calistirekle = mysqli_query($baglanti_uyelik, $ekle);

                if ($calistirekle) {
                    // Kayıt işlemi başarılı ise kullanıcı tipini "uye" olarak ayarlayalım
                    $uyeEkle = "UPDATE kullanicilar SET kullanici_tipi = 'uye' WHERE kullanici_adi = '$username'";
                    $calistirUyeEkle = mysqli_query($baglanti_uyelik, $uyeEkle);

                    if ($calistirUyeEkle) {
                        echo '<div class="alert alert-success" role="alert">
                            Kayıt işleminiz başarıyla yapıldı.
                        </div>';
                        header("Location:login.php");
                    } else {
                        echo '<div class="alert alert-danger" role="alert">
                            Kullanıcı tipi eklenirken bir hata oluştu: ' . mysqli_error($baglanti_uyelik) . '
                        </div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">
                        Kayıt esnasında bir hata oluştu: ' . mysqli_error($baglanti_uyelik) . '
                    </div>';
                }
            }

            mysqli_free_result($existingUserResult); // Sorgu sonuçlarını serbest bırak
        } else {
            echo '<div class="alert alert-danger" role="alert">
                Veritabanı hatası: ' . mysqli_error($baglanti_uyelik) . '
            </div>';
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
    <title>Üyelik Kayıt İşlemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link rel="stylesheet" href="kayit.css">
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

    <div class="container p-5">
        <div class="card p-5">
            <form method="POST">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Kullanıcı Ad</label>
                    <input type="text" class="form-control <?php if (!empty($username_err)) echo "is-invalid"; ?>"
                        id="exampleInputEmail1" name="kullaniciadi" value="<?php echo $username; ?>">
                    <div id="validationServer04Feedback" class="invalid-feedback">
                        <?php echo $username_err; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">E-mail Adresi</label>
                    <input type="email" class="form-control <?php if (!empty($email_err)) echo "is-invalid"; ?>"
                        id="exampleInputEmail1" name="email" value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ''; ?>">
                    <div id="validationServer04Feedback" class="invalid-feedback">
                        <?php echo $email_err; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Parola</label>
                    <input type="password"
                        class="form-control <?php if (!empty($parola_err)) echo "is-invalid"; ?>"
                        id="exampleInputPassword1" name="parola">
                    <div id="validationServer04Feedback" class="invalid-feedback">
                        <?php echo $parola_err; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Parola Tekrar</label>
                    <input type="password"
                        class="form-control <?php if (!empty($parolatkr_err)) echo "is-invalid"; ?>"
                        id="exampleInputPassword1" name="parolatkr">
                    <div id="validationServer04Feedback" class="invalid-feedback">
                        <?php echo $parolatkr_err; ?>
                    </div>
                </div>

                <button type="submit" name="kaydet" class="btn btn-primary">Kaydet</button>
            </form>
        </div>
    </div>

</body>
</html>
