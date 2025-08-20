<?php

require_once(dirname(__FILE__) . '/lib/functions.php');

$composer_path = '';
if (is_dir(__DIR__ . '/vendor')) {
	$composer_path = __DIR__ . '/';
}

use Elgg\Router\Middleware\GroupPageOwnerCanEditGatekeeper;

return [
	'plugin' => [
		'version' => '10.2',
	],
	'settings' => [
		'enable_group_stats' => 'no',
		'include_banned_users' => 1,
	],
	'actions' => [
		'advanced_statistics/export' => [],
	],
	'events' => [
		'register' => [
			'menu:admin_header' => [
				'\ColdTrick\AdvancedStatistics\Menus::registerAdminItems' => [],
			],
			'menu:owner_block' => [
				'\ColdTrick\AdvancedStatistics\Menus::registerGroupItems' => [],
			],
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
	'views' => [
		'default' => [
			'chartjs.mjs' => $composer_path . 'vendor/npm-asset/chart.js/dist/chart.umd.js',
		],
	],
	'view_extensions' => [
		'admin.css' => [
			'advanced_statistics/charts.css' => [],
		],
		'advanced_statistics/account/statistics/likes' => [
			'advanced_statistics/account/statistics/likes_graph' => [],
		],
		'core/settings/statistics' => [
			'advanced_statistics/account/statistics/likes' => [],
		],
		'elgg.css' => [
			'advanced_statistics/charts.css' => [],
		],
	],
	'view_options' => [
		'advanced_statistics/account/statistics/details' => ['ajax' => true],
		'advanced_statistics/account' => ['ajax' => true],
		'advanced_statistics/admin_data' => ['ajax' => true],
		'advanced_statistics/group_data' => ['ajax' => true],
	],
	'widgets' => [
		'advanced_statistics' => [
			'context' => ['admin'],
			'multiple' => true,
		],
	],
];
