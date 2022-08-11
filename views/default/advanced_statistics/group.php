<?php
/**
 * Show group stats
 *
 * @uses $vars['entity'] the group to show for
 */

use Elgg\Values;

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

// default a date range of 30 days
set_input('ts_lower', get_input('ts_lower', Values::normalizeTime('-30 days')->format(elgg_echo('input:date_format'))));
set_input('ts_upper', get_input('ts_upper', Values::normalizeTime('now')->format(elgg_echo('input:date_format'))));

echo elgg_view('advanced_statistics/date_selector');

// group members join dates
echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:members'),
	'id' => 'advanced-statistics-group-members',
	'container_guid' => $group->guid,
	'page' => 'group_data',
	'section' => 'group',
	'chart' => 'members',
	'date_limited' => true,
]);

// group content created bar chart (content must be searchable and have activity)
echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:contenttype'),
	'id' => 'advanced-statistics-group-contenttype',
	'container_guid' => $group->guid,
	'page' => 'group_data',
	'section' => 'group',
	'chart' => 'contenttype',
	'date_limited' => true,
]);

// content creation history
echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:content_creation'),
	'id' => 'advanced-statistics-group-content-creation',
	'container_guid' => $group->guid,
	'page' => 'group_data',
	'section' => 'group',
	'chart' => 'content_creation',
	'date_limited' => true,
]);

// activity history
echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:activity'),
	'id' => 'advanced-statistics-group-activity',
	'container_guid' => $group->guid,
	'page' => 'group_data',
	'section' => 'group',
	'chart' => 'activity',
	'date_limited' => true,
]);
