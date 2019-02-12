<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$qb = Select::fromTable('river', 'r');
$qb->select('md.value as name');
$qb->addSelect('count(*) AS total');
$qb->join('r', 'entities', 'e' , 'r.object_guid = e.guid');
$qb->join('e', 'entities', 'ge' , 'e.container_guid = ge.guid');
$qb->join('ge', 'metadata', 'md' , 'ge.guid = md.entity_guid');
$qb->where("e.enabled = 'yes'");
$qb->andWhere("ge.enabled = 'yes'");
$qb->andWhere("ge.type = 'group'");
$qb->groupBy('md.value');
$qb->orderBy('total', 'asc');
$qb->setMaxResults(10);

$query_result = $qb->execute()->fetchAll();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$data[] = [
			elgg_get_excerpt($row->name, 25),
			(int) $row->total,
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
