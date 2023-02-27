<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select('DAYOFWEEK(FROM_UNIXTIME(r.posted)) AS day_of_the_week');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'river', 'r', 'e.guid = r.object_guid');
$qb->groupBy('DAYOFWEEK(FROM_UNIXTIME(r.posted))');
$qb->orderBy('day_of_the_week', 'asc');

$ts_limit = advanced_statistics_get_timestamp_query_part('r.posted');
if ($ts_limit) {
	$qb->where($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$dotw = (int) $row['day_of_the_week'] - 1; // Mysql starts at 1, PHP at 0
		$dotw = elgg_echo("date:weekday:{$dotw}");
		
		$total = (int) $row['total'];
		$data[] = [
			$dotw . " [{$total}]",
			$total,
		];
	}
}

$result['data'] = [$data];

echo json_encode($result);
