<?php

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:notifications:delayed_interval'),
	'id' => 'advanced-statistics-notifications-delayed-interval',
	'page' => 'admin_data',
	'section' => 'notifications',
	'chart' => 'delayed_interval',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:notifications:timed_muting'),
	'id' => 'advanced-statistics-notifications-timed-muting',
	'page' => 'admin_data',
	'section' => 'notifications',
	'chart' => 'timed_muting',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:notifications:user_configured_methods'),
	'id' => 'advanced-statistics-notifications-user-configured-methods',
	'page' => 'admin_data',
	'section' => 'notifications',
	'chart' => 'user_configured_methods',
]);

if (elgg_is_active_plugin('friends')) {
	echo elgg_view('advanced_statistics/elements/chart', [
		'title' => elgg_echo('advanced_statistics:notifications:users_generic_vs_specific'),
		'help' => elgg_echo('advanced_statistics:notifications:generic_vs_specific:help'),
		'id' => 'advanced-statistics-notifications-users-generic-vs-specific',
		'page' => 'admin_data',
		'section' => 'notifications',
		'chart' => 'users_generic_vs_specific',
	]);
}

if (elgg_is_active_plugin('groups')) {
	echo elgg_view('advanced_statistics/elements/chart', [
		'title' => elgg_echo('advanced_statistics:notifications:groups_generic_vs_specific'),
		'help' => elgg_echo('advanced_statistics:notifications:generic_vs_specific:help'),
		'id' => 'advanced-statistics-notifications-groups-generic-vs-specific',
		'page' => 'admin_data',
		'section' => 'notifications',
		'chart' => 'groups_generic_vs_specific',
	]);
}
