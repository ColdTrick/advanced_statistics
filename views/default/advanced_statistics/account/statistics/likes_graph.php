<?php
/**
 * Show total likes over time in a graph
 */

if (!elgg_is_active_plugin('likes')) {
	return;
}

$user = elgg_get_page_owner_entity();
if (!$user instanceof \ElggUser || !$user->canEdit()) {
	return;
}

// likes graph
$count = elgg_get_annotations([
	'owner_guid' => $user->guid,
	'count' => true,
	'annotation_name' => 'likes',
]);

if ($count > 10) {
	echo elgg_view('advanced_statistics/elements/chart', [
		'title' => elgg_echo('advanced_statistics:account:likes:chart'),
		'id' => 'advanced-statistics-account-likes-chart',
		'date_limited' => false,
		'page' => 'account',
		'section' => 'likes',
		'chart' => 'count',
		'url_elements' => [
			'user_guid' => $user->guid,
		],
	]);
}
