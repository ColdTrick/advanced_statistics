<?php

use Elgg\Database\Select;

$container_guid = elgg_extract('container_guid', $vars);

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select('e.subtype as subtype');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'river', 'r', 'e.guid = r.object_guid');
$qb->where("e.type = 'object'");
$qb->andWhere($qb->compare('e.subtype', '=', get_registered_entity_types('object'), ELGG_VALUE_STRING));
$qb->andWhere("e.container_guid = {$container_guid}");
$qb->groupBy('e.subtype');
$qb->orderBy('total', 'desc');

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$data[] = [
			elgg_echo("item:object:{$row['subtype']}"),
			(int) $row['total'],
		];
	}
}
$result['data'] = [$data];

$result['options']['seriesDefaults']['rendererOptions'] = [
	'varyBarColor' => true,
];
			
$result['options']['highlighter'] = [
	'show' => true,
	'sizeAdjust' => 7.5,
	'tooltipAxes' => 'y'
];
$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CanvasAxisTickRenderer';
$result['options']['axes']['xaxis']['tickOptions'] = [
	'angle' => '-30',
	'fontSize' => '8pt',
];

echo json_encode($result);
