<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('entity_relationships', 'r');
$qb->select('r.guid_one');
$qb->addSelect('count(*) AS total');
$ue = $qb->joinEntitiesTable('r', 'guid_one');
$ge = $qb->joinEntitiesTable('r', 'guid_two');
$qb->where($qb->compare("{$ue}.type", '=', 'user', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$ue}.enabled", '=', 'yes', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$ge}.type", '=', 'group', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('r.relationship', '=', 'member', ELGG_VALUE_STRING));
$qb->groupBy('r.guid_one');

$base_options = [
	'type' => 'user',
	'metadata_name_value_pairs' => [],
];

if (!(bool) elgg_extract('include_banned_users', $vars, true)) {
	$banned = $qb->subquery('metadata');
	$banned->select('entity_guid')
		->where($qb->compare('name', '=', 'banned', ELGG_VALUE_STRING))
		->andWhere($qb->compare('value', '=', 'yes', ELGG_VALUE_STRING));
	
	$qb->andWhere($qb->compare('r.guid_one', 'NOT IN', $banned->getSQL()));
	
	$base_options['metadata_name_value_pairs'][] = [
		'name' => 'banned',
		'value' => 'no',
		'case_sensitive' => false,
	];
}

$data = [];

$havings = [
	'<= 5' => 'total <= 5',
	'> 5 <= 10' => 'total > 5 and total <= 10',
	'> 10 <= 20' => 'total > 10 and total <= 20',
	'> 20' => 'total > 20',
];

$total_user_count = elgg_count_entities($base_options);

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
