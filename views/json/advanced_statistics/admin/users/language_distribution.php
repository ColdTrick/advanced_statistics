<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('entities', 'e');
$qb->select('md.value as language');
$qb->addSelect('count(*) AS total');
$qb->join('e', 'metadata', 'md', 'md.entity_guid = e.guid');
$qb->where("e.type = 'user'");
$qb->andWhere("md.name = 'language'");
$qb->groupBy('md.value');

$query_result = $qb->execute()->fetchAll();

$data = [];
if ($query_result) {
	foreach ($query_result as $row) {
		$total = (int) $row->total;
		$data[] = [
			elgg_echo($row->language) . " [" . $total . "]",
			$total,
		];
	}
}

$result['data'] = [$data];

echo json_encode($result);
