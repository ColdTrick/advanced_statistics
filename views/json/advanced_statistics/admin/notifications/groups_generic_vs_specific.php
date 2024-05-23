<?php

use Elgg\Database\Select;
use Elgg\Notifications\SubscriptionsService;

$result = advanced_statistics_get_default_chart_options('bar');

$qb = Select::fromTable('entity_relationships', 'r');
$qb->select('r.guid_one');
$qb->addSelect('count(*) AS total');
$ue = $qb->joinEntitiesTable('r', 'guid_one');
$ge = $qb->joinEntitiesTable('r', 'guid_two');
$md2 = $qb->joinMetadataTable('r', 'guid_one', 'banned');
$qb->where($qb->compare("{$ue}.type", '=', 'user', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$ue}.enabled", '=', 'yes', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$ge}.type", '=', 'group', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$md2}.value", '=', 'no', ELGG_VALUE_STRING));
$qb->groupBy('r.guid_one');

$specific = clone $qb;
$generic = clone $qb;

$generic_ors = [];
$specific_ors = [];

$methods = elgg_get_notification_methods();
foreach ($methods as $method) {
	$generic_ors[] = $generic->compare('r.relationship', '=', SubscriptionsService::RELATIONSHIP_PREFIX . ":{$method}", ELGG_VALUE_STRING);
	$specific_ors[] = $specific->compare('r.relationship', 'like', SubscriptionsService::RELATIONSHIP_PREFIX . ":%:{$method}", ELGG_VALUE_STRING);
}

$generic->andWhere($generic->merge($generic_ors, 'OR'));
$specific->andWhere($specific->merge($specific_ors, 'OR'));

$generic_result = $generic->executeQuery();
$specific_result = $specific->executeQuery();

$data = [];

$data[] = [
	'x' => elgg_echo('admin:statistics:label:numusers'),
	'y' => elgg_count_entities([
		'type' => 'user',
		'metadata_name_value_pairs' => [
			'name' => 'banned',
			'value' => 'no',
			'case_sensitive' => false,
		],
	]),
];

$data[] = [
	'x' => elgg_echo('advanced_statistics:notifications:generic_count'),
	'y' => $generic_result->rowCount(),
];

$data[] = [
	'x' => elgg_echo('advanced_statistics:notifications:specific_count'),
	'y' => $specific_result->rowCount(),
];

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
