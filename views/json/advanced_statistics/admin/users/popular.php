<?php

$result = advanced_statistics_get_default_chart_options('bar');

$entities = elgg_get_entities_from_relationship_count([
	'type' => 'user',
	'relationship' => 'friend',
	'inverse_relationship' => false,
	'limit' => 10,
]);

$data = [];
if ($entities) {
	foreach ($entities as $user) {
		$data[] = [
			'x' => elgg_get_excerpt($user->getDisplayName(), 25),
			'y' => (int) $user->countEntitiesFromRelationship('friend', true),
		];
	}
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
