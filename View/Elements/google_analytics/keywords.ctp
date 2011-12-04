<h2>Last Updated: <?php echo $time->timeAgoInWords($updated) ?></h2>
<table>
	<tr>
		<th><?php echo __('Keyword') ?></th>
		<th><?php echo __('Visits') ?></th>
	</tr>
	<?php foreach($data as $keyword) { ?>
		<tr>
			<td><?php echo $keyword['keyword']; ?></td>
			<td><?php echo $keyword['visits'] ?></td>
		</tr>
	<?php } ?>
</table>