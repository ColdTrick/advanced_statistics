<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('bar');

$week_ago = \Elgg\Values::normalizeTimestamp('-1 week');

$qb = Select::fromTable('river', 'r');
$qb->select('ge.guid');
$qb->addSelect('count(*) AS total');
$qb->join('r', 'entities', 'e', 'r.object_guid = e.guid');
$qb->join('e', 'entities', 'ge', 'e.container_guid = ge.guid');
$qb->where("e.enabled = 'yes'");
$qb->andWhere("e.deleted = 'no'");
$qb->andWhere("ge.enabled = 'yes'");
$qb->andWhere("ge.deleted = 'no'");
$qb->andWhere("ge.type = 'group'");
$qb->andWhere("r.posted > {$week_ago}");
$qb->groupBy('ge.guid');
$qb->orderBy('total', 'desc');
$qb->setMaxResults(10);

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];

foreach ($query_result as $row) {
	$data[] = [
		'x' => elgg_get_excerpt((string) get_entity($row['guid'])?->getDisplayName(), 25),
		'y' => (int) $row['total'],
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
