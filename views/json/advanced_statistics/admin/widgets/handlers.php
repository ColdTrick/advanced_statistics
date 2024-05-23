<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('bar');

$qb = Select::fromTable('entities', 'e');
$qb->select('md.value as handler');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'metadata', 'md', 'e.guid = md.entity_guid');
$qb->where("md.name = 'handler'");
$qb->andWhere("e.type = 'object'");
$qb->andWhere("e.subtype = 'widget'");
$qb->andWhere("e.deleted = 'no'");
$qb->groupBy('md.value');
$qb->orderBy('total', 'desc');

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
foreach ($query_result as $row) {
	$data[] = [
		'x' => $row['handler'],
		'y' => (int) $row['total'],
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
