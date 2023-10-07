<<?php
include 'functions.php';

session_start(); // Oturumu başlat

// Logout işlemini yönet
if (isset($_GET["logout"]) && $_GET["logout"] == "true") {
    // Oturumu sonlandır
    session_unset();
    session_destroy();
    // Giriş sayfasına yönlendir
    header("Location: login.php");
    exit;
}

// Connect to MySQL using the below function
$pdo = pdo_connect_mysql();

// Dosya yükleme işlevi
function uploadFile($fileInputName, $uploadDirectory)
{
    $uploadedFile = $_FILES[$fileInputName];

    if ($uploadedFile["error"] != UPLOAD_ERR_OK) {
        return false;
    }

    $allowedFileTypes = array("jpg", "jpeg", "png", "gif");
    $fileExtension = strtolower(pathinfo($uploadedFile["name"], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedFileTypes)) {
        return false;
    }

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    $newFileName = uniqid() . "." . $fileExtension;
    $destination = $uploadDirectory . "/" . $newFileName;

    if (move_uploaded_file($uploadedFile["tmp_name"], $destination)) {
        return $newFileName;
    } else {
        return false;
    }
}

// Form verilerini işleme al
$msg = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $email = $_POST["email"];
    $msg = $_POST["msg"];
    $user_id = $_SESSION['user_id'];

    // Dosya yükleme işlemini çağırın
    $uploadDirectory = "uploads"; // Yükleme dizini
    $fileInputName = "file"; // HTML formundaki dosya yükleme alanının adı
    $uploadedFileName = uploadFile($fileInputName, $uploadDirectory);

    if ($uploadedFileName) {
        // Dosya başarıyla yüklendi, burada veritabanına veya başka bir yere kaydedebilirsiniz.
        // $uploadedFileName, yüklenen dosyanın yeni adıdır.

        // Ticket oluşturma işlemini gerçekleştir
        $stmt = $pdo->prepare("INSERT INTO tickets (user_id, title, email, msg, file_name, file_path) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $title, $email, $msg, $uploadedFileName, $uploadDirectory])) {
            $msg = 'Ticket created successfully!';
        } else {
            $msg = 'An error occurred while creating the ticket.';
        }
    } else {
        $msg = 'An error occurred while uploading the file.';
    }
}
?>

<?=template_header('Create Ticket')?>

<div class="content create">
    <h2>Create Ticket</h2>
    <form action="create.php" method="post" enctype="multipart/form-data"> <!-- enctype="multipart/form-data" eklenmelidir -->
        <label for="title">Başlık</label>
        <input type="text" name="title" id="title" required>
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        <label for="msg">Mesajınız</label>
        <textarea name="msg" id="msg" rows="4" required></textarea>
        <label for="file">Dosya Seçiniz</label>
        <input type="file" name="file" id="file" accept="image/*"> <!-- Dosya yükleme alanını ekledik -->
        <input type="submit" value="Create">
    </form>
    <p><?=$msg?></p>
</div>

<?=template_footer()?>
