<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$data = [];

// banned users

$qb = Select::fromTable('entities', 'e');
$qb->select('count(*) AS total');
$qb->join('e', 'metadata', 'md', 'e.guid = md.entity_guid');
$qb->where("e.type = 'user'");
$qb->andWhere("e.enabled = 'yes'");
$qb->andWhere("md.name = 'banned'");
$qb->andWhere("md.value = 'yes'");

$banned = (int) $qb->execute()->fetchFirstColumn();

$data[] = [
	"banned [{$banned}]",
	$banned,
];

// unvalidated users

$subquery = Select::fromTable('metadata', 'md');
$subquery->select('md.entity_guid');
$subquery->where("md.name = 'validated'");
$subquery->andWhere("md.value = '1'");

$qb = Select::fromTable('entities', 'e');
$qb->select('count(*) AS total');
$qb->where("e.type = 'user'");
$qb->andWhere("e.enabled = 'no'");
$qb->andWhere($qb->compare('e.guid', 'NOT IN', $subquery->getSQL()));

$unvalidated = (int) $qb->execute()->fetchFirstColumn();

$data[] = [
	"unvalidated [{$unvalidated}]",
	$unvalidated,
];

// disabled
$qb = Select::fromTable('entities', 'e');
$qb->select('count(*) AS total');
$qb->where("e.type = 'user'");
$qb->andWhere("e.enabled = 'no'");

$disabled = (int) $qb->execute()->fetchFirstColumn();

$disabled = $disabled - $unvalidated;

$data[] = [
	"disabled [{$disabled}]",
	$disabled,
];

// active
$qb = Select::fromTable('entities', 'e');
$qb->select('count(*) AS total');
$qb->where("e.type = 'user'");

$active = (int) $qb->execute()->fetchFirstColumn();
$active = $active - $disabled - $unvalidated - $banned;

$data[] = [
	"active [{$active}]",
	$active,
];

$result['data'] = [$data];

echo json_encode($result);
