<?php

use Elgg\Database\Select;

$container_guid = elgg_extract('container_guid', $vars);

$result = [
	'options' => advanced_statistics_get_default_chart_options('date'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(e.time_created, '%Y-%m-%d') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->where("e.container_guid = {$container_guid}");
$qb->groupBy("FROM_UNIXTIME(e.time_created, '%Y-%m-%d')");

$query_result = $qb->execute()->fetchAll();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$data[] = [
			$row->date_created,
			(int) $row->total,
		];
	}
}

$result['data'] = [$data];

$result['options']['series'] = [['showMarker' => false]];

echo json_encode($result);
