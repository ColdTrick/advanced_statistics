<?php

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$total_user_count = elgg_count_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => [
		'name' => 'banned',
		'value' => 'no',
		'case_sensitive' => false,
	],
]);

$previously_used = elgg_count_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => [
		[
			'name' => 'banned',
			'value' => 'no',
			'case_sensitive' => false,
		],
		[
			'name' => 'timed_muting_end',
			'value' => time(),
			'operand' => '<',
			'type' => ELGG_VALUE_INTEGER,
		],
	],
]);

$active = elgg_count_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => [
		[
			'name' => 'banned',
			'value' => 'no',
			'case_sensitive' => false,
		],
		[
			'name' => 'timed_muting_start',
			'value' => time(),
			'operand' => '<=',
			'type' => ELGG_VALUE_INTEGER,
		],
		[
			'name' => 'timed_muting_end',
			'value' => time(),
			'operand' => '>=',
			'type' => ELGG_VALUE_INTEGER,
		],
	],
]);

$scheduled = elgg_count_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => [
		[
			'name' => 'banned',
			'value' => 'no',
			'case_sensitive' => false,
		],
		[
			'name' => 'timed_muting_start',
			'value' => time(),
			'operand' => '>',
			'type' => ELGG_VALUE_INTEGER,
		],
	],
]);

$not_configured = $total_user_count - $previously_used - $active - $scheduled;

$data = [
	[
		elgg_echo('advanced_statistics:notifications:not_configured') . " [{$not_configured}]",
		$not_configured,
	],
	[
		elgg_echo('advanced_statistics:notifications:timed_muting:previous') . " [{$previously_used}]",
		$previously_used,
	],
	[
		elgg_echo('advanced_statistics:notifications:timed_muting:active') . " [{$active}]",
		$active,
	],
	[
		elgg_echo('advanced_statistics:notifications:timed_muting:scheduled') . " [{$scheduled}]",
		$scheduled,
	],
];

$result['data'] = [$data];

echo json_encode($result);
