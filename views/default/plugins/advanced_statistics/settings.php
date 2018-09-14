<?php

$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'select',
	'#label' => elgg_echo('advanced_statistics:settings:enable_group_stats'),
	'name' => 'params[enable_group_stats]',
	'value' => $plugin->enable_group_stats,
	'options_values' => [
		'no' => elgg_echo('option:no'),
		'yes' => elgg_echo('option:yes'),
	],
]);
