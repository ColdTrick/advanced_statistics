<?php

$widget = elgg_extract('entity', $vars);

$chart = $widget->chart;

if (empty($chart)) {
	echo elgg_echo('advanced_statistics:widgets:advanced_statistics:content:no_chart');
	return;
}

elgg_import_esm('advanced_statistics/charts');

list($id, $text) = explode('|', $chart);
list($section, $chart_src) = explode(':', $text);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:' . $text),
	'id' => 'advanced-statistics-' . $id,
	'page' => 'admin_data',
	'section' => $section,
	'chart' => $chart_src,
]);
