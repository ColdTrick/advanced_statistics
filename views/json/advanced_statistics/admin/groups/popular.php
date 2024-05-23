<?php

$result = advanced_statistics_get_default_chart_options('bar');

$entities = elgg_get_entities_from_relationship_count([
	'type' => 'group',
	'relationship' => 'member',
	'inverse_relationship' => false,
	'limit' => 10,
]);

$data = [];
if ($entities) {
	foreach ($entities as $group) {
		$data[] = [
			'x' => $group->getDisplayName(),
			'y' => $group->getMembers(['count' => true]),
		];
	}
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
