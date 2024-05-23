<?php

use Elgg\Database\Select;

$container_guid = elgg_extract('container_guid', $vars);

$result = advanced_statistics_get_default_chart_options('bar');

$searchable_subtypes = elgg_extract('object', elgg_entity_types_with_capability('searchable'), []);

$qb = Select::fromTable('entities', 'e');
$qb->select('e.subtype as subtype');
$qb->addSelect('count(*) AS total');
// searchable objects
$qb->where($qb->compare('e.type', '=', 'object', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.subtype', '=', $searchable_subtypes, ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.deleted', '=', 'no', ELGG_VALUE_STRING));
// in the group
$qb->andWhere($qb->compare('e.container_guid', '=', $container_guid, ELGG_VALUE_GUID));

// have river activtiy
$river_sub = $qb->subquery('river', 'r');
$river_sub->select('r.object_guid');

$qb->andWhere($qb->compare('e.guid', 'in', $river_sub->getSQL()));

// group and order
$qb->groupBy('e.subtype');
$qb->orderBy('total', 'desc');

// time limitation
$ts_limit = advanced_statistics_get_timestamp_query_part('e.time_created');
if (!empty($ts_limit)) {
	$qb->andWhere($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
foreach ($query_result as $row) {
	$label = $row['subtype'];
	$lan_key = "collection:object:{$row['subtype']}";
	if (!elgg_language_key_exists($lan_key)) {
		$lan_key = "item:object:{$row['subtype']}";
	}
	
	if (elgg_language_key_exists($lan_key)) {
		$label = elgg_echo($lan_key);
	}
	
	$data[] = [
		'x' => $label,
		'y' => (int) $row['total'],
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
