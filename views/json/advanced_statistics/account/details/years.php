<?php

use Elgg\Database\Select;
use Elgg\BadRequestException;

$user = elgg_extract('user', $vars);
$type = elgg_extract('type', $vars);
$subtype = elgg_extract('subtype', $vars);

if (empty($type) || empty($subtype)) {
	throw new BadRequestException();
}

$result = [
	'options' => advanced_statistics_get_default_chart_options('bar'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(e.time_created, '%Y') AS year");
$qb->addSelect('count(*) AS total');
$qb->where($qb->compare('e.owner_guid', '=', $user->guid, ELGG_VALUE_GUID));
$qb->andWhere($qb->compare('e.type', '=', $type, ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.subtype', '=', $subtype, ELGG_VALUE_STRING));
$qb->groupBy("FROM_UNIXTIME(e.time_created, '%Y')");
$qb->orderBy('year', 'ASC');

$query_result = $qb->execute()->fetchAll();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$data[] = [
			$row->year,
			(int) $row->total,
		];
	}
}
$result['data'] = [$data];
$result['options']['series'] = [['showMarker' => false]];
$result['options']['axes']['yaxis']['tickOptions']['show'] = false;

echo json_encode($result);
