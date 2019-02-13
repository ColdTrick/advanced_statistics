<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select("SUBSTRING_INDEX(md.value, '@', -1) AS domain");
$qb->addSelect('count(*) AS total');
$qb->join('e', 'metadata', 'md', 'md.entity_guid = e.guid');
$qb->where("e.type = 'user'");
$qb->andWhere("md.name = 'email'");
$qb->groupBy("SUBSTRING_INDEX(md.value, '@', -1)");
$qb->orderBy('total', 'desc');
$qb->setMaxResults(10);

$query_result = $qb->execute()->fetchAll();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$total = (int) $row->total;
		$data[] = [
			$row->domain . " [" . $total . "]",
			$total,
		];
	}
}

$result['data'] = [$data];

echo json_encode($result);
