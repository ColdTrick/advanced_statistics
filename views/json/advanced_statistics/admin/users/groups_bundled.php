<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('entity_relationships', 'r');
$qb->select('r.guid_one');
$qb->addSelect('count(*) AS total');
$qb->join('r', 'entities', 'e', 'r.guid_one = e.guid');
$qb->join('r', 'entities', 'e2', 'r.guid_two = e2.guid');
$qb->where("e.type = 'user'");
$qb->where("e2.type = 'group'");
$qb->andWhere("r.relationship = 'member'");
$qb->groupBy('r.guid_one');

$data = [];

$havings = [
	'<= 5' => 'total <= 5',
	'> 5 <= 10' => 'total > 5 and total <= 10',
	'> 10 <= 20' => 'total > 10 and total <= 20',
	'> 20' => 'total > 20',
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
