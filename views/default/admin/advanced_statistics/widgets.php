<?php
/**
*   Widget handler counts (pie)
*   Widget context
*/

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:widgets:handlers'),
	'id' => 'advanced-statistics-widgets-handlers',
	'page' => 'admin_data',
	'section' => 'widgets',
	'chart' => 'handlers',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:widgets:context'),
	'id' => 'advanced-statistics-widgets-context',
	'page' => 'admin_data',
	'section' => 'widgets',
	'chart' => 'context',
]);
