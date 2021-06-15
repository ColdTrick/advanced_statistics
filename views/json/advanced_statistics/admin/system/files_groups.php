<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select('ge.guid as group_guid');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'entities', 'ge', 'e.container_guid = ge.guid');
$qb->where("e.type = 'object'");
$qb->andWhere("ge.type = 'group'");
$qb->andWhere($qb->compare('e.subtype', '=', ['file', 'images'], ELGG_VALUE_STRING));
$qb->groupBy('e.container_guid');
$qb->orderBy('total', 'desc');
$qb->setMaxResults(25);

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$group = get_entity($row['group_guid']);
		if (!$group instanceof \ElggGroup) {
			continue;
		}
		
		$data[] = [
			elgg_get_excerpt($group->getDisplayName(), 25),
			(int) $row['total'],
		];
	}
}

$result['data'] = [$data];

$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CanvasAxisTickRenderer';
$result['options']['axes']['xaxis']['tickOptions'] = [
	'angle' => '-70',
	'fontSize' => '8pt',
];

echo json_encode($result);
