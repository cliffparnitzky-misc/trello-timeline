<?php require_once("includes/login.inc"); ?>
<?php require_once("includes/curl.inc"); ?>
<?php if ($_SESSION['login']): ?>
<?php
if ($_GET['boards'] != "") {
  define("TRELLO_BOARD_IDS", $_GET['boards']);
} else {
  define("TRELLO_BOARD_IDS", "nC8QJJoZ");
}
?>
<?php $pageTitle = "Timeline (multiple boards)"; ?>
<?php include "includes/header.inc"; ?>
<body>
  <h1><?php echo APP_TITLE; ?> | <?php echo $pageTitle; ?></h1>
  <div id="visualization">
    <div class="menu">
      <img id="zoomIn" src="assets/zoom-in.png" alt="Vergrößern" title="Vergr&ouml;ßern" />
      <img id="zoomOut" src="assets/zoom-out.png" alt="Verkleinern" title="Verkleinern" />
      <img id="moveLeft" src="assets/move-left.png" alt="Nach links bewegen" title="Nach links bewegen" />
      <img id="moveRight" src="assets/move-right.png" alt="Nach rechts bewegen" title="Nach rechts bewegen" />
    </div> 
  </div>

<?php  ?>
<script type="text/javascript">
  // create data
  // note that months are zero-based in the JavaScript Date object
  <?php $boardIds = explode(',', TRELLO_BOARD_IDS); ?>
  <?php $customers = array(); ?> 
  var data = new vis.DataSet([
  <?php foreach ($boardIds as $boardId) : ?>
    <?php
    $jsonBoardUrl = "https://trello.com/1/boards/" . $boardId . "?key=" . TRELLO_KEY . "&token=" . TRELLO_TOKEN;
    $jsonBoardOutput = json_decode(executeRESTCall('GET', $jsonBoardUrl));
    
    $jsonCardsUrl = "https://trello.com/1/boards/" . $boardId . "/cards/open?key=" . TRELLO_KEY . "&token=" . TRELLO_TOKEN;
    $jsonCardsOutput = json_decode(executeRESTCall('GET', $jsonCardsUrl));
    ?>
    <?php foreach ($jsonCardsOutput as $card) : ?>
      <?php if ($card->due != null): ?>
        <?php $customerName = ""; ?>
        <?php $customerVisId = ""; ?>
        <?php $customerPrefix = "Kunde: "; ?>
        <?php foreach ($card->labels as $label): ?>
          <?php if (strpos($label->name, $customerPrefix) !== FALSE): ?>
            <?php $customerName = substr($label->name, strlen($customerPrefix)); ?>
            <?php $customerVisId = count($customers) + 1; ?>
            <?php if (!array_key_exists($customerName, $customers)): ?>
            <??>
              <?php $customers[$customerName] = array('visId' => $customerVisId, 'name' => $customerName, 'hasCards' => true); ?>
            <?php else: ?>
              <?php $customerVisId = $customers[$customerName]['visId']; ?>
            <?php endif; ?>
          <?php endif; ?>
        <?php endforeach; ?>
        
        <?php if (!empty($customerVisId)): ?>
        <?php $dueDate = DateTime::createFromFormat("Y-m-d\TH:i:s.uT", $card->due); ?>
        <?php $dueDate->setTimeZone(new DateTimeZone('Europe/Berlin')); ?>
    {
    'start': new Date(<?php echo $dueDate->format("Y"); ?>, <?php echo ($dueDate->format("m") - 1); ?>, <?php echo $dueDate->format("d"); ?>, <?php echo $dueDate->format("H"); ?>, <?php echo $dueDate->format("i"); ?>, <?php echo $dueDate->format("s"); ?>),
    'group': <?php echo $customerVisId; ?>,
    'content': '<a href="<?php echo $card->url; ?>" target="_blank"><?php echo str_replace("\n", "<br/>", $card->name); ?></a>',
    'title': 'Board: <?php echo $jsonBoardOutput->name; ?><br/>Termin: <?php echo $dueDate->format("d.m.Y H:i:s"); ?> Uhr',
    'className': '<?php echo $jsonBoardOutput->prefs->background; ?>'
    },
        <?php endif; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endforeach; ?>
  ]);
</script>
<script type="text/javascript">
  // create groups
  var groups = new vis.DataSet([
<?php ksort($customers); ?>
<?php foreach ($customers as $customer) : ?>
<?php if ($customer['hasCards']): ?>
  {id: <?php echo $customer['visId']; ?>, content: '<?php echo $customer['name']; ?>'},
<?php endif; ?>
<?php endforeach; ?> 
  ]);
</script>
<script type="text/javascript">
  // specify options
  var options = {
  editable: false,
  zoomable: false,
  selectable: false,
  orientation: 'top',
  start: new Date(<?php echo date("Y"); ?>, <?php echo (date("m") - 1); ?>, <?php echo (date("d") - 10); ?>, 0, 0, 0),
  end: new Date(<?php echo date("Y"); ?>, <?php echo (date("m") - 1); ?>, <?php echo (date("d") + 20); ?>, 0, 0, 0)
  };

  // create the timeline
  var container = document.getElementById('visualization');
  timeline = new vis.Timeline(container, data, options);
  timeline.setGroups(groups);
  timeline.setItems(data);

  /**
   * Move the timeline a given percentage to left or right
   * @param {Number} percentage   For example 0.1 (left) or -0.1 (right)
   */
  function move (percentage) {
    var range = timeline.getWindow();
    var interval = range.end - range.start;

    timeline.setWindow({
      start: range.start.valueOf() - interval * percentage,
      end:   range.end.valueOf()   - interval * percentage
    });
  }

  /**
   * Zoom the timeline a given percentage in or out
   * @param {Number} percentage   For example 0.1 (zoom out) or -0.1 (zoom in)
   */
  function zoom (percentage) {
    var range = timeline.getWindow();
    var interval = range.end - range.start;

    timeline.setWindow({
      start: range.start.valueOf() - interval * percentage,
      end:   range.end.valueOf()   + interval * percentage
    });
  }

  // attach events to the navigation buttons
  document.getElementById('zoomIn').onclick    = function () { zoom(-0.2); };
  document.getElementById('zoomOut').onclick   = function () { zoom( 0.2); };
  document.getElementById('moveLeft').onclick  = function () { move( 0.2); };
  document.getElementById('moveRight').onclick = function () { move(-0.2); }; 
</script>
<?php include "includes/footer.inc"; ?>
</body>
</html>
<?php endif; ?>