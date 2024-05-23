<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('pie');

$group_tools = elgg()->group_tools->all();

$data = [];
$labels = [];
$order = [];

foreach ($group_tools as $key => $tool) {
	$qb = Select::fromTable('entities', 'e');
	$qb->select('count(*) AS total');
	$qb->join('e', 'metadata', 'md', 'e.guid = md.entity_guid');
	$qb->where("md.name = '{$tool->name}_enable'");
	$qb->andWhere("e.type = 'group'");
	$qb->andWhere("e.enabled = 'yes'");
	$qb->andWhere("e.deleted = 'no'");
	$qb->andWhere("md.value = 'yes'");
	
	$query_result = $qb->execute()->fetchAllAssociative();
	foreach ($query_result as $row) {
		$order[$key] = (int) $row['total'];
		$data[$key] = (int) $row['total'];
		$labels[$key] = $tool->name;
	}
}

array_multisort($order, $data);
array_multisort($order, $labels);

$result['data']['labels'] = array_values($labels);
$result['data']['datasets'][] = ['data' => array_values($data)];

echo json_encode($result);
