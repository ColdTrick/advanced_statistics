<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('bar');

$qb = Select::fromTable('entities', 'e');
$qb->select('ue.guid as user');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'entities', 'ue', 'e.owner_guid = ue.guid');
$qb->where("e.type = 'object'");
$qb->andWhere("ue.type = 'user'");
$qb->andWhere("ue.deleted = 'no'");
$qb->andWhere("e.deleted = 'no'");
$qb->andWhere($qb->compare('e.subtype', '=', ['file', 'images'], ELGG_VALUE_STRING));
$qb->groupBy('e.owner_guid');
$qb->orderBy('total', 'desc');
$qb->setMaxResults(25);

$query_result = $qb->execute()->fetchAllAssociative();

$data = [];
foreach ($query_result as $row) {
	$user = get_user($row['user']);
	if (!$user) {
		continue;
	}
	
	$data[] = [
		'x' => elgg_get_excerpt($user->getDisplayName(), 25),
		'y' => (int) $row['total'],
	];
}

$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
