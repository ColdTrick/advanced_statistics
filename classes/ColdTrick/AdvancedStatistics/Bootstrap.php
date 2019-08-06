<?php

namespace ColdTrick\AdvancedStatistics;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function init() {
		elgg_register_ajax_view('widgets/online_user_count/content');
		elgg_register_ajax_view('advanced_statistics/admin_data');
		elgg_register_ajax_view('advanced_statistics/group_data');
		
		elgg_register_plugin_hook_handler('register', 'menu:owner_block', '\ColdTrick\AdvancedStatistics\Menus::registerGroupItems');
		elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\AdvancedStatistics\Menus::registerAdminItems');
	}
}
