<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('pie');

$qb = Select::fromTable('entities', 'e');
$qb->select('md.value as context');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'metadata', 'md', 'e.guid = md.entity_guid');
$qb->where("md.name = 'context'");
$qb->andWhere("e.type = 'object'");
$qb->andWhere("e.subtype = 'widget'");
$qb->andWhere("e.deleted = 'no'");
$qb->groupBy('md.value');
$qb->orderBy('total', 'desc');

$query_result = $qb->execute()->fetchAllAssociative();

$labels = [];
$data = [];
foreach ($query_result as $row) {
	$context = $row['context'] ?: 'unknown';
	
	$labels[] = elgg_echo($context);
	$data[] = (int) $row['total'];
}

$result['data']['labels'] = $labels;
$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
