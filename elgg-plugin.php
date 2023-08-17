<?php

require_once(dirname(__FILE__) . '/lib/functions.php');

use Elgg\Router\Middleware\GroupPageOwnerCanEditGatekeeper;

return [
	'plugin' => [
		'version' => '9.0.2',
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
			'js/jqplot/' => __DIR__ . '/vendors/jqplot',
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
	'view_options' => [
		'widgets/online_user_count/content' => ['ajax' => true],
		'advanced_statistics/account/statistics/details' => ['ajax' => true],
		'advanced_statistics/account' => ['ajax' => true],
		'advanced_statistics/admin_data' => ['ajax' => true],
		'advanced_statistics/group_data' => ['ajax' => true],
		'css/advanced_statistics/jqplot' => ['simplecache' => true],
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
