<?php

advanced_statistics_load_externals();

$widget = elgg_extract('entity', $vars);

$chart = $widget->chart;

if (empty($chart)) {
	echo elgg_echo('advanced_statistics:widgets:advanced_statistics:content:no_chart');
	return;
}

list($id, $text) = explode('|', $chart);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:' . $text),
	'id' => 'advanced-statistics-' . $id,
]);

if (!elgg_is_xhr()) {
	return;
}
?>
<script type='text/javascript'>
	require(['advanced_statistics/charts'], function(Charts) {
		Charts.init();
	});
</script>
