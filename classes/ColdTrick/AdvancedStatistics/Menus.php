<?php

namespace ColdTrick\AdvancedStatistics;

/**
 * Menu callbacks
 */
class Menus {
	
	/**
	 * Add a subscribe/unsubscribe link to the supported entity types
	 *
	 * @param \Elgg\Event $event 'register', 'menu:admin_header'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerAdminItems(\Elgg\Event $event) {
		
		if (!elgg_is_admin_logged_in()) {
			return;
		}
		
		$return_value = $event->getValue();
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'information:advanced_statistics',
			'text' => elgg_echo('admin:advanced_statistics'),
			'href' => false,
			'parent_name' => 'information',
		]);
		
		$sections = ['users', 'groups', 'content', 'activity', 'widgets', 'notifications', 'system'];
		foreach ($sections as $section) {
			$return_value[] = \ElggMenuItem::factory([
				'name' => "information:advanced_statistics:{$section}",
				'href' => "admin/advanced_statistics/{$section}",
				'text' => elgg_echo("admin:advanced_statistics:{$section}"),
				'parent_name' => 'information:advanced_statistics',
			]);
		}
		
		return $return_value;
	}
	
	/**
	 * Add a subscribe/unsubscribe link to the supported entity types
	 *
	 * @param \Elgg\Event $event 'register', 'menu:owner_block'
	 *
	 * @return void|\ElggMenuItem[]
	 */
	public static function registerGroupItems(\Elgg\Event $event) {
		
		if (elgg_get_plugin_setting('enable_group_stats', 'advanced_statistics') === 'no') {
			return;
		}
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggGroup || !$entity->canEdit()) {
			return;
		}
		
		$return_value = $event->getValue();
		
		$return_value[] = \ElggMenuItem::factory([
			'name' => 'advanced_statistics',
			'text' => elgg_echo('advanced_statistics:group:title'),
			'href' => elgg_generate_url('collection:advanced_statistics:group', [
				'guid' => $entity->guid,
			]),
		]);
		
		return $return_value;
	}
}
