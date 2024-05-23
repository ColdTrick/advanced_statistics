<?php

use Elgg\Database\Select;
use Elgg\Exceptions\Http\BadRequestException;

$user = elgg_extract('user', $vars);
$type = elgg_extract('type', $vars);
$subtype = elgg_extract('subtype', $vars);

if (empty($type) || empty($subtype)) {
	throw new BadRequestException();
}

$result = advanced_statistics_get_default_chart_options('bar');

$qb = Select::fromTable('entities', 'e');
$qb->select("FROM_UNIXTIME(e.time_created, '%Y') AS year");
$qb->addSelect('count(*) AS total');
$qb->where($qb->compare('e.owner_guid', '=', $user->guid, ELGG_VALUE_GUID));
$qb->andWhere($qb->compare('e.type', '=', $type, ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.subtype', '=', $subtype, ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('e.deleted', '=', 'no', ELGG_VALUE_STRING));
$qb->groupBy("FROM_UNIXTIME(e.time_created, '%Y')");
$qb->orderBy('year', 'ASC');

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
foreach ($query_result as $row) {
	$data[] = [
		'x' => (string) $row['year'],
		'y' => (int) $row['total'],
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
