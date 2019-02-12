<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

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
			$group->getDisplayName(),
			$group->getMembers(['count' => true]),
		];
	}
}

$result['data'] = [$data];

$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CanvasAxisTickRenderer';
$result['options']['axes']['xaxis']['tickOptions'] = [
	'angle' => '-30',
	'fontSize' => '8pt',
];

echo json_encode($result);
