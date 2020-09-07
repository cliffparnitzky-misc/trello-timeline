<?php require_once("includes/login.inc"); ?>
<?php require_once("includes/curl.inc"); ?>
<?php if ($_SESSION['login']): ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta http-equiv="content-language" content="de" />
	<title><?php echo APP_TITLE; ?> | List</title>
	<link rel="stylesheet" type="text/css" href="assets/default.css">
	<link rel="icon" type="image/png" href="assets/favicon.png">
	<style>
	body > * {
		clear: both;
		float: left;
		width: 100%;
	}
	.level_1 i, .level_1 ul {
		float: left;
	}
	.level_1 > li, .level_2 > li {
		clear: left;
		list-style: none outside none;
	}
	.level_3 > li {
		margin-bottom: 5px;
	}
	</style>
</head>
<body>
	<h1><?php echo APP_TITLE; ?> | Login</h1>
<?php
$jsonBoardsUrl = "https://trello.com/1/members/" . TRELLO_MEMBER . "/boards?key=" . TRELLO_KEY . "&token=" . TRELLO_TOKEN;
$jsonBoardsOutput = json_decode(executeRESTCall('GET', $jsonBoardsUrl));
$jsonCardsUrl = "https://trello.com/1/boards/[BOARD_ID]/cards/open?key=" . TRELLO_KEY . "&token=" . TRELLO_TOKEN;
$cardCounter = 0;
$dueCards = array();
?>

<?php foreach ($jsonBoardsOutput as $board) : ?>
	<?php if (!$board->closed): ?>
		<?php
		$jsonCardsUrlTmp = str_replace("[BOARD_ID]", $board->id, $jsonCardsUrl);
		$jsonCardsOutput = json_decode(executeRESTCall('GET', $jsonCardsUrlTmp));
		?>
		<?php foreach ($jsonCardsOutput as $card) : ?>
				<?php if ($card->due != null): ?>
					<?php $dueDate = DateTime::createFromFormat("Y-m-d\TH:i:s.uT", $card->due); ?>
					<?php $dueDate->setTimeZone(new DateTimeZone('Europe/Berlin')); ?>
					<?php $card->due = $dueDate; ?>
				
					<?php $dayKey = $dueDate->format("Ymd"); ?>
					<?php $hourKey = $dueDate->format("His"); ?>
					<?php $dueCards[$dayKey][$hourKey][] = $card; ?>
					
					<?php $card->nameBoard = $board->name; ?>
					
					<?php $cardCounter++; ?>
				<?php endif; ?>
		<?php endforeach; ?>
		<?php endif; ?>
<?php endforeach; ?>
	<?php ksort($dueCards); ?>
	<ul class="level_1">
	<?php foreach ($dueCards as $day=>$times) : ?>
		<li>
		<i><?php echo DateTime::createFromFormat("Ymd", $day)->format("d.m.Y"); ?></i>
		<ul class="level_2">
		<?php ksort($times); ?>
		<?php foreach ($times as $time=>$cards) : ?>
			<li>
			<i><?php echo DateTime::createFromFormat("His", $time)->format("H:i:s"); ?> Uhr</i>
			<ul class="level_3">
			<?php foreach ($cards as $card) : ?>
				<li><a href="<?php echo $card->url; ?>" target="_blank" title="Board: <?php echo $card->nameBoard . "\n"; ?>Termin: <?php echo $card->due->format("d.m.Y H:i:s"); ?> Uhr"><?php echo $card->name; ?></a></li>
			<?php endforeach; ?>
			</ul>
			</li>
		<?php endforeach; ?>
		</ul>
		</li>
	<?php endforeach; ?>
	</ul>
	<span><?php echo $cardCounter; ?> Karten gefunden</span>
<?php include "includes/footer.inc"; ?>
</body>
</html>
<?php endif; ?>