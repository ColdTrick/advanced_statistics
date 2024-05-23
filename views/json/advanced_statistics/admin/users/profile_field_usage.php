<?php

$result = advanced_statistics_get_default_chart_options('bar');

$data = [];

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

$total_users_count = elgg_count_entities($base_options);

$profile_fields = elgg()->fields->get('user', 'user');
foreach ($profile_fields as $field) {
	$field_options = $base_options;
	$field_options['metadata_name_value_pairs'][] = [
		'name' => $field['name'],
	];
	$field_total = elgg_count_entities($field_options);
	
	$data[] = [
		'x' => elgg_get_excerpt(elgg_extract('#label', $field, $field['name']), 25),
		'y' => (int) round(($field_total * 100) / $total_users_count),
	];
}

$result['options']['scales']['y']['ticks']['stepSize'] = 10;
$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
