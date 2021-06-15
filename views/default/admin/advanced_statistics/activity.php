<?php
/**
*   Which days most activity?
*   Which hours most activity?
*   Activity count per day (line)
*/

echo elgg_view('advanced_statistics/date_selector');

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:activity:day'),
	'id' => 'advanced-statistics-activity-day',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'activity',
	'chart' => 'day',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:activity:hour'),
	'id' => 'advanced-statistics-activity-hour',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'activity',
	'chart' => 'hour',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:activity:timeline'),
	'id' => 'advanced-statistics-activity-timeline',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'activity',
	'chart' => 'timeline',
]);
