<?php
/**
	10 most popular groups (bar)
	new groups / total per week
    enabled tools for group (pie)
    10 most active groups (most activity in last week) (bar)
    10 least active groups (least activity in last week)
    Dead vs Alive groups (last activity < 1 month <3 <6 <12) (pie)
*/

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:groups:popular'),
	'id' => 'advanced-statistics-groups-popular',
	'page' => 'admin_data',
	'section' => 'groups',
	'chart' => 'popular',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:groups:created'),
	'id' => 'advanced-statistics-groups-created',
	'page' => 'admin_data',
	'section' => 'groups',
	'chart' => 'created',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:groups:popular_tools'),
	'id' => 'advanced-statistics-groups-popular-tools',
	'page' => 'admin_data',
	'section' => 'groups',
	'chart' => 'popular_tools',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:groups:most_active'),
	'id' => 'advanced-statistics-groups-most-active',
	'page' => 'admin_data',
	'section' => 'groups',
	'chart' => 'most_active',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:groups:least_active'),
	'id' => 'advanced-statistics-groups-least-active',
	'page' => 'admin_data',
	'section' => 'groups',
	'chart' => 'least_active',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:groups:dead_vs_alive'),
	'id' => 'advanced-statistics-groups-dead-vs-alive',
	'page' => 'admin_data',
	'section' => 'groups',
	'chart' => 'dead_vs_alive',
]);
