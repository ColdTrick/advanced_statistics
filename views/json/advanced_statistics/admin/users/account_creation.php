<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('date');
$result['options']['plugins']['legend']['display'] = true;

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(e.time_created, '%Y-%m-%d') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->where("e.type = 'user'");
$qb->andWhere('e.time_created > 0');
$qb->groupBy("FROM_UNIXTIME(e.time_created, '%Y-%m-%d')");
$qb->orderBy('date_created', 'ASC');

$ts_limit = advanced_statistics_get_timestamp_query_part('e.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
$data2 = [];

$total = 0;

foreach ($query_result as $row) {
	$date_total = (int) $row['total'];
	$total += $date_total;
	
	$data[] = ['x' => $row['date_created'], 'y' => $date_total];
	$data2[] = ['x' => $row['date_created'], 'y' => $total];
}

$result['data']['datasets'][] = [
	'label' => elgg_echo('admin:widget:new_users'),
	'data' => $data,
];
$result['data']['datasets'][] = [
	'label' => elgg_echo('total') . ' ' . strtolower(elgg_echo('collection:user:user')),
	'data' => $data2,
];

echo json_encode($result);
