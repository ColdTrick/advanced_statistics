<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$group_tools = elgg()->group_tools->all();

$data = [];
$order = [];

foreach ($group_tools as $key => $tool) {
	$qb = Select::fromTable('entities', 'e');
	$qb->select('count(*) AS total');
	$qb->join('e', 'metadata', 'md', 'e.guid = md.entity_guid');
	$qb->where("md.name = '{$tool->name}_enable'");
	$qb->andWhere("e.type = 'group'");
	$qb->andWhere("e.enabled = 'yes'");
	$qb->andWhere("md.value = 'yes'");
	
	$query_result = $qb->execute()->fetchAllAssociative();

	if ($query_result) {
		foreach ($query_result as $row) {
			$total = (int) $row['total'];
			$order[$key] = $total;
			$data[$key] = [
				$tool->name . " [{$total}]",
				$total,
			];
		}
	}
}

array_multisort($order, $data);

$result['data'] = [array_values($data)];

echo json_encode($result);
