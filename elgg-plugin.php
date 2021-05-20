<?php

require_once(dirname(__FILE__) . '/lib/functions.php');

use ColdTrick\AdvancedStatistics\Bootstrap;
use Elgg\Router\Middleware\GroupPageOwnerCanEditGatekeeper;

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
	'routes' => [
		'collection:advanced_statistics:group' => [
			'path' => '/advanced_statistics/group/{guid}',
			'resource' => 'advanced_statistics/group',
			'middleware' => [
				GroupPageOwnerCanEditGatekeeper::class,
			],
		],
	],
	'view_extensions' => [
		'advanced_statistics/account/statistics/likes' => [
			'advanced_statistics/account/statistics/likes_graph' => [],
		],
		'core/settings/statistics' => [
			'advanced_statistics/account/statistics/likes' => [],
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
