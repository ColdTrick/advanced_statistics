<?php

use Elgg\Database\Select;
use Elgg\Values;

$result = [
	'options' => advanced_statistics_get_default_chart_options('date'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(e.time_created, '%Y-%v') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->where($qb->compare('e.type', '=', 'group', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.time_created', '>', 0, ELGG_VALUE_INTEGER));
$qb->groupBy("FROM_UNIXTIME(e.time_created, '%Y-%v')");
$qb->orderBy('date_created', 'ASC');

$query_result = elgg()->db->getData($qb);

$data = [];
$data2 = [];
if ($query_result) {
	$total = 0;
	
	foreach ($query_result as $row) {
		$date_total = (int) $row->total;
		$total += $date_total;
		
		list($year, $week) = explode('-', $row->date_created);
		$dt = Values::normalizeTime(strtotime("{$year}W{$week}1"));
		
		$data[] = array($dt->format('Y-m-d'), $date_total);
		$data2[] = array($dt->format('Y-m-d'), $total);
	}
}

$result['data'] = [$data, $data2];

$result['options']['series'] = [
	[
		'showMarker' => false,
		'label' => elgg_echo('advanced_statistics:groups:created:new'),
	],
	[
		'showMarker' => false,
		'label' => elgg_echo('total') . ' ' . strtolower(elgg_echo('collection:group:group')),
		'yaxis' => 'y2axis',
	],
];
$result['options']['legend'] = [
	'show' => true,
	'position' => 'e',
];

echo json_encode($result);
