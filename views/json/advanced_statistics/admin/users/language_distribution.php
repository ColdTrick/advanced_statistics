<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('entities', 'e');
$md1 = $qb->joinMetadataTable('e', 'guid', 'language');

$qb->select("{$md1}.value as language");
$qb->addSelect('count(*) AS total');
$qb->where($qb->compare('e.type', '=', 'user', ELGG_VALUE_STRING));
$qb->where($qb->compare('e.enabled', '=', 'yes', ELGG_VALUE_STRING));
$qb->groupBy("{$md1}.value");
$qb->orderBy('total', 'desc');

if (!(bool) elgg_extract('include_banned_users', $vars, true)) {
	$md2 = $qb->joinMetadataTable('e', 'guid', 'banned');
	$qb->andWhere($qb->compare("{$md2}.value", '=', 'no', ELGG_VALUE_STRING));
}

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$total = (int) $row['total'];
		$data[] = [
			elgg_echo($row['language']) . " [{$total}]",
			$total,
		];
	}
}

$result['data'] = [$data];

echo json_encode($result);
