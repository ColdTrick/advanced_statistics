<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$searchable_subtypes = elgg_extract('object', elgg_entity_types_with_capability('searchable'), []);

$qb = Select::fromTable('entities', 'e');
$qb->select('e2.type as type');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'entities', 'e2', 'e.container_guid = e2.guid');
$qb->where("e.type = 'object'");
$qb->andWhere($qb->compare('e.subtype', '=', $searchable_subtypes, ELGG_VALUE_STRING));
$qb->groupBy('e2.type');
$qb->orderBy('total', 'desc');

$ts_limit = advanced_statistics_get_timestamp_query_part('e.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$data[] = [
			$row['type'],
			(int) $row['total'],
		];
	}
}

$result['data'] = [$data];

echo json_encode($result);
