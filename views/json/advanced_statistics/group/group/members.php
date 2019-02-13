<?php

use Elgg\Database\Select;

$container_guid = elgg_extract('container_guid', $vars);

$result = [
	'options' => advanced_statistics_get_default_chart_options('date'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(r.time_created, '%Y-%m-%d') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->join('e', 'entity_relationships', 'r', 'e.guid = r.guid_one');
$qb->where("r.guid_two = {$container_guid}");
$qb->andWhere("r.relationship = 'member'");
$qb->andWhere("e.type = 'user'");
$qb->andWhere("r.time_created > 0");
$qb->groupBy("FROM_UNIXTIME(r.time_created, '%Y-%m-%d')");

$query_result = $qb->execute()->fetchAll();

$data = [];
$data2 = [];
if ($query_result) {
	$total = 0;
	
	foreach ($query_result as $row) {
		$date_total = (int) $row->total;
		$total += $date_total;
		
		$data[] = [
			$row->date_created,
			$date_total,
		];
		$data2[] = [
			$row->date_created,
			$total,
		];
	}
}
$result['data'] = [$data, $data2];

$result['options']['series'] = [
	[
		'showMarker' => false,
		'label' => elgg_echo('admin:widget:new_users'),
	],
	[
		'showMarker' => false,
		'label' => elgg_echo('total') . ' ' . strtolower(elgg_echo('item:user:user')),
		'yaxis' => 'y2axis',
	],
];
$result['options']['legend'] = [
	'show' => true,
	'position' => 'e',
];

echo json_encode($result);
