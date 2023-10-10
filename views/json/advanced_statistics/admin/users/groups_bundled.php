<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select('e.guid');

$rel = $qb->subquery('entity_relationships', 'r');
$ge = $rel->joinEntitiesTable('r', 'guid_two');

$rel->select('count(*)')
	->where($qb->compare('r.guid_one', '=', 'e.guid'))
	->andWhere($qb->compare('r.relationship', '=', 'member', ELGG_VALUE_STRING))
	->andWhere($qb->compare("{$ge}.type", '=', 'group', ELGG_VALUE_STRING));

$qb->select("({$rel->getSQL()}) as total");

$qb->where($qb->compare('e.type', '=', 'user', ELGG_VALUE_STRING))
	->andWhere($qb->compare('e.enabled', '=', 'yes', ELGG_VALUE_STRING));

if (!(bool) elgg_extract('include_banned_users', $vars, true)) {
	$md = $qb->joinMetadataTable('e');
	$qb->andWhere($qb->compare("{$md}.name", '=', 'banned', ELGG_VALUE_STRING))
		->andWhere($qb->compare("{$md}.value", '=', 'no', ELGG_VALUE_STRING));
}

$data = [];

$havings = [
	'0' => 'total = 0',
	'<= 5' => 'total > 0 and total <= 5',
	'> 5 <= 10' => 'total > 5 and total <= 10',
	'> 10 <= 20' => 'total > 10 and total <= 20',
	'> 20' => 'total > 20',
];

foreach ($havings as $key => $having) {
	$temp_qb = $qb;
	$temp_qb->having($having);

	$db_result = $qb->executeQuery();
	$count = $db_result->rowCount();
	$data[] = [
		"{$key} [{$count}]",
		$count,
	];
}

$result['data'] = [$data];

echo json_encode($result);
