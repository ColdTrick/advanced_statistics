<?php

advanced_statistics_load_externals();

$id = elgg_extract('id', $vars);
$title = elgg_extract('title', $vars);

$href_id = substr($id, 20);

$id_parts = explode('-', $href_id);

$chart_href = 'advanced_statistics/' . $id_parts[0] . '/' . substr($href_id, strlen($id_parts[0]) + 1);

if (elgg_extract('date_limited', $vars)) {
	$date_part = '';
	$ts_lower = get_input('ts_lower');
	$ts_upper = get_input('ts_upper');
	if ($ts_lower) {
		$date_part .= ' since ' . $ts_lower;
	}
	if ($ts_upper) {
		$date_part .= ' until ' . $ts_upper;
	}
	
	if (!empty($date_part)) {
		$title .= ' -' . $date_part;
	}
	
	$chart_href .= "?ts_lower={$ts_lower}&ts_upper={$ts_upper}";
}

$body = elgg_format_element('div', [
	'id' => $id,
	'class' => 'advanced-statistics-plot-container',
	'data-chart-href' => $chart_href,
]);
$body .= elgg_format_element('div', [
	'class' => 'elgg-ajax-loader',
]);

echo elgg_view_module('inline', $title, $body);
