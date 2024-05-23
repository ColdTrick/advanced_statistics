<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('date');

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(r.posted, '%Y-%m-%d') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->join('e', 'river', 'r', 'e.guid = r.object_guid');
$qb->groupBy("FROM_UNIXTIME(r.posted, '%Y-%m-%d')");

$ts_limit = advanced_statistics_get_timestamp_query_part('r.posted');
if ($ts_limit) {
	$qb->where($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
foreach ($query_result as $row) {
	$data[] = [
		'x' => $row['date_created'],
		'y' => (int) $row['total'],
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
