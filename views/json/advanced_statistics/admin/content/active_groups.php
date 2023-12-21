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
$qb->select('e.guid');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'entities', 'e2', 'e2.container_guid = e.guid');
$qb->where("e.type = 'group'");
$qb->andWhere($qb->compare('e2.subtype', 'IN', $searchable_subtypes, ELGG_VALUE_STRING));
$qb->groupBy('e.guid');
$qb->orderBy('total', 'desc');
$qb->setMaxResults(10);

$ts_limit = advanced_statistics_get_timestamp_query_part('e2.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = array_reverse($qb->execute()->fetchAllAssociative()); // we want to show from large to small

$groups = [];
foreach ($query_result as $row) {
	$groups[$row['guid']] = get_entity($row['guid'])->getDisplayName();
}

$data = [];
if ($query_result) {
	$series = [];
	foreach ($searchable_subtypes as $subtype) {
		$serie = [];
		
		$qb = Select::fromTable('entities', 'e');
		$qb->select('e.container_guid');
		$qb->addSelect('count(*) AS total');
		$qb->where($qb->compare('e.container_guid', 'IN', array_keys($groups), ELGG_VALUE_GUID));
		$qb->andWhere($qb->compare('e.type', '=', 'object', ELGG_VALUE_STRING));
		$qb->andWhere($qb->compare('e.subtype', '=', $subtype, ELGG_VALUE_STRING));
		$qb->groupBy('e.container_guid');
		
		$container_values = [];
		$container_results = $qb->execute()->fetchAllAssociative();
		if (empty($container_results)) {
			continue;
		}
		
		$series[] = ['label' => elgg_echo("collection:object:{$subtype}")];
		
		foreach ($container_results as $container_row) {
			$container_values[$container_row['container_guid']] = $container_row['total'];
		}
		
		// set in correct order
		foreach ($groups as $group_guid => $group_name) {
			$serie[] = elgg_extract($group_guid, $container_values, 0);
		}
		
		$data[] = $serie;
	}
}

$result['data'] = $data;

$result['options']['stackSeries'] = true;
$result['options']['seriesDefaults']['rendererOptions'] = [
	'barDirection' => 'horizontal',
];

$result['options']['seriesDefaults']['pointLabels'] = [
	'show' => true,
	'hideZeros' => true,
	'formatString' => '%d',
];
			
$result['options']['highlighter'] = [
	'show' => false
];
$result['options']['legend'] = [
	'show' => true,
	'location' => 'e',
];
$result['options']['series'] = $series;

$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CategoryAxisTickRenderer';
$result['options']['axes']['xaxis']['renderer'] = '$.jqplot.LinearAxisRenderer';
$result['options']['axes']['yaxis']['renderer'] = '$.jqplot.CategoryAxisRenderer';
$result['options']['axes']['yaxis']['ticks'] = array_values($groups);

echo json_encode($result);
