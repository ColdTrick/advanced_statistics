<?php

$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('advanced_statistics:settings:enable_group_stats'),
	'name' => 'params[enable_group_stats]',
	'checked' => $plugin->enable_group_stats === 'yes',
	'switch' => true,
	'default' => 'no',
	'value' => 'yes',
]);
