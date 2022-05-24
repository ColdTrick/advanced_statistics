<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('private_settings', 'ps');
$qb->select('ps.value');
$qb->addSelect('count(*) AS total');
$e = $qb->joinEntitiesTable('ps', 'entity_guid');
$md2 = $qb->joinMetadataTable('ps', 'entity_guid', 'banned');
$qb->where($qb->compare("{$e}.type", '=', 'user', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$e}.enabled", '=', 'yes', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('ps.name', '=', 'delayed_email_interval', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare("{$md2}.value", '=', 'no', ELGG_VALUE_STRING));
$qb->groupBy('ps.value');
$qb->orderBy('total', 'desc');

$data = [];
$intervals = [];

$total_user_count = elgg_count_entities([
	'type' => 'user',
	'metadata_name_value_pairs' => [
		'name' => 'banned',
		'value' => 'no',
		'case_sensitive' => false,
	],
]);

$db_result = elgg()->db->getData($qb);
foreach ($db_result as $row) {
	$interval_count = (int) $row->total;
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
