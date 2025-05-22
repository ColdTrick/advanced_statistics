<?php

use Elgg\Database\Select;
use Elgg\Values;

$container_guid = elgg_extract('container_guid', $vars);

$result = advanced_statistics_get_default_chart_options('date');
$result['options']['plugins']['legend']['display'] = true;

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(r.time_created, '%Y-%m-%d') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->join('e', 'entity_relationships', 'r', 'e.guid = r.guid_one');
$qb->where($qb->compare('r.guid_two', '=', $container_guid, ELGG_VALUE_GUID));
$qb->andWhere($qb->compare('r.relationship', '=', 'member', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.type', '=', 'user', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.deleted', '=', 'no', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('r.time_created', '>', 0, ELGG_VALUE_INTEGER));

$total = 0;
$ts_limit = advanced_statistics_get_timestamp_query_part('r.time_created');
if (!empty($ts_limit)) {
	$ts_lower = get_input('ts_lower');
	if (!empty($ts_lower)) {
		// get the starting member count
		$count = clone $qb;
		$count->andWhere($count->compare('r.time_created', '<=', Values::normalizeTimestamp($ts_lower)));
		$count->select('count(*) as total');
		
		$row = elgg()->db->getDataRow($count);
		$total = (int) $row->total;
	}
	
	// add time limits
	$qb->andWhere($ts_limit);
}

$qb->groupBy("FROM_UNIXTIME(r.time_created, '%Y-%m-%d')");

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
$data2 = [];

foreach ($query_result as $row) {
	$date_total = (int) $row['total'];
	$total += $date_total;
	
	$data[] = [
		'x' => $row['date_created'],
		'y' => $date_total,
	];
	$data2[] = [
		'x' => $row['date_created'],
		'y' => $total,
	];
}

$result['data']['datasets'][] = [
	'label' => elgg_echo('admin:widget:new_users'),
	'data' => $data,
];
$result['data']['datasets'][] = [
	'label' => elgg_echo('total') . ' ' . strtolower(elgg_echo('item:user:user')),
	'data' => $data2,
];

echo json_encode($result);
