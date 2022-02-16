<?php

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$total_users_count = elgg_count_entities(['type' => 'user']);

$data = [];
$ticks = [];

$profile_fields = elgg()->fields->get('user', 'user');
foreach ($profile_fields as $field) {
	$field_total = elgg_count_entities([
		'type' => 'user',
		'metadata_name' => $field['name'],
	]);
		
	$ticks[] = elgg_get_excerpt(elgg_extract('#label', $field, $field['name']), 25);
	$data[] = round(($field_total * 100) / $total_users_count);
}

$result['data'] = [$data];

$result['options']['axes']['xaxis']['ticks'] = $ticks;
$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CanvasAxisTickRenderer';
$result['options']['axes']['xaxis']['tickOptions'] = [
	'angle' => '-30',
	'fontSize' => '8pt',
];
$result['options']['axes']['yaxis'] = [
	'tickOptions' => [
		'formatString' => '%d%',
	],
];

echo json_encode($result);
