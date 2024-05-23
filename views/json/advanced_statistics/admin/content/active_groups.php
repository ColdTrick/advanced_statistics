<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('bar');

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
$qb->andWhere("e.enabled = 'yes'");
$qb->andWhere("e.deleted = 'no'");
$qb->andWhere($qb->compare('e2.subtype', 'IN', $searchable_subtypes, ELGG_VALUE_STRING));
$qb->groupBy('e.guid');
$qb->orderBy('total', 'desc');
$qb->setMaxResults(10);

$ts_limit = advanced_statistics_get_timestamp_query_part('e2.time_created');
if ($ts_limit) {
	$qb->andWhere($ts_limit);
}

$query_result = $qb->execute()->fetchAllAssociative(); // we want to show from large to small

$labels = [];
$group_guids = [];

foreach ($query_result as $row) {
	$group_guids[] = $row['guid'];
	$labels[] = get_entity($row['guid'])->getDisplayName();
}

$result['data']['labels'] = $labels;

$data = [];
if ($query_result) {
	$series = [];
	foreach ($searchable_subtypes as $subtype) {
		$qb = Select::fromTable('entities', 'e');
		$qb->select('e.container_guid');
		$qb->addSelect('count(*) AS total');
		$qb->where($qb->compare('e.container_guid', 'IN', $group_guids, ELGG_VALUE_GUID));
		$qb->andWhere($qb->compare('e.type', '=', 'object', ELGG_VALUE_STRING));
		$qb->andWhere($qb->compare('e.subtype', '=', $subtype, ELGG_VALUE_STRING));
		$qb->groupBy('e.container_guid');
		
		$container_values = [];
		$container_results = $qb->execute()->fetchAllAssociative();
		if (empty($container_results)) {
			continue;
		}
		
		foreach ($container_results as $container_row) {
			$container_values[$container_row['container_guid']] = $container_row['total'];
		}
		
		// set in correct order
		$serie = [];
		foreach ($group_guids as $group_guid) {
			$serie[] = elgg_extract($group_guid, $container_values, 0);
		}
		
		$label = $subtype;
		if (elgg_language_key_exists("collection:object:{$subtype}")) {
			$label = elgg_echo("collection:object:{$subtype}");
		} elseif (elgg_language_key_exists("item:object:{$subtype}")) {
			$label = elgg_echo("item:object:{$subtype}");
		}
		
		$data[] = [
			'label' => $label,
			'data' => $serie,
		];
	}
}

$result['options']['indexAxis'] = 'y';
$result['options']['plugins']['legend']['display'] = true;
$result['options']['scales']['x']['stacked'] = true;
$result['options']['scales']['y']['stacked'] = true;

$result['data']['datasets'] = $data;

echo json_encode($result);
