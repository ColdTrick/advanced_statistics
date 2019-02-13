<?php

/**
*

FileStorage

    most consuming owners (bar)
    most consuming groups (bar)
    Based on ElggFile and ElggPhoto (tidypics) stacked bar

*/

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:system:files:users'),
	'id' => 'advanced-statistics-system-files-users',
	'page' => 'admin_data',
	'section' => 'system',
	'chart' => 'files_users',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:system:files:groups'),
	'id' => 'advanced-statistics-system-files-groups',
	'page' => 'admin_data',
	'section' => 'system',
	'chart' => 'files_groups',
]);
