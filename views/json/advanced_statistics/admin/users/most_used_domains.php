<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('pie');

$qb = Select::fromTable('entities', 'e');
$md1 = $qb->joinMetadataTable('e', 'guid', 'email');

$qb->select("SUBSTRING_INDEX({$md1}.value, '@', -1) AS domain");
$qb->addSelect('count(*) AS total');
$qb->where($qb->compare('e.type', '=', 'user', ELGG_VALUE_STRING));
$qb->groupBy("SUBSTRING_INDEX({$md1}.value, '@', -1)");
$qb->orderBy('total', 'desc');
$qb->setMaxResults(10);

if (!(bool) elgg_extract('include_banned_users', $vars, true)) {
	$md2 = $qb->joinMetadataTable('e', 'guid', 'banned');
	$qb->andWhere($qb->compare("{$md2}.value", '=', 'no', ELGG_VALUE_STRING));
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
$labels = [];

foreach ($query_result as $row) {
	$labels[] = $row['domain'];
	$data[] = (int) $row['total'];
}

$result['data']['labels'] = $labels;
$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
