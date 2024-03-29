<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select('md.value as handler');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'metadata', 'md', 'e.guid = md.entity_guid');
$qb->where("md.name = 'handler'");
$qb->andWhere("e.type = 'object'");
$qb->andWhere("e.subtype = 'widget'");
$qb->groupBy('md.value');
$qb->orderBy('total', 'desc');

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$data[] = [
			$row['handler'],
			(int) $row['total'],
		];
	}
}

$result['data'] = [$data];

$result['options']['seriesDefaults']['rendererOptions'] = [
	'barMargin' => '2',
];
$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CanvasAxisTickRenderer';
$result['options']['axes']['xaxis']['tickOptions'] = [
	'angle' => '-70',
	'fontSize' => '8pt',
];

echo json_encode($result);
