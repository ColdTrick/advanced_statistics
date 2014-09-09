<?php

	function advanced_statistics_page_handler($page){
		admin_gatekeeper();
		
		switch($page[0]){
			case "users":
				echo advanced_statistics_get_users_data($page[1]);
				break;
			case "groups":
				echo advanced_statistics_get_groups_data($page[1]);
				break;
			case "activity":
				echo advanced_statistics_get_activity_data($page[1]);
				break;
			case "content":
				echo advanced_statistics_get_content_data($page[1]);
				break;
			case "system":
				echo advanced_statistics_get_system_data($page[1]);
				break;
			case "widgets":
				echo advanced_statistics_get_widgets_data($page[1]);
				break;
			default:
				return false;
				break;
		}
		
		return true;
	}