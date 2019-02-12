<?php

namespace ColdTrick\AdvancedStatistics;

use Elgg\DefaultPluginBootstrap;

class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function init() {
		// register page handler for nice URLs
// 		elgg_register_page_handler('advanced_statistics', 'advanced_statistics_page_handler');
	
		elgg_register_simplecache_view('css/advanced_statistics/jqplot');
		elgg_register_css('jquery.jqplot', elgg_get_simplecache_url('css', 'advanced_statistics/jqplot'));

		elgg_register_ajax_view('widgets/online_user_count/content');
		elgg_register_ajax_view('advanced_statistics/admin_data');
		elgg_register_ajax_view('advanced_statistics/group_data');
		
		elgg_register_plugin_hook_handler('register', 'menu:owner_block', '\ColdTrick\AdvancedStatistics\Menus::registerGroupItems');
		elgg_register_plugin_hook_handler('register', 'menu:page', '\ColdTrick\AdvancedStatistics\Menus::registerAdminItems');
	}
}
