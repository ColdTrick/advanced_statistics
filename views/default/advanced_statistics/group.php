<?php
/**
 * Show group stats
 *
 * @uses $vars['entity'] the group to show for
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof ElggGroup) {
	return;
}

// group members join dates
echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:members'),
	'id' => 'advanced-statistics-group-members',
	'container_guid' => $group->guid,
	'page' => 'group_data',
	'section' => 'group',
	'chart' => 'members',
]);

// group content pie
echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:contenttype'),
	'id' => 'advanced-statistics-group-contenttype',
	'container_guid' => $group->guid,
	'page' => 'group_data',
	'section' => 'group',
	'chart' => 'contenttype',
]);

// content creation history
echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:content_creation'),
	'id' => 'advanced-statistics-group-content-creation',
	'container_guid' => $group->guid,
	'page' => 'group_data',
	'section' => 'group',
	'chart' => 'content_creation',
]);

// activity history
echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:activity'),
	'id' => 'advanced-statistics-group-activity',
	'container_guid' => $group->guid,
	'page' => 'group_data',
	'section' => 'group',
	'chart' => 'activity',
]);
