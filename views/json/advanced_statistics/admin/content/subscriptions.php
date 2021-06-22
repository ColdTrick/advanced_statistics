<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('date'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(r.time_created, '%Y-%m-%d') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->joinRelationshipTable('e', 'guid', null, false, 'inner', 'r');
$qb->where($qb->compare('r.relationship', 'like', 'notify:%', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('r.guid_one', '<>', 'e.owner_guid'));
$qb->groupBy("FROM_UNIXTIME(r.time_created, '%Y-%m-%d')");

$ts_limit = advanced_statistics_get_timestamp_query_part('r.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = elgg()->db->getData($qb);

$data = [];
foreach ($query_result as $row) {
	$data[] = [
		$row->date_created,
		(int) $row->total,
	];
}

$result['data'] = [$data];
$result['options']['series'] = [['showMarker' => false]];

echo json_encode($result);
