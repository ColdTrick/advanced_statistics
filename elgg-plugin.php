<?php

require_once(dirname(__FILE__) . '/lib/functions.php');

use ColdTrick\AdvancedStatistics\Bootstrap;

return [
	'bootstrap' => Bootstrap::class,
	'settings' => [
		'enable_group_stats' => 'no',
	],
	'views' => [
		'default' => [
			'js/jqplot/' => __DIR__ . '/vendors/jqplot',
		],
	],
	'widgets' => [
		'advanced_statistics' => [
			'context' => ['admin'],
			'multiple' => true,
		],
		'online_user_count' => [
			'context' => ['admin'],
		],
	],
];
