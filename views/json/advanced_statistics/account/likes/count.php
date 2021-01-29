<?php

use Elgg\Database\Select;

$user = elgg_extract('user', $vars);

$result = [
	'options' => advanced_statistics_get_default_chart_options('date'),
];

$qb = Select::fromTable('annotations', 'a');
$qb->select("FROM_UNIXTIME(a.time_created, '%x-%v') AS yearweek");
$e = $qb->joinEntitiesTable('a', 'entity_guid');
$qb->addSelect('count(*) AS total');
$qb->where($qb->compare("{$e}.owner_guid", '=', $user->guid, ELGG_VALUE_GUID));
$qb->andWhere($qb->compare("a.name", '=', 'likes', ELGG_VALUE_STRING));
$qb->groupBy("FROM_UNIXTIME(a.time_created, '%x-%v')");
$qb->orderBy('yearweek', 'ASC');

$query_result = $qb->execute()->fetchAll();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		list ($year, $week) = explode('-', $row->yearweek);

		$data[] = [
			date('Y-m-d', strtotime("first monday of january {$year} + {$week} weeks")),
			(int) $row->total,
		];
	}
}
$result['data'] = [$data];

echo json_encode($result);
