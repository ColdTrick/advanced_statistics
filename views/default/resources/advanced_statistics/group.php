<?php

$group = elgg_get_page_owner_entity();
if (!$group instanceof \ElggGroup) {
	forward();
}

$title = elgg_echo('advanced_statistics:group:title');
$content = '';

// group members join dates
$content .= elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:members'),
	'id' => 'advanced-statistics-group-members',
	'container_guid' => $group->guid,
]);

// group content pie
$content .= elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:contenttype'),
	'id' => 'advanced-statistics-group-contenttype',
	'container_guid' => $group->guid,
]);

// content creation history
$content .= elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:content_creation'),
	'id' => 'advanced-statistics-group-content-creation',
	'container_guid' => $group->guid,
]);

// activity history
$content .= elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:group:activity'),
	'id' => 'advanced-statistics-group-activity',
	'container_guid' => $group->guid,
]);

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $body);
