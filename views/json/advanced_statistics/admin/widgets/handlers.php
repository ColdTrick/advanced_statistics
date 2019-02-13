<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select('ps.value as handler');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'private_settings', 'ps', 'e.guid = ps.entity_guid');
$qb->where("ps.name = 'handler'");
$qb->andWhere("e.type = 'object'");
$qb->andWhere("e.subtype = 'widget'");
$qb->groupBy('ps.value');
$qb->orderBy('total', 'desc');

$query_result = $qb->execute()->fetchAll();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$data[] = [
			$row->handler,
			(int) $row->total,
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
