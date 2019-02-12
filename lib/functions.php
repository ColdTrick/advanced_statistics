<?php
/**
 * Functions for Advanced Statistics
 */

/**
 * Handles the advanced statistics pages
 *
 * @param array $page requested page
 *
 * @return boolean
 */
function advanced_statistics_page_handler($page) {
	
	if ($page[0] === 'group') {
		if (elgg_get_plugin_setting('enable_group_stats', 'advanced_statistics') !== 'yes') {
			return false;
		}
		
		if (is_numeric($page[1])) {
			echo elgg_view_resource('advanced_statistics/group');
		} else {
			echo advanced_statistics_get_group_data($page[1]);
		}
		return true;
	}
	
	admin_gatekeeper();
	
	switch($page[0]){
		case 'users':
			echo advanced_statistics_get_users_data($page[1]);
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

/**
 * Returns data for a given chart id
 *
 * @param string $chart_id chart id
 *
 * @return string
 */
function advanced_statistics_get_users_data($chart_id) {
	$result = array("data" => array(), "options" => array());
	
	$dbprefix = elgg_get_config("dbprefix");
	$current_site_guid = elgg_get_site_entity()->getGUID();
	
	$ts_limit = advanced_statistics_get_timestamp_query_part('e.time_created');
	
	switch($chart_id){
		case "language-distribution":
			$data = array();
			
			$query = "SELECT ue.language, count(*) AS total";
			$query .= " FROM " . $dbprefix . "users_entity ue";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = ue.guid";
			$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
			$query .= " GROUP BY language";
			
			if ($query_result = get_data($query)) {
				foreach ($query_result as $row) {
					$language = $row->language;
					if (empty($language)) {
						$language = "unknown";
					}
					$total = (int) $row->total;
					$data[] = array(elgg_echo($language) . " [" . $total . "]"  , $total);
				}
			}
			
			$result["data"] = array($data);
			$result["options"] = advanced_statistics_get_default_chart_options("pie");
			
			break;
		case "account-creation":
			$data = array();
			$data2 = array();
			
			$query = "SELECT FROM_UNIXTIME(r.time_created, '%Y-%m-%d') AS date_created, count(*) AS total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
			$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
			$query .= " AND e.type = 'user'";
			$query .= " AND r.time_created > 0";
			if ($ts_limit) {
				$query .= " AND {$ts_limit}";
			}
			$query .= " GROUP BY FROM_UNIXTIME(r.time_created, '%Y-%m-%d')";
			
			if ($query_result = get_data($query)) {
				$total = 0;
				
				foreach ($query_result as $row) {
					$date_total = (int) $row->total;
					$total += $date_total;
					
					$data[] = array($row->date_created , $date_total);
					$data2[] = array($row->date_created , $total);
				}
			}
			
			$result["data"] = array($data, $data2);
			$result["options"] = advanced_statistics_get_default_chart_options("date");
			$result["options"]["series"] = array(
				array("showMarker" => false, "label" => elgg_echo("admin:widget:new_users")),
				array("showMarker" => false, "label" => elgg_echo("total") . " " . strtolower(elgg_echo("item:user")), "yaxis" => "y2axis")
			);
			$result["options"]["legend"] = array("show" => true, "position" => "e");
			
			break;
		case "most-used-domains":
			$data = array();
			
			$query = "SELECT SUBSTRING_INDEX(ue.email, '@', -1) AS domain, count(*) AS total";
			$query .= " FROM " . $dbprefix . "users_entity ue";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = ue.guid";
			$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
			$query .= " GROUP BY SUBSTRING_INDEX(ue.email, '@', -1) ORDER BY total DESC LIMIT 0,10";
			
			if ($query_result = get_data($query)) {
				foreach ($query_result as $row) {
					$total = (int) $row->total;
					$data[] = array($row->domain . " [" . $total . "]"  , $total);
				}
			}
			
			$result["data"] = array($data);
			$result["options"] = advanced_statistics_get_default_chart_options("pie");
			
			break;
		case "account-activity":
			$data = array();
			
			$query = "SELECT FROM_UNIXTIME(e.last_action, '%Y-%m-01') AS month, count(*) AS total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
			$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
			$query .= " AND e.type = 'user' AND e.last_action > 0";
			if ($ts_limit) {
				$query .= " AND {$ts_limit}";
			}
			$query .= " GROUP BY FROM_UNIXTIME(e.last_action, '%Y-%m')";
			
			if ($query_result = get_data($query)) {
				foreach ($query_result as $row) {
					$total = (int) $row->total;
					$data[] = array($row->month, $total);
				}
			}
			
			$result["data"] = array($data);
			$result["options"] = advanced_statistics_get_default_chart_options("date");
			
			break;
		case "account-status":
			$data = array();
			
			// banned users
			$query = "SELECT count(*) AS total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "users_entity ue ON e.guid = ue.guid";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
			$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
			$query .= " AND e.type = 'user' AND ue.banned = 'yes' AND e.enabled = 'yes'";
			
			if ($query_result = get_data_row($query)) {
				$banned = (int) $query_result->total;
				
				$data[] = array("banned [" . $banned . "]", $banned);
			}
			
			// unvalidated
			
			$validated_id = elgg_get_metastring_id('validated');
			$one_id = elgg_get_metastring_id('1');
			$unvalidated = 0;
			$disabled = 0;
			$banned = 0;
			
			$query = "SELECT count(*) AS total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
			$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
			$query .= " AND e.type = 'user' AND e.enabled = 'no'";
			$query .= " AND NOT EXISTS (
						SELECT 1 FROM " . $dbprefix . "metadata md
						WHERE md.entity_guid = e.guid
							AND md.name_id = $validated_id
							AND md.value_id = $one_id)";
			
			if ($query_result = get_data_row($query)) {
				$unvalidated = (int) $query_result->total;
					
				$data[] = array("unvalidated [" . $unvalidated . "]", $unvalidated);
			}
			
			// disabled
			$query = "SELECT count(*) AS total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
			$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
			$query .= " AND e.type = 'user' AND e.enabled = 'no'";
			
			if ($query_result = get_data_row($query)) {
				$disabled = (int) $query_result->total;
				$disabled = $disabled - $unvalidated;
					
				$data[] = array("disabled [" . $disabled . "]", $disabled);
			}
			
			// total
			$query = "SELECT count(*) AS total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
			$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
			$query .= " AND e.type = 'user'";
			
			if ($query_result = get_data_row($query)) {
				$active = (int) $query_result->total;
				$active = $active - $disabled - $unvalidated - $banned;
					
				$data[] = array("active [" . $active . "]", $active);
			}
			
			$result["data"] = array($data);
			$result["options"] = advanced_statistics_get_default_chart_options("pie");
			
			break;
		case "profile-field-usage":
			$data = array();
			$ticks = array();
			
			if ($profile_fields = elgg_get_config("profile_fields")) {
				$total_users_count = 0;
				$empty_id = elgg_get_metastring_id("");
				
				// total for this field
				$query = "SELECT count(*) AS total";
				$query .= " FROM " . $dbprefix . "entities e";
				$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
				$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
				$query .= " AND e.type = 'user'";
					
				if ($query_result = get_data_row($query)) {
					$total_users_count = (int) $query_result->total;
				}
				
				foreach ($profile_fields as $field_name => $type) {
					$name_id = elgg_get_metastring_id($field_name);
					
					// total for this field
					$query = "SELECT count(distinct e.guid) AS total";
					$query .= " FROM " . $dbprefix . "entities e";
					$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
					$query .= " JOIN " . $dbprefix . "metadata md ON e.guid = md.entity_guid";
					$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
					$query .= " AND e.type = 'user'";
					$query .= " AND md.name_id = '" . $name_id . "'";
					$query .= " AND md.value_id <> " . $empty_id;
						
					if ($query_result = get_data_row($query)) {
						$total = (int) $query_result->total;
						
						$ticks[] = elgg_get_excerpt(elgg_echo("profile:" . $field_name), 25);
						$data[] = round(($total * 100) / $total_users_count);
					}
					
				}
			}
			
			$result["data"] = array($data);
			
			$options = advanced_statistics_get_default_chart_options("bar");
			$options["axes"]["xaxis"]["ticks"] = $ticks;
			$options["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
			$options["axes"]["xaxis"]["tickOptions"] = array("angle" => "-30", "fontSize" => "8pt");
			$options["axes"]["yaxis"] = array("tickOptions" => array("formatString" => "%d%"));
				
			$result["options"] = $options;
			
			break;
		case "popular":
			$data = array();
			$ticks = array();
			
			$query = "SELECT ue.name, count(*) AS total";
			$query .= " FROM " . $dbprefix . "users_entity ue";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON ue.guid = r.guid_one";
			$query .= " JOIN " . $dbprefix . "entity_relationships r2 ON ue.guid = r2.guid_one";
			$query .= " JOIN " . $dbprefix . "entities e ON ue.guid = e.guid";
			$query .= " WHERE r.relationship = 'friend'";
			$query .= " AND r2.relationship = 'member_of_site' AND r2.guid_two = " . $current_site_guid;
			$query .= " AND e.enabled = 'yes' AND ue.banned = 'no'";
			$query .= " GROUP BY ue.name";
			$query .= " ORDER BY total desc";
			$query .= " LIMIT 0, 10";
			
			if ($query_result = get_data($query)) {
				foreach ($query_result as $row) {
					$data[] = (int) $row->total;
					$ticks[] = elgg_get_excerpt($row->name, 25);
				}
			}
			
			$result["data"] = array($data);
			
			$options = advanced_statistics_get_default_chart_options("bar");
			$options["axes"]["xaxis"]["ticks"] = $ticks;
			$options["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
			$options["axes"]["xaxis"]["tickOptions"] = array("angle" => "-70", "fontSize" => "8pt");
			
			$result["options"] = $options;
			break;
		default:
			$params = array(
				"chart_id" => $chart_id,
				"default_result" => $result
			);
			
			$result = elgg_trigger_plugin_hook("users", "advanced_statistics", $params, $result);
			break;
	}
	
	return json_encode($result);
}

/**
 * Returns data for a given chart id
 *
 * @param string $chart_id chart id
 *
 * @return string
 */
function advanced_statistics_get_widgets_data($chart_id) {
	$result = array("data" => array(), "options" => array());

	$dbprefix = elgg_get_config("dbprefix");
	$current_site_guid = elgg_get_site_entity()->getGUID();

	switch ($chart_id) {
		case "handlers":
			$data = array();

			$widget_subtype = get_subtype_id("object", "widget");
			
			$query = "SELECT ps.value as handler, count(*) as total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "private_settings ps ON e.guid = ps.entity_guid";
			$query .= " WHERE e.type = 'object' AND e.subtype = " . $widget_subtype;
			$query .= " AND ps.name = 'handler'";
			$query .= " AND e.site_guid = " . $current_site_guid;
			$query .= " GROUP BY ps.value";
			$query .= " ORDER BY total DESC";
				
			if ($query_result = get_data($query)) {
				foreach ($query_result as $row) {
					$handler = $row->handler;
		
					$total = (int) $row->total;
					$data[] = array($handler, $total);
				}
			}
				
			$result["data"] = array($data);
			$result["options"] = advanced_statistics_get_default_chart_options("bar");
			
			$result["options"]["seriesDefaults"]["rendererOptions"] = array("barMargin" => "2");
			$result["options"]["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
			$result["options"]["axes"]["xaxis"]["tickOptions"] = array("angle" => "-70", "fontSize" => "8pt");
			
			break;
		case "context":
			$data = array();

			$widget_subtype = get_subtype_id("object", "widget");
			
			$query = "SELECT ps.value as context, count(*) as total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "private_settings ps ON e.guid = ps.entity_guid";
			$query .= " WHERE e.type = 'object' AND e.subtype = " . $widget_subtype;
			$query .= " AND ps.name = 'context'";
			$query .= " AND e.site_guid = " . $current_site_guid;
			$query .= " GROUP BY ps.value";
			$query .= " ORDER BY total DESC";
				
			if ($query_result = get_data($query)) {
				foreach ($query_result as $row) {
					$context = $row->context;
					if (!$context) {
						$context = elgg_echo("unknown");
					} else {
						$context = elgg_echo($context);
					}
					$total = (int) $row->total;
					$data[] = array($context, $total);
				}
			}
				
			$result["data"] = array($data);
			$result["options"] = advanced_statistics_get_default_chart_options("pie");
			
			
			break;
		default:
			$params = array(
				"chart_id" => $chart_id,
				"default_result" => $result
			);

			$result = elgg_trigger_plugin_hook("widgets", "advanced_statistics", $params, $result);
			break;
	}

	return json_encode($result);
}

/**
 * Returns data for a given chart id
 *
 * @param string $chart_id chart id
 *
 * @return string
 */
function advanced_statistics_get_system_data($chart_id) {
	$result = array("data" => array(), "options" => array());

	$dbprefix = elgg_get_config("dbprefix");
	$current_site_guid = elgg_get_site_entity()->getGUID();

	switch ($chart_id) {
		case "files-users":
			$data = array();
		
			$subtype_ids = array();
			if ($subtype_id = get_subtype_id("object", "file")) {
				$subtype_ids[] = $subtype_id;
			}
			if ($subtype_id = get_subtype_id("object", "images")) {
				$subtype_ids[] = $subtype_id;
			}
		
			if (!empty($subtype_ids)) {
			
				$query = "SELECT ue.name as user, count(*) as total";
				$query .= " FROM " . $dbprefix . "entities e";
				$query .= " JOIN " . $dbprefix . "users_entity ue ON e.owner_guid = ue.guid";
				$query .= " WHERE e.type = 'object' AND e.subtype IN (" . implode(", ", $subtype_ids) . ")";
				$query .= " AND e.site_guid = " . $current_site_guid;
				$query .= " GROUP BY e.owner_guid";
				$query .= " ORDER BY total DESC";
				$query .= " LIMIT 0, 25";
					
				if ($query_result = get_data($query)) {
					foreach ($query_result as $row) {
						$user = $row->user;
							
						$total = (int) $row->total;
						$data[] = array(elgg_get_excerpt($user, 25), $total);
					}
				}
					
				$result["data"] = array($data);
				$result["options"] = advanced_statistics_get_default_chart_options("bar");
			
				$result["options"]["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
				$result["options"]["axes"]["xaxis"]["tickOptions"] = array("angle" => "-70", "fontSize" => "8pt");
			}
			break;
		case "files-groups":
			$data = array();
		
			$subtype_ids = array();
			if ($subtype_id = get_subtype_id("object", "file")) {
				$subtype_ids[] = $subtype_id;
			}
			if ($subtype_id = get_subtype_id("object", "images")) {
				$subtype_ids[] = $subtype_id;
			}
		
			if (!empty($subtype_ids)) {
			
				$query = "SELECT ge.name as user, count(*) as total";
				$query .= " FROM " . $dbprefix . "entities e";
				$query .= " JOIN " . $dbprefix . "groups_entity ge ON e.container_guid = ge.guid";
				$query .= " WHERE e.type = 'object' AND e.subtype IN (" . implode(", ", $subtype_ids) . ")";
				$query .= " AND e.site_guid = " . $current_site_guid;
				$query .= " GROUP BY e.container_guid";
				$query .= " ORDER BY total DESC";
				$query .= " LIMIT 0, 25";
					
				if ($query_result = get_data($query)) {
					foreach ($query_result as $row) {
						$user = $row->user;
							
						$total = (int) $row->total;
						$data[] = array(elgg_get_excerpt($user, 25), $total);
					}
				}
					
				$result["data"] = array($data);
				$result["options"] = advanced_statistics_get_default_chart_options("bar");
			
				$result["options"]["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
				$result["options"]["axes"]["xaxis"]["tickOptions"] = array("angle" => "-70", "fontSize" => "8pt");
			}
			break;
		default:
			$params = array(
				"chart_id" => $chart_id,
				"default_result" => $result
			);

			$result = elgg_trigger_plugin_hook("system", "advanced_statistics", $params, $result);
			break;
	}

	return json_encode($result);
}

/**
 * Returns data for a given chart id
 *
 * @param string $chart_id   chart id
 * @param int    $group_guid group to get data for
 *
 * @return string
 */
function advanced_statistics_get_group_data($chart_id) {
	$result = array("data" => array(), "options" => array());
	
	$dbprefix = elgg_get_config("dbprefix");
	$current_site_guid = elgg_get_site_entity()->getGUID();
	$container_guid = get_input('container_guid');
	
	switch ($chart_id) {
		case "members":
			$data = array();
			$data2 = array();
			
			$query = "SELECT FROM_UNIXTIME(r.time_created, '%Y-%m-%d') AS date_created, count(*) AS total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
			$query .= " WHERE r.guid_two = " . $container_guid . " AND r.relationship = 'member'";
			$query .= " AND e.type = 'user'";
			$query .= " AND r.time_created > 0";
			$query .= " GROUP BY FROM_UNIXTIME(r.time_created, '%Y-%m-%d')";
			
			if ($query_result = get_data($query)) {
				$total = 0;
				
				foreach ($query_result as $row) {
					$date_total = (int) $row->total;
					$total += $date_total;
					
					$data[] = array($row->date_created , $date_total);
					$data2[] = array($row->date_created , $total);
				}
			}
			
			$result["data"] = array($data, $data2);
			$result["options"] = advanced_statistics_get_default_chart_options("date");
			$result["options"]["series"] = array(
				array("showMarker" => false, "label" => elgg_echo("admin:widget:new_users")),
				array("showMarker" => false, "label" => elgg_echo("total") . " " . strtolower(elgg_echo("item:user")), "yaxis" => "y2axis")
			);
			$result["options"]["legend"] = array("show" => true, "position" => "e");
			
			break;
		case "contenttype":
			$data = array();
			
			$subtype_ids = array();
			$subtypes = get_registered_entity_types("object");
			
			foreach ($subtypes as $subtype) {
				if ($subtype_id = get_subtype_id("object", $subtype)) {
					$subtype_ids[] = $subtype_id;
				}
			}
			$query = "SELECT e.subtype as subtype, count(*) as total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " WHERE e.type = 'object'";
			$query .= " AND e.subtype IN (" . implode(",", $subtype_ids) . ")";
			$query .= " AND e.container_guid = " . $container_guid;
			if ($ts_limit) {
				$query .= " AND {$ts_limit}";
			}
			$query .= " GROUP BY e.subtype";
			$query .= " ORDER BY total DESC";
			
			if ($query_result = get_data($query)) {
				foreach ($query_result as $row) {
					$subtype = get_subtype_from_id($row->subtype);
					$subtype = elgg_echo("item:object:" . $subtype);
					
					$total = (int) $row->total;
					$data[] = array($subtype, $total);
				}
			}
			
			$result["data"] = array($data);
			$result["options"] = advanced_statistics_get_default_chart_options("bar");
			$result["options"]["seriesDefaults"]["rendererOptions"] = array("varyBarColor" => true);
			
			$result["options"]["highlighter"] = array (
				"show" => true,
				"sizeAdjust" => 7.5,
				"tooltipAxes" => "y"
			);
			$result["options"]["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
			$result["options"]["axes"]["xaxis"]["tickOptions"] = array("angle" => "-30", "fontSize" => "8pt");
			
			break;
		case "content-creation":
			$data = array();
			
			$query = "SELECT FROM_UNIXTIME(e.time_created, '%Y-%m-%d') AS date_created, count(*) as total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " WHERE e.container_guid = " . $container_guid;
			$query .= " GROUP BY FROM_UNIXTIME(e.time_created, '%Y-%m-%d')";
			
			if ($query_result = get_data($query)) {
				foreach ($query_result as $row) {
					$date_created = $row->date_created;
					
					$total = (int) $row->total;
					$data[] = array($date_created, $total);
				}
			}
			
			$result["data"] = array($data);
			
			$result["options"] = advanced_statistics_get_default_chart_options("date");
			$result["options"]["series"] = array(array("showMarker" => false));
			
			break;
		case "activity":
			$data = array();
			
			$query = "SELECT FROM_UNIXTIME(r.posted, '%Y-%m-%d') AS date_created, count(*) as total";
			$query .= " FROM " . $dbprefix . "entities e";
			$query .= " JOIN " . $dbprefix . "river r ON e.guid = r.object_guid";
			$query .= " WHERE e.container_guid = " . $container_guid;
			$query .= " GROUP BY FROM_UNIXTIME(r.posted, '%Y-%m-%d')";
			
			if ($query_result = get_data($query)) {
				foreach ($query_result as $row) {
					$date_created = $row->date_created;
					
					$total = (int) $row->total;
					$data[] = array($date_created, $total);
				}
			}
			
			$result["data"] = array($data);
			
			$result["options"] = advanced_statistics_get_default_chart_options("date");
			$result["options"]["series"] = array(array("showMarker" => false));
			
			break;
		default:
			$params = array(
				"chart_id" => $chart_id,
				"default_result" => $result
			);
			
			$result = elgg_trigger_plugin_hook("group", "advanced_statistics", $params, $result);
			break;
	}
	
	return json_encode($result);
}

/**
 * Returns the query part to limit data based on date selection input
 *
 * @param $field_name string Name of the column name to limit
 *
 * @return string
 */
function advanced_statistics_get_timestamp_query_part($field_name) {
	if (empty($field_name)) {
		return '';
	}
	
	$ts_lower = get_input('ts_lower');
	$ts_upper = get_input('ts_upper');
	
	if (empty($ts_lower) && empty($ts_upper)) {
		return '';
	}
	
	$ts_lower = strtotime($ts_lower);
	$ts_upper = strtotime($ts_upper);
	
	$ts_limits = [];
	if (!empty($ts_lower)) {
		$ts_limits[] = $field_name . ' > ' . $ts_lower;
	}
	if (!empty($ts_upper)) {
		$ts_limits[] = $field_name . ' < ' . $ts_upper;
	}
	
	return implode(' AND ', $ts_limits);
}

/**
 * Returns the default chart options for a give chart type
 *
 * @param string $type chart type
 *
 * @return array
 */
function advanced_statistics_get_default_chart_options($type) {
	$defaults = array(
			"pie" => array(
					"seriesDefaults" => array (
						"renderer" => "$.jqplot.PieRenderer",
						"rendererOptions" => array(
							"showDataLabels" => true
						)
					),
					"legend" => array (
						"show" => true,
						"location" => "e"
					)
				),
			"bar" => array(
					"seriesDefaults" => array (
						"renderer" => "$.jqplot.BarRenderer",
						"pointLabels" => array (
							"show" => true,
							"stackedValue" => true
						)
					),
					"legend" => array (
						"show" => false
					),
					"axes" => array (
						"xaxis" => array (
							"renderer" => "$.jqplot.CategoryAxisRenderer"
							)
					)
				),
			"date" => array(
					"axes" => array (
						"xaxis" => array (
							"renderer" => "$.jqplot.DateAxisRenderer"
						),
						"yaxis" => array (
							"autoscale" => true,
							"min" => 0
						),
						"y2axis" => array (
							"autoscale" => true,
							"min" => 0,
							"tickOptions" => array(
								"showGridline" => false
							)
						)
					),
					"highlighter" => array (
						"show" => true,
						"sizeAdjust" => 7.5
					)
				),
		);
	
	return $defaults[$type];
}
