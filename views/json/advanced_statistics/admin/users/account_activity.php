<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('date');

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(e.last_action, '%Y-%m-01') AS month");
$qb->addSelect('count(*) AS total');
$qb->where("e.type = 'user'");
$qb->andWhere('e.last_action > 0');
$qb->groupBy("FROM_UNIXTIME(e.last_action, '%Y-%m')");

$ts_limit = advanced_statistics_get_timestamp_query_part('e.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];

foreach ($query_result as $row) {
	$data[] = [
		'x' => $row['month'],
		'y' => (int) $row['total'],
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
