<?php
include 'admin_functions.php';

session_start(); // Start the session

if (isset($_GET["logout"]) && $_GET["logout"] == "true") {
    // Destroy the session
    session_unset();
    session_destroy();
    // Redirect to login page
    header("Location: login.php");
    exit;
}



// Connect to MySQL using the below function
$pdo = pdo_connect_mysql();
// MySQL query that retrieves all the tickets from the database
$stmt = $pdo->prepare('SELECT * FROM tickets ORDER BY created DESC');
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_header('Tickets')?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tickets</title>
    <link href="ticketstyle.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
</head>
<body>
        </div>
    </nav>
    <div class="content home">
        <h2>Tickets</h2>
        <p>Merhaba Değerli Adminlerimiz. Aşağıdan Destek Taleplerine Tıklayarak Ulaşabilirsiniz.</p>
        <div class="tickets-list">
            <?php foreach ($tickets as $ticket): ?>
            <a href="admin_view.php?id=<?=$ticket['id']?>" class="ticket">
                <span class="con">
                    <?php if ($ticket['status'] == 'open'): ?>
                    <i class="far fa-clock fa-2x"></i>
                    <?php elseif ($ticket['status'] == 'resolved'): ?>
                    <i class="fas fa-check fa-2x"></i>
                    <?php elseif ($ticket['status'] == 'closed'): ?>
                    <i class="fas fa-times fa-2x"></i>
                    <?php endif; ?>
                </span>
                <span class="con">
                    <span class="title"><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?></span>
                    <span class="msg"><?=htmlspecialchars($ticket['msg'], ENT_QUOTES)?></span>
                </span>
                <span class="con created"><?=date('F dS, G:ia', strtotime($ticket['created']))?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="logout-btn">
		<a href="?logout=true" class="btn btn-primary">Logout</a>
	</div>

</body>
</html>
