<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

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
$qb->andWhere($qb->compare('e.subtype', '=', 'comment', ELGG_VALUE_STRING));
$qb->groupBy('ge.guid');
$qb->orderBy('total', 'desc');
$qb->setMaxResults(10);

$ts_limit = advanced_statistics_get_timestamp_query_part('e.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = array_reverse($qb->execute()->fetchAllAssociative()); // we want to show from large to small

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$data[] = [
			$row['total'],
			get_entity($row['guid'])->getDisplayName(),
		];
	}
}

$result['data'] = [$data];

$result['options']['seriesDefaults']['rendererOptions'] = [
	'barDirection' => 'horizontal',
];

$result['options']['seriesDefaults']['pointLabels'] = [
	'show' => false,
];

$result['options']['highlighter'] = [
	'show' => false
];

$result['options']['axes']['xaxis']['renderer'] = '$.jqplot.LinearAxisRenderer';
$result['options']['axes']['yaxis']['renderer'] = '$.jqplot.CategoryAxisRenderer';

echo json_encode($result);
