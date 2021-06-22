<?php
/**
*   count created content (pie)
*   distribution (groups vs personal)
*   content usage in groups (% blog, %file etc)
*   content usage personal
*/

echo elgg_view('advanced_statistics/date_selector');

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:content:totals') . ' - '. elgg_echo('admin:statistics:numentities:searchable'),
	'id' => 'advanced-statistics-content-totals',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'content',
	'chart' => 'totals',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:content:totals') . ' - '. elgg_echo('admin:statistics:numentities:other'),
	'id' => 'advanced-statistics-content-totals-others',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'content',
	'chart' => 'totals-others',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:content:distribution'),
	'id' => 'advanced-statistics-content-distribution',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'content',
	'chart' => 'distribution',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:content:subscriptions'),
	'help' => elgg_echo('advanced_statistics:content:subscriptions:help'),
	'id' => 'advanced-statistics-content-subscriptions',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'content',
	'chart' => 'subscriptions',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:content:block_subscriptions'),
	'id' => 'advanced-statistics-content-block-subscriptions',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'content',
	'chart' => 'block_subscriptions',
]);
