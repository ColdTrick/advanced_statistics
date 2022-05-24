<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('entity_relationships', 'r');
$qb->select('r.guid_one');
$qb->addSelect('count(*) AS total');
$e = $qb->joinEntitiesTable('r', 'guid_one');
$qb->where($qb->compare("{$e}.type", '=', 'user', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$e}.enabled", '=', 'yes', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('r.relationship', '=', 'friend', ELGG_VALUE_STRING));
$qb->groupBy('r.guid_one');

if (!(bool) elgg_extract('include_banned_users', $vars, true)) {
	$md2 = $qb->joinMetadataTable('r', 'guid_one', 'banned');
	$qb->andWhere($qb->compare("{$md2}.value", '=', 'no', ELGG_VALUE_STRING));
}

$data = [];

$havings = [
	'<= 10' => 'total <= 10',
	'> 10 <= 25' => 'total > 10 and total <= 25',
	'> 25 <= 100' => 'total > 25 and total <= 100',
	'> 100' => 'total > 100',
];

$total_user_count = elgg_count_entities(['type' => 'user']);

foreach ($havings as $key => $having) {
	$temp_qb = $qb;
	$temp_qb->having($having);
	
	$db_result = $qb->executeQuery();
	$count = $db_result->rowCount();
	$data[] = [
		"{$key} [{$count}]",
		$count,
	];
	$total_user_count -= $count;
}

array_unshift($data, [
	"0 [{$total_user_count}]",
	$total_user_count,
]);

$result['data'] = [$data];

echo json_encode($result);
