<?php
$host = "localhost";
$kullanici = "root";
$parola = "";
$vt_uyelik = "uyelik"; // Uyelik veritabanı adı
$vt_phpticket = "phpticket"; // Phpticket veritabanı adı

$baglanti_uyelik = mysqli_connect($host, $kullanici, $parola, $vt_uyelik);
mysqli_set_charset($baglanti_uyelik, "UTF8");

$baglanti_phpticket = mysqli_connect($host, $kullanici, $parola, $vt_phpticket);
mysqli_set_charset($baglanti_phpticket, "UTF8");
?>
