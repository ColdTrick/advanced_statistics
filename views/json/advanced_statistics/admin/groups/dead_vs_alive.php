<?php

use Elgg\Database\Select;

$result = advanced_statistics_get_default_chart_options('pie');

$qb = Select::fromTable('river', 'r');
$qb->select('DISTINCT ge.guid');
$qb->joinEntitiesTable('r', 'object_guid', 'inner', 'e');
$qb->joinEntitiesTable('e', 'container_guid', 'inner', 'ge');
$qb->where($qb->compare('ge.type', '=', 'group', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('ge.enabled', '=', 'yes', ELGG_VALUE_STRING));
$qb->andWhere($qb->compare('ge.deleted', '=', 'no', ELGG_VALUE_STRING));

$data = [];
$labels = [];

// activity in last month
$month_qb = clone $qb;
$month_qb->andWhere($month_qb->compare('r.posted', '>=', \Elgg\Values::normalizeTimestamp('-30 days'), ELGG_VALUE_TIMESTAMP));

$total = $month_qb->execute()->rowCount();

$labels[] = elgg_echo('advanced_statistics:groups:dead_vs_alive:last_month');
$data[] = $total;

$previous_total = $total;

// activity in last 3 months
$three_month_qb = clone $qb;
$three_month_qb->andWhere($three_month_qb->compare('r.posted', '>=', \Elgg\Values::normalizeTimestamp('-90 days'), ELGG_VALUE_TIMESTAMP));

$total = $three_month_qb->execute()->rowCount();

$labels[] = elgg_echo('advanced_statistics:groups:dead_vs_alive:3_months');
$data[] = $total - $previous_total;

$previous_total = $total;

// activity in last 6 months
$six_month_qb = clone $qb;
$six_month_qb->andWhere($six_month_qb->compare('r.posted', '>=', \Elgg\Values::normalizeTimestamp('-180 days'), ELGG_VALUE_TIMESTAMP));

$total = $six_month_qb->execute()->rowCount();

$labels[] = elgg_echo('advanced_statistics:groups:dead_vs_alive:6_months');
$data[] = $total - $previous_total;

$previous_total = $total;

// activity in last year
$year_qb = clone $qb;
$year_qb->andWhere($year_qb->compare('r.posted', '>=', \Elgg\Values::normalizeTimestamp('-365 days'), ELGG_VALUE_TIMESTAMP));

$total = $year_qb->execute()->rowCount();

$labels[] = elgg_echo('advanced_statistics:groups:dead_vs_alive:year');
$data[] = $total - $previous_total;

$previous_total = $total;

// activity < last year
$dead_qb = clone $qb;

$total = $dead_qb->execute()->rowCount();

$labels[] = elgg_echo('advanced_statistics:groups:dead_vs_alive:more_year');
$data[] = $total - $previous_total;

$result['data']['labels'] = $labels;
$result['data']['datasets'][] = ['data' => $data];

echo json_encode($result);
