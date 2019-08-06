<?php

$widget = elgg_extract('entity', $vars);

$chart = $widget->chart;

if (empty($chart)) {
	echo elgg_echo('advanced_statistics:widgets:advanced_statistics:content:no_chart');
	return;
}

elgg_require_js('advanced_statistics/charts');
elgg_require_css('advanced_statistics/jqplot');

list($id, $text) = explode('|', $chart);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:' . $text),
	'id' => 'advanced-statistics-' . $id,
]);
