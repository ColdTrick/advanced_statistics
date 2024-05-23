<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('bar');

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(r.posted, '%k') AS hour_of_the_day");
$qb->addSelect('count(*) AS total');
$qb->join('e', 'river', 'r', 'e.guid = r.object_guid');
$qb->groupBy("FROM_UNIXTIME(r.posted, '%k')");

$ts_limit = advanced_statistics_get_timestamp_query_part('r.posted');
if ($ts_limit) {
	$qb->where($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative();

// make sure every hour is present
$data = [];
for ($i = 0; $i < 24; $i++) {
	$data[$i] = [
		'x' => "{$i}",
		'y' => 0,
	];
}

foreach ($query_result as $row) {
	$hotd = $row['hour_of_the_day'];
	
	$data[(int) $hotd] = [
		'x' => $hotd,
		'y' => (int) $row['total'],
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
