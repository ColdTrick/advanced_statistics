<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('pie');

$data = [];
$labels = [];

// banned users

$qb = Select::fromTable('entities', 'e');
$qb->select('count(*) AS total');
$qb->join('e', 'metadata', 'md', 'e.guid = md.entity_guid');
$qb->where("e.type = 'user'");
$qb->andWhere("e.enabled = 'yes'");
$qb->andWhere("md.name = 'banned'");
$qb->andWhere("md.value = 'yes'");

$banned = (int) $qb->execute()->fetchOne();

$labels[] = elgg_echo('banned');
$data[] = $banned;

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

$unvalidated = (int) $qb->execute()->fetchOne();

$labels[] = elgg_echo('unvalidated');
$data[] = $unvalidated;

// disabled
$qb = Select::fromTable('entities', 'e');
$qb->select('count(*) AS total');
$qb->where("e.type = 'user'");
$qb->andWhere("e.enabled = 'no'");

$disabled = (int) $qb->execute()->fetchOne();

$disabled = $disabled - $unvalidated;

$labels[] = elgg_echo('status:disabled');
$data[] = $disabled;

// active
$qb = Select::fromTable('entities', 'e');
$qb->select('count(*) AS total');
$qb->where("e.type = 'user'");

$active = (int) $qb->execute()->fetchOne();
$active = $active - $disabled - $unvalidated - $banned;

$labels[] = elgg_echo('status:active');
$data[] = $active;

$result['data']['labels'] = $labels;
$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
