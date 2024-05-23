<?php

use Elgg\Database\Select;
use Elgg\Values;

$result = advanced_statistics_get_default_chart_options('date');
$result['options']['axes']['xaxis']['tickOptions']['formatString'] = '%Y-%m';

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(e.time_created, '%Y-%m') AS date_created");
$qb->addSelect('count(*) AS total');
$qb->where($qb->compare('e.type', '=', 'group', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.time_created', '>', 0, ELGG_VALUE_INTEGER));
$qb->groupBy("FROM_UNIXTIME(e.time_created, '%Y-%m')");
$qb->orderBy('date_created', 'ASC');

$query_result = elgg()->db->getData($qb);

$data = [];
$data2 = [];
if ($query_result) {
	$total = 0;
	
	foreach ($query_result as $row) {
		$date_total = (int) $row->total;
		$total += $date_total;
		
		$data[] = ['x' => $row->date_created, 'y' => $date_total];
		$data2[] = ['x' => $row->date_created, 'y' => $total];
	}
}

$result['data']['datasets'][] = [
	'label' => elgg_echo('advanced_statistics:groups:created:new'),
	'data' => $data,
];
$result['data']['datasets'][] = [
	'label' => elgg_echo('total') . ' ' . strtolower(elgg_echo('collection:group:group')),
	'data' => $data2,
];

echo json_encode($result);
