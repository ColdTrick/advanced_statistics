<?php

use Elgg\Database\Select;
use Elgg\Values;

$container_guid = elgg_extract('container_guid', $vars);

$result = [
	'options' => advanced_statistics_get_default_chart_options('date'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(r.time_created, '%Y-%m-%d') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->join('e', 'entity_relationships', 'r', 'e.guid = r.guid_one');
$qb->where($qb->compare('r.guid_two', '=', $container_guid, ELGG_VALUE_GUID));
$qb->andWhere($qb->compare('r.relationship', '=', 'member', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.type', '=', 'user', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('r.time_created', '>', 0, ELGG_VALUE_INTEGER));
$qb->groupBy("FROM_UNIXTIME(r.time_created, '%Y-%m-%d')");

$total = 0;
$ts_limit = advanced_statistics_get_timestamp_query_part('r.time_created');
if (!empty($ts_limit)) {
	$ts_lower = get_input('ts_lower');
	if (!empty($ts_lower)) {
		// get the starting member count
		$count = clone $qb;
		$count->andWhere($count->compare('r.time_created', '<=', Values::normalizeTimestamp($ts_lower)));
		$count->select('count(*) as total');
		$count->resetQueryPart('groupBy');
		
		$row = elgg()->db->getDataRow($count);
		$total = (int) $row->total;
	}
	
	// add time limits
	$qb->andWhere($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
$data2 = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$date_total = (int) $row['total'];
		$total += $date_total;
		
		$data[] = [
			$row['date_created'],
			$date_total,
		];
		$data2[] = [
			$row['date_created'],
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
