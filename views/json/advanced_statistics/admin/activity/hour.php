<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
	'data' => [],
];

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
	$data[$i] = [$i, 0];
}

if ($query_result) {
	foreach ($query_result as $row) {
		$hotd = $row['hour_of_the_day'];
		$total = (int) $row['total'];
		
		$data[(int) $hotd] = [$hotd, $total];
	}
}

$result['data'] = [$data];

echo json_encode($result);
