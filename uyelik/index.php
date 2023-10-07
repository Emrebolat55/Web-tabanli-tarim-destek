<?php
include 'functions.php';

session_start(); // Start the session

// Handle logout
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

// Retrieve the user ID from the session
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];
} else {
    // If user is not logged in, redirect to login page
    header("Location: login.php");
    exit;
}

// MySQL query that retrieves tickets for the logged-in user
$stmt = $pdo->prepare('SELECT * FROM tickets WHERE user_id = ? ORDER BY created DESC');
$stmt->execute([$user_id]);
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_header('Tickets')?>

<div class="content home">

	<h2>Tickets</h2>

	<p>Welcome to the index page. You can view the list of your tickets below.</p>

	<div class="btns">
		<a href="create.php" class="btn">Create Ticket</a>
	</div>

	<div class="tickets-list">
		<?php foreach ($tickets as $ticket): ?>
		<a href="view.php?id=<?=$ticket['id']?>" class="ticket">
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

	<div class="logout-btn">
		<a href="?logout=true" class="btn btn-primary">Logout</a>
	</div>

</div>

<?=template_footer()?>
