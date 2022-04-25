<?php

use Elgg\Database\Select;
use Elgg\Notifications\SubscriptionsService;

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$qb = Select::fromTable('entity_relationships', 'r');
$qb->select('r.guid_one');
$qb->addSelect('count(*) AS total');
$qb->join('r', 'entities', 'e', 'r.guid_one = e.guid');
$qb->join('r', 'entities', 'e2', 'r.guid_two = e2.guid');
$qb->where("e.type = 'user'");
$qb->andWhere("e2.type = 'user' OR e2.type = 'group'");
$qb->groupBy('r.guid_one');

$data = [];
$ticks = [];

$data[] = elgg_count_entities(['type' => 'user']);
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

$result['data'] = [$data];

$result['options']['axes']['xaxis']['ticks'] = $ticks;
$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CanvasAxisTickRenderer';
$result['options']['axes']['xaxis']['tickOptions'] = [
	'angle' => '-70',
	'fontSize' => '8pt',
];

echo json_encode($result);
