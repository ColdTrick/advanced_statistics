<?php

/**
*   count created content (pie)
*   distribution (groups vs personal)
*   content usage in groups (% blog, %file etc)
*   content usage personal
*/

echo elgg_view('advanced_statistics/date_selector');

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:content:totals'),
	'id' => 'advanced-statistics-content-totals',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'content',
	'chart' => 'totals',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:content:distribution'),
	'id' => 'advanced-statistics-content-distribution',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'content',
	'chart' => 'distribution',
]);
