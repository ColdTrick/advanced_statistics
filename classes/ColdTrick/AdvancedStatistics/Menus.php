<?php

namespace ColdTrick\AdvancedStatistics;

class Menus {
	
	/**
	 * Add a subscribe/unsubscribe link to the supported entity types
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:page'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerAdminItems(\Elgg\Hook $hook) {
		
		if (!elgg_is_admin_logged_in() || !elgg_in_context('admin')) {
			return;
		}
		
		$return_value = $hook->getValue();
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'information:advanced_statistics',
			'text' => elgg_echo('admin:advanced_statistics'),
			'section' => 'information',
		]);
		
		$sections = ['users', 'groups', 'content', 'activity', 'widgets', 'system'];
		foreach ($sections as $section) {
			$return_value[] = \ElggMenuItem::factory([
				'name' => "information:advanced_statistics:{$section}",
				'href' => "admin/advanced_statistics/{$section}",
				'text' => elgg_echo("admin:advanced_statistics:{$section}"),
				'parent_name' => 'information:advanced_statistics',
				'section' => 'information',
			]);
		}
		
		return $return_value;
	}
	
	/**
	 * Add a subscribe/unsubscribe link to the supported entity types
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:owner_block'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerGroupItems(\Elgg\Hook $hook) {
		
		if (elgg_get_plugin_setting('enable_group_stats', 'advanced_statistics') === 'no') {
			return;
		}
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggGroup || !$entity->canEdit()) {
			return;
		}
		
		$return_value = $hook->getValue();
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'advanced_statistics',
			'href' => "advanced_statistics/group/{$entity->guid}",
			'text' => elgg_echo('advanced_statistics:group:title'),
		]);
		
		return $return_value;
	}
}
