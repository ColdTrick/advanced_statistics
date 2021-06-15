<?php

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$entities = elgg_get_entities_from_relationship_count([
	'type' => 'user',
	'relationship' => 'friend',
	'inverse_relationship' => false,
	'limit' => 10,
]);

$data = [];
$ticks = [];
if ($entities) {
	foreach ($entities as $user) {
		$data[] = (int) $user->countEntitiesFromRelationship('friend', true);
		$ticks[] = elgg_get_excerpt($user->getDisplayName(), 25);
	}
}

$result['data'] = [$data];

$result['options']['axes']['xaxis']['ticks'] = $ticks;
$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CanvasAxisTickRenderer';
$result['options']['axes']['xaxis']['tickOptions'] = [
	'angle' => '-70',
	'fontSize' => '8pt',
];

echo json_encode($result);
