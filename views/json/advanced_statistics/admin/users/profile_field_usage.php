<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$total_users_count = elgg_get_entities([
	'type' => 'user',
	'count' => true,
]);

$data = [];
$ticks = [];

$profile_fields = elgg_get_config('profile_fields');
foreach ($profile_fields as $field_name => $type) {
	
	$field_total = elgg_get_entities([
		'type' => 'user',
		'count' => true,
		'metadata_name' => $field_name,
	]);
		
	$ticks[] = elgg_get_excerpt(elgg_echo("profile:{$field_name}"), 25);
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
