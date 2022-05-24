<?php

use Elgg\Database\Select;
use Elgg\Notifications\SubscriptionsService;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$qb = Select::fromTable('entity_relationships', 'r');
$qb->select('r.guid_one');
$qb->addSelect('count(*) AS total');
$ue1 = $qb->joinEntitiesTable('r', 'guid_one');
$ue2 = $qb->joinEntitiesTable('r', 'guid_two');
$md2 = $qb->joinMetadataTable('r', 'guid_one', 'banned');
$qb->where($qb->compare("{$ue1}.type", '=', 'user', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$ue1}.enabled", '=', 'yes', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$ue2}.type", '=', 'user', ELGG_VALUE_STRING));
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

$data[] = $generic_result->rowCount();
$ticks[] = elgg_echo('advanced_statistics:notifications:generic_count');

$data[] = $specific_result->rowCount();
$ticks[] = elgg_echo('advanced_statistics:notifications:specific_count');

$result['data'] = [$data];

$result['options']['axes']['xaxis']['ticks'] = $ticks;
$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CanvasAxisTickRenderer';
$result['options']['axes']['xaxis']['tickOptions'] = [
	'angle' => '-70',
	'fontSize' => '8pt',
];

echo json_encode($result);
