<?php
include 'functions.php';

// MySQL veritabanına bağlanma işlemi
$pdo = pdo_connect_mysql();

// URL'deki ID parametresinin varlığını kontrol et
if (!isset($_GET['id'])) {
    exit('ID belirtilmedi!');
}

session_start();

// Kullanıcının oturum açtığından emin olun
if (!isset($_SESSION["user_id"])) {
    // Kullanıcı oturum açmamışsa, giriş yapmalarını isteyin veya başka bir işlem yapın
    header("Location: login.php");
    exit;
}

// Ticket ID'sini alma işlemi...
$ticket_id = $_GET['id']; // Ticket ID'sini aldığınızdan emin olmalısınız

// MySQL sorgusu, ID sütununu kullanarak belirli bir bilet seçer
$stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = ?');
$stmt->execute([$ticket_id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

// Eğer ticket_owner değişkenini tanımlamamışsanız, aşağıdaki satırı ekleyin
$ticket_owner = $ticket['user_id'];

if ($ticket_owner == $_SESSION["user_id"]) {
    // Ticket sahibi ile oturum açan kullanıcı aynı ise, erişim izni ver
    // Ticket'ı görüntüleme işlemini yap
} else {
    // Erişim izni yok, hata mesajı göster veya başka bir işlem yap
    exit('Erişim izniniz yok!');
}

// Eğer bilet mevcut değilse
if (!$ticket) {
    exit('Geçersiz bilet ID!');
}

// Durumu güncelle
if (isset($_GET['status']) && in_array($_GET['status'], array('open', 'closed', 'resolved'))) {
    $stmt = $pdo->prepare('UPDATE tickets SET status = ? WHERE id = ?');
    $stmt->execute([$_GET['status'], $ticket_id]);
    header('Location: view.php?id=' . $ticket_id);
    exit;
}

// Yorum formunun gönderilip gönderilmediğini kontrol edin
if (isset($_POST['msg']) && !empty($_POST['msg'])) {
    // Yeni yorumu "tickets_comments" tablosuna ekleyin
    $stmt = $pdo->prepare('INSERT INTO tickets_comments (ticket_id, msg) VALUES (?, ?)');
    $stmt->execute([$ticket_id, $_POST['msg']]);
    header('Location: view.php?id=' . $ticket_id);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM tickets_comments WHERE ticket_id = ? ORDER BY created DESC');
$stmt->execute([$ticket_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Ticket')?>

<div class="content view">
    <h2><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?> <span class="<?=$ticket['status']?>">(<?=$ticket['status']?>)</span></h2>
    
    <div class="ticket">
        <p class="created"><?=date('F dS, G:ia', strtotime($ticket['created']))?></p>
        <p class="msg"><?=nl2br(htmlspecialchars($ticket['msg'], ENT_QUOTES))?></p>
    </div>

    <?php if (!empty($ticket['file_path'])): ?>
    <div style>
        <img src="<?=$ticket['file_path'].'/'.$ticket['file_name']?>" alt="Ticket Image">
    </div>
<?php endif; ?>

    <div class="btns">
        <a href="view.php?id=<?=$ticket_id?>&status=closed" class="btn red">Close</a>
        <a href="view.php?id=<?=$ticket_id?>&status=resolved" class="btn">Resolve</a>
    </div>

    <div class="comments">
        <?php foreach($comments as $comment): ?>
        <div class="comment">
            <div>
                <i class="fas fa-comment fa-2x"></i>
            </div>
            <p>
                <span><?=date('F dS, G:ia', strtotime($comment['created']))?></span>
                <?=nl2br(htmlspecialchars($comment['msg'], ENT_QUOTES))?>
            </p>
        </div>
        <?php endforeach; ?>
        <form action="" method="post">
            <textarea name="msg" placeholder="Yorumunuzu girin..."></textarea>
            <input type="submit" value="Yorum Gönder">
        </form>
    </div>
</div>

<?=template_footer()?>
