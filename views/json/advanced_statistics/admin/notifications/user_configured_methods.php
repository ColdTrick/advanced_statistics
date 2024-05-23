<?php

use Elgg\Database\Select;
use Elgg\Notifications\SubscriptionsService;

$result = advanced_statistics_get_default_chart_options('bar');

$qb = Select::fromTable('entity_relationships', 'r');
$qb->select('r.guid_one');
$qb->addSelect('count(*) AS total');
$ue = $qb->joinEntitiesTable('r', 'guid_one');
$oe = $qb->joinEntitiesTable('r', 'guid_two');
$md2 = $qb->joinMetadataTable('r', 'guid_one', 'banned');
$qb->where($qb->compare("{$ue}.type", '=', 'user', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$ue}.enabled", '=', 'yes', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$md2}.value", '=', 'no', ELGG_VALUE_STRING));
$qb->andWhere($qb->merge([
	$qb->compare("{$oe}.type", '=', 'user', ELGG_VALUE_STRING),
	$qb->compare("{$oe}.type", '=', 'group', ELGG_VALUE_STRING),
], 'OR'));
$qb->groupBy('r.guid_one');

$data = [];
$ticks = [];

$data[] = elgg_count_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => [
		'name' => 'banned',
		'value' => 'no',
		'case_sensitive' => false,
	],
]);
$ticks[] = elgg_echo('admin:statistics:label:numusers');

$methods = elgg_get_notification_methods();
foreach ($methods as $method) {
	$temp_qb = clone $qb;
	
	$ors = [
		$temp_qb->compare('r.relationship', '=', SubscriptionsService::RELATIONSHIP_PREFIX . ":{$method}", ELGG_VALUE_STRING),
		$temp_qb->compare('r.relationship', 'like', SubscriptionsService::RELATIONSHIP_PREFIX . ":%:{$method}", ELGG_VALUE_STRING),
	];
	$temp_qb->andWhere($temp_qb->merge($ors, 'OR'));
	
	$db_result = $temp_qb->executeQuery();
	
	$data[] = $db_result->rowCount();
	$ticks[] = elgg_echo("notification:method:{$method}");
}

$result['data']['labels'] = $ticks;
$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
