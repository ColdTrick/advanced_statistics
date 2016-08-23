<?php
/**
 * Advanced Statistics
 */

require_once(dirname(__FILE__) . '/lib/functions.php');

// register default Elgg event
elgg_register_event_handler('init', 'system', 'advanced_statistics_init');

/**
 * initialization of plugin
 *
 * @return void
 */
function advanced_statistics_init() {
	// register page handler for nice URLs
	elgg_register_page_handler('advanced_statistics', 'advanced_statistics_page_handler');

	if (elgg_is_admin_logged_in()) {
		elgg_register_admin_menu_item('administer', 'users', 'advanced_statistics');
		elgg_register_admin_menu_item('administer', 'groups', 'advanced_statistics');
		elgg_register_admin_menu_item('administer', 'content', 'advanced_statistics');
		elgg_register_admin_menu_item('administer', 'activity', 'advanced_statistics');
		elgg_register_admin_menu_item('administer', 'widgets', 'advanced_statistics');
		elgg_register_admin_menu_item('administer', 'system', 'advanced_statistics');

		elgg_register_simplecache_view('css/advanced_statistics/jqplot');
		elgg_register_css('jquery.jqplot', elgg_get_simplecache_url('css', 'advanced_statistics/jqplot'));

		// register admin widgets
		elgg_register_widget_type('advanced_statistics', elgg_echo('advanced_statistics:widgets:advanced_statistics:title'), elgg_echo('advanced_statistics:widgets:advanced_statistics:description'), ['admin'], true);

		elgg_register_widget_type('online_user_count', elgg_echo('advanced_statistics:widgets:online_user_count:title'), elgg_echo('advanced_statistics:widgets:online_user_count:description'), ['admin']);
		
		elgg_register_ajax_view('widgets/online_user_count/content');
	}
}


/**
 * Handles the advanced statistics pages
 *
 * @param array $page requested page
 *
 * @return boolean
 */
function advanced_statistics_page_handler($page) {
	admin_gatekeeper();
	
	switch($page[0]){
		case 'users':
			echo advanced_statistics_get_users_data($page[1]);
			break;
		case 'groups':
			echo advanced_statistics_get_groups_data($page[1]);
			break;
		case 'activity':
			echo advanced_statistics_get_activity_data($page[1]);
			break;
		case 'content':
			echo advanced_statistics_get_content_data($page[1]);
			break;
		case 'system':
			echo advanced_statistics_get_system_data($page[1]);
			break;
		case 'widgets':
			echo advanced_statistics_get_widgets_data($page[1]);
			break;
		default:
			return false;
	}
	
	return true;
}