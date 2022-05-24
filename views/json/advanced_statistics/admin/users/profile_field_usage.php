<?php

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$total_users_count = elgg_count_entities(['type' => 'user']);

$data = [];
$ticks = [];

$base_options = [
	'type' => 'user',
	'metadata_name_value_pairs' => [],
];

if (!(bool) elgg_extract('include_banned_users', $vars, true)) {
	$base_options['metadata_name_value_pairs'][] = [
		'name' => 'banned',
		'value' => 'no',
		'case_sensitive' => false,
	];
}

$profile_fields = elgg()->fields->get('user', 'user');
foreach ($profile_fields as $field) {
	$field_options = $base_options;
	$field_options['metadata_name_value_pairs'][] = [
		'name' => $field['name'],
	];
	$field_total = elgg_count_entities($field_options);
		
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
