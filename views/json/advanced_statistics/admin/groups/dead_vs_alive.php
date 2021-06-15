<?php

use Elgg\Database\Select;

$result = [
	'options' => advanced_statistics_get_default_chart_options('pie'),
];

$qb = Select::fromTable('river', 'r');
$qb->select('DISTINCT ge.guid');
$qb->join('r', 'entities', 'e' , 'r.object_guid = e.guid');
$qb->join('e', 'entities', 'ge' , 'e.container_guid = ge.guid');
$qb->where("ge.type = 'group'");
$qb->andWhere("ge.enabled = 'yes'");

// activity in last month
$month = time() - (30 * 24 * 60 * 60);

$month_qb = $qb;
$month_qb->andWhere("r.posted >= {$month}");

$total = $month_qb->execute()->rowCount();
$data[] = [
	elgg_echo("advanced_statistics:groups:dead_vs_alive:last_month", [$total]),
	$total,
];

// activity in last 3 months
$threemonth = time() - (90 * 24 * 60 * 60);

$three_month_qb = $qb;
$three_month_qb->andWhere("r.posted >= {$threemonth}");
$three_month_qb->andWhere($three_month_qb->compare('ge.guid', 'NOT IN', $month_qb->getSQL()));

$total = $three_month_qb->execute()->rowCount();
$data[] = [
	elgg_echo("advanced_statistics:groups:dead_vs_alive:3_months", [$total]),
	$total,
];

// activity in last 6 months
$sixmonth = time() - (180 * 24 * 60 * 60);

$six_month_qb = $qb;
$six_month_qb->andWhere("r.posted >= {$sixmonth}");
$six_month_qb->andWhere($six_month_qb->compare('ge.guid', 'NOT IN', $three_month_qb->getSQL()));

$total = $six_month_qb->execute()->rowCount();
$data[] = [
	elgg_echo("advanced_statistics:groups:dead_vs_alive:6_months", [$total]),
	$total,
];

// activity in last year
$year = time() - (365 * 24 * 60 * 60);

$year_qb = $qb;
$year_qb->andWhere("r.posted >= {$year}");
$year_qb->andWhere($year_qb->compare('ge.guid', 'NOT IN', $six_month_qb->getSQL()));

$total = $year_qb->execute()->rowCount();
$data[] = [
	elgg_echo("advanced_statistics:groups:dead_vs_alive:year", [$total]),
	$total,
];

// activity < last year
$dead_qb = $qb;
$dead_qb->andWhere("r.posted < {$year}");
$dead_qb->andWhere($dead_qb->compare('ge.guid', 'NOT IN', $year_qb->getSQL()));

$total = $dead_qb->execute()->rowCount();
$data[] = [
	elgg_echo("advanced_statistics:groups:dead_vs_alive:more_year", [$total]),
	$total,
];

$result['data'] = [$data];

$result['options']['seriesDefaults']['rendererOptions'] = ['varyBarColor' => true];
			
$result['options']['highlighter'] = [
	'show' => true,
	'sizeAdjust' => 7.5,
	'tooltipAxes' => 'y',
];
$result['options']['axes']['xaxis']['tickRenderer'] = '$.jqplot.CanvasAxisTickRenderer';
$result['options']['axes']['xaxis']['tickOptions'] = ['angle' => '-30', 'fontSize' => '8pt'];

echo json_encode($result);
