<?php
function pdo_connect_mysql() {
    // MySQL bağlantısı için aşağıdaki bilgileri güncelleyin
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'phpticket';
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
        // Bağlantıda bir hata oluşursa, hata mesajını gösterip betiği sonlandırır
        exit('Veritabanına bağlanılamadı!');
    }
}

// Şablon başlığı, istediğiniz gibi özelleştirebilirsiniz
function template_header($title) {
    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>$title</title>
            <link href="ticketstyle.css" rel="stylesheet" type="text/css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        </head>
        <body>
        <nav class="navtop">
            <div>
                <h1>Ticketing System</h1>
                <a href="admin_index.php"><i class="fas fa-ticket-alt"></i>Tickets</a>

    	</div>
    </nav>
EOT;
}

function template_footer() {
    echo <<<EOT
        </body>
    </html>
EOT;
}
?>