<?php
 
	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/page_handlers.php");

	// register default Elgg event
	elgg_register_event_handler("init", "system", "advanced_statistics_init");
	
	function advanced_statistics_init(){
		// register page handler for nice URLs
		elgg_register_page_handler("advanced_statistics", "advanced_statistics_page_handler");
		
		if (elgg_is_admin_logged_in()) {
			elgg_register_admin_menu_item('administer', 'users', 'advanced_statistics');
			elgg_register_admin_menu_item('administer', 'groups', 'advanced_statistics');
			elgg_register_admin_menu_item('administer', 'content', 'advanced_statistics');
			elgg_register_admin_menu_item('administer', 'activity', 'advanced_statistics');
			elgg_register_admin_menu_item('administer', 'widgets', 'advanced_statistics');
			elgg_register_admin_menu_item('administer', 'system', 'advanced_statistics');
			
			elgg_extend_view("js/admin", "js/advanced_statistics/admin");
			
			$vendors = elgg_get_site_url() . "mod/advanced_statistics/vendors/";
			elgg_register_js("excanvas", $vendors . "jqplot/excanvas.min.js"); // only for IE < 9
			
			// jqplot base library
			elgg_register_js("jquery.jqplot", $vendors . "jqplot/jquery.jqplot.js");
			// jqplot plugins
			elgg_register_js("jquery.jqplot.barRenderer", $vendors . "jqplot/plugins/jqplot.barRenderer.js");
			elgg_register_js("jquery.jqplot.pieRenderer", $vendors . "jqplot/plugins/jqplot.pieRenderer.js");
			elgg_register_js("jquery.jqplot.canvasAxisTickRenderer", $vendors . "jqplot/plugins/jqplot.canvasAxisTickRenderer.js");
			elgg_register_js("jquery.jqplot.canvasTextRenderer", $vendors . "jqplot/plugins/jqplot.canvasTextRenderer.js");
			elgg_register_js("jquery.jqplot.categoryAxisRenderer", $vendors . "jqplot/plugins/jqplot.categoryAxisRenderer.js");
			elgg_register_js("jquery.jqplot.dateAxisRenderer", $vendors . "jqplot/plugins/jqplot.dateAxisRenderer.js");
			elgg_register_js("jquery.jqplot.pointLabels", $vendors . "jqplot/plugins/jqplot.pointLabels.js");
			elgg_register_js("jquery.jqplot.highlighter", $vendors . "jqplot/plugins/jqplot.highlighter.js");
			
			elgg_register_simplecache_view("css/advanced_statistics/jqplot");
			elgg_register_css("jquery.jqplot", elgg_get_simplecache_url("css", "advanced_statistics/jqplot"));
			
		}
	}
	