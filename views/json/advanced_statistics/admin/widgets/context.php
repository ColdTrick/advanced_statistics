<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select('md.value as context');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'metadata', 'md', 'e.guid = md.entity_guid');
$qb->where("md.name = 'context'");
$qb->andWhere("e.type = 'object'");
$qb->andWhere("e.subtype = 'widget'");
$qb->groupBy('md.value');
$qb->orderBy('total', 'desc');

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$context = $row['context'] ?: 'unknown';

		$data[] = [
			elgg_echo($context),
			(int) $row['total'],
		];
	}
}

$result['data'] = [$data];

echo json_encode($result);
