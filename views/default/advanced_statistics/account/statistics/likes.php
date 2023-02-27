<?php
/**
 * Show your most liked content
 */

if (!elgg_is_active_plugin('likes')) {
	return;
}

$user = elgg_get_page_owner_entity();
if (!$user instanceof ElggUser || !$user->canEdit()) {
	return;
}

$num_days = (int) elgg_extract('num_days', $vars, 90);

// top 5 liked content in the past 90 days
$entities = elgg_get_entities([
	'owner_guid' => $user->guid,
	'limit' => 5,
	'annotation_created_after' => "today - {$num_days} days",
	'annotation_name' => 'likes',
	'annotation_sort_by_calculation' => 'count',
	'full_view' => false,
]);

$likes_count = function(\ElggEntity $entity) use ($num_days) {
	return elgg_get_annotations([
		'guid' => $entity->guid,
		'count' => true,
		'annotation_created_after' => "today - {$num_days} days",
		'annotation_name' => 'likes',
	]);
};

if (empty($entities)) {
	return;
}

$body = elgg_view('output/longtext', [
	'value' => elgg_echo('advanced_statistics:account:likes:top:description', [$num_days]),
]);

$lis = [];
foreach ($entities as $entity) {
	$title = $entity->getDisplayName();
	if (!$title) {
		$keys = [
			"item:{$entity->type}:{$entity->getSubtype()}",
			"item:{$entity->subtype}",
			'untitled',
		];
		
		foreach ($keys as $key) {
			if (elgg_language_key_exists($key)) {
				$title = elgg_echo($key);
				break;
			}
		}
	}
	
	$lis[] = elgg_format_element('li', ['class' => 'elgg-item'], elgg_view('output/url', [
		'text' => $title,
		'href' => $entity->getURL(),
		'is_trusted' => true,
		'badge' => elgg_echo('likes:userslikedthis', [$likes_count($entity)]),
	]));
}

$body .= elgg_format_element('ul', ['class' => 'elgg-list'], implode(PHP_EOL, $lis));

echo elgg_view_module('info', elgg_echo('advanced_statistics:account:likes:top:title'), $body);
