<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$week_ago = time() - (7 * 24 * 60 * 60);

$qb = Select::fromTable('river', 'r');
$qb->select('md.value as name');
$qb->addSelect('count(*) AS total');
$qb->join('r', 'entities', 'e' , 'r.object_guid = e.guid');
$qb->join('e', 'entities', 'ge' , 'e.container_guid = ge.guid');
$qb->join('ge', 'metadata', 'md' , 'ge.guid = md.entity_guid');
$qb->where("e.enabled = 'yes'");
$qb->andWhere("ge.enabled = 'yes'");
$qb->andWhere("ge.type = 'group'");
$qb->andWhere("r.posted > {$week_ago}");
$qb->groupBy('md.value');
$qb->orderBy('total', 'desc');
$qb->setMaxResults(10);

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$data[] = [
			elgg_get_excerpt($row['name'], 25),
			(int) $row['total'],
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
