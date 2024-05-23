<?php

use Elgg\Database\Select;
use Elgg\Notifications\SubscriptionsService;

$result = advanced_statistics_get_default_chart_options('date');

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(r.time_created, '%Y-%m-%d') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->joinRelationshipTable('e', 'guid', null, true, 'inner', 'r');
$qb->where($qb->compare('r.relationship', '=', SubscriptionsService::MUTE_NOTIFICATIONS_RELATIONSHIP, ELGG_VALUE_STRING));
$qb->groupBy("FROM_UNIXTIME(r.time_created, '%Y-%m-%d')");

$ts_limit = advanced_statistics_get_timestamp_query_part('r.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = elgg()->db->getData($qb);

$data = [];
foreach ($query_result as $row) {
	$data[] = [
		'x' => $row->date_created,
		'y' => (int) $row->total,
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
