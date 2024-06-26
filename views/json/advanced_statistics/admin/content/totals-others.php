<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('bar');

$searchable_subtypes = elgg_extract('object', elgg_entity_types_with_capability('searchable'), []);

$qb = Select::fromTable('entities', 'e');
$qb->select('e.subtype as subtype');
$qb->addSelect('count(*) AS total');
$qb->where("e.type = 'object'");
$qb->andWhere($qb->compare('e.subtype', '!=', $searchable_subtypes, ELGG_VALUE_STRING));
$qb->groupBy('e.subtype');
$qb->orderBy('total', 'desc');

$ts_limit = advanced_statistics_get_timestamp_query_part('e.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];

foreach ($query_result as $row) {
	$data[] = [
		'x' => elgg_language_key_exists("item:object:{$row['subtype']}") ? elgg_echo("item:object:{$row['subtype']}") : ucfirst($row['subtype']),
		'y' => (int) $row['total'],
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
