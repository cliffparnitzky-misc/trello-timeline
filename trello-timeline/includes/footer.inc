<?php 
$customMenuFile = "config/custom_menu.inc";
if(is_file($customMenuFile))
{
	require_once($customMenuFile);
}
?>
	<div id="footer" style="text-align: center; margin-top: 20px;">
		<b>Main menu:</b> <a href="index.php">Home</a> | <a href="list.php">List</a> | <a href="timeline.php">Timeline</a> | <a href="timeline_grouped.php">Timeline (grouped by board)</a> | <a href="timeline_single.php">Timeline (single board)</a> | <a href="timeline_multiple.php">Timeline (multiple boards)</a>
	<?php if ($customMenu && count($customMenu) > 0): ?>
		<br/><br/>
		<b>Custom menu:</b> 
		<?php foreach($customMenu as $index=>$menu): ?>
    <a href="<?php echo $menu['link']; ?>?pageTitle=<?php echo $menu['title']; ?><?php if (strlen($menu['params']) > 0): ?>&<?php echo $menu['params']; ?><?php endif; ?>"><?php echo $menu['title']; ?></a><?php if ($index < count($customMenu) - 1 ): ?> | <?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	</div>
