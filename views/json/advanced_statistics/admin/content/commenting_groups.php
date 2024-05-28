<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('bar');

$searchable_subtypes = elgg_extract('object', elgg_entity_types_with_capability('searchable'), []);
$key = array_search('comment', $searchable_subtypes);
if ($key !== false) {
	unset($searchable_subtypes[$key]);
}

$qb = Select::fromTable('entities', 'e');
$qb->select('ge.guid');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'entities', 'e2', 'e.container_guid = e2.guid');
$qb->join('e2', 'entities', 'ge', 'e2.container_guid = ge.guid');
$qb->where($qb->compare('ge.type', '=', 'group', ELGG_VALUE_STRING));
$qb->andWhere("ge.enabled = 'yes'");
$qb->andWhere("ge.deleted = 'no'");
$qb->andWhere($qb->compare('e.subtype', '=', 'comment', ELGG_VALUE_STRING));
$qb->groupBy('ge.guid');
$qb->orderBy('total', 'desc');
$qb->setMaxResults(10);

$ts_limit = advanced_statistics_get_timestamp_query_part('e.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative(); // we want to show from large to small

$data = [];
foreach ($query_result as $row) {
	$data[] = [
		'y' => get_entity((int) $row['guid'])->getDisplayName(),
		'x' => $row['total'],
	];
}

$result['options']['indexAxis'] = 'y';
$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
