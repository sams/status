<h2>Last Updated: <?php echo $time->timeAgoInWords($updated) ?></h2>
<table>
	<tr>
		<th><?php echo __('When') ?></th>
		<th><?php echo __('Visits') ?></th>
	</tr>
	<?php
		foreach($data as $when => $count) {
			echo $this->element('google_analytics/visits_' . $span, compact('when', 'count'));
		}
	?>
</table>