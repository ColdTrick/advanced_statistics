<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('private_settings', 'ps');
$qb->select('ps.value');
$qb->addSelect('count(*) AS total');
$qb->join('ps', 'entities', 'e', 'ps.entity_guid = e.guid');
$qb->where("e.type = 'user'");
$qb->andWhere("ps.name = 'delayed_email_interval'");
$qb->groupBy('ps.value');

$data = [];
$intervals = [];

$total_user_count = elgg_count_entities(['type' => 'user']);

$db_result = elgg()->db->getData($qb);
foreach ($db_result as $row) {
	$interval_count = (int) $row->total;;
	$intervals[$row->value] = $interval_count;
	
	$total_user_count -= $interval_count;
}

$daily_found = false;
foreach ($intervals as $interval => $count) {
	if ($interval === 'daily') {
		$daily_found = true;
		$count += $total_user_count;
	}
	
	$label = $interval;
	if (elgg_language_key_exists("interval:{$interval}")) {
		$label = elgg_echo("interval:{$interval}");
	}
	
	$data[] = [
		"{$label} [{$count}]",
		$count,
	];
}

if (!$daily_found) {
	$data[] = [
		elgg_echo('interval:daily') . " {$total_user_count}",
		$total_user_count,
	];
}

$result['data'] = [$data];

echo json_encode($result);
