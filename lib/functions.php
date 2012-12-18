<?php

	function advanced_statistics_get_users_data($chart_id){
		$result = array("data" => array(), "options" => array());
		
		$dbprefix = elgg_get_config("dbprefix");
		$current_site_guid = elgg_get_site_entity()->getGUID();
		
		switch($chart_id){
			case "language-distribution":
				$data = array();
				
				$query = "SELECT ue.language, count(*) AS total";
				$query .= " FROM " . $dbprefix . "users_entity ue";
				$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = ue.guid";
				$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
				$query .= " GROUP BY language";
				
				if($query_result = get_data($query)){
					foreach($query_result as $row){
						$language = $row->language;
						if(empty($language)){
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
				$query .= " GROUP BY FROM_UNIXTIME(r.time_created, '%Y-%m-%d')";
				
				if($query_result = get_data($query)){
					$total = 0;
					
					foreach($query_result as $row){
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
				
				if($query_result = get_data($query)){
					foreach($query_result as $row){
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
				$query .= " GROUP BY FROM_UNIXTIME(e.last_action, '%Y-%m')";
				
				if($query_result = get_data($query)){
					foreach($query_result as $row){
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
				
				if($query_result = get_data_row($query)){
					$banned = (int) $query_result->total;
					
					$data[] = array("banned [" . $banned . "]", $banned);
				}
				
				// unvalidated
				
				$validated_id = add_metastring('validated');
				$one_id = add_metastring('1');
				
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
				
				if($query_result = get_data_row($query)){
					$unvalidated = (int) $query_result->total;
						
					$data[] = array("unvalidated [" . $unvalidated . "]", $unvalidated);
				}
				
				// disabled
				$query = "SELECT count(*) AS total";
				$query .= " FROM " . $dbprefix . "entities e";
				$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
				$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
				$query .= " AND e.type = 'user' AND e.enabled = 'no'";
				
				if($query_result = get_data_row($query)){
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
				
				if($query_result = get_data_row($query)){
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
				
				if($profile_fields = elgg_get_config("profile_fields")){
					$total_users_count = 0;
					$empty_id = add_metastring("");
					
					// total for this field
					$query = "SELECT count(*) AS total";
					$query .= " FROM " . $dbprefix . "entities e";
					$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
					$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
					$query .= " AND e.type = 'user'";
						
					if($query_result = get_data_row($query)){
						$total_users_count = (int) $query_result->total;
					}
					
					foreach($profile_fields as $field_name => $type){
						$name_id = add_metastring($field_name);
						
						// total for this field
						$query = "SELECT count(distinct e.guid) AS total";
						$query .= " FROM " . $dbprefix . "entities e";
						$query .= " JOIN " . $dbprefix . "entity_relationships r ON r.guid_one = e.guid";
						$query .= " JOIN " . $dbprefix . "metadata md ON e.guid = md.entity_guid";
						$query .= " WHERE r.guid_two = " . $current_site_guid . " AND r.relationship = 'member_of_site'";
						$query .= " AND e.type = 'user'";
						$query .= " AND md.name_id = '" . $name_id . "'";
						$query .= " AND md.value_id <> " . $empty_id;
							
						if($query_result = get_data_row($query)){
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
				
				if($query_result = get_data($query)){
					foreach($query_result as $row){
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
	
	function advanced_statistics_get_groups_data($chart_id){
		$result = array("data" => array(), "options" => array());
	
		$dbprefix = elgg_get_config("dbprefix");
		$current_site_guid = elgg_get_site_entity()->getGUID();
	
		switch($chart_id){
			case "popular":
				$data = array();
				
				$query = "SELECT ge.name, count(*) AS total";
				$query .= " FROM " . $dbprefix . "groups_entity ge";
				$query .= " JOIN " . $dbprefix . "entity_relationships r ON ge.guid = r.guid_two";
				$query .= " JOIN " . $dbprefix . "entities e ON ge.guid = e.guid";
				$query .= " JOIN " . $dbprefix . "entities eu ON r.guid_one = eu.guid";
				$query .= " JOIN " . $dbprefix . "users_entity ue ON eu.guid = ue.guid";
				$query .= " WHERE r.relationship = 'member' AND eu.type = 'user'";
				$query .= " AND eu.enabled = 'yes' AND ue.banned = 'no'";
				$query .= " AND e.site_guid = " . $current_site_guid . " AND e.enabled = 'yes'";
				$query .= " GROUP BY ge.name";
				$query .= " ORDER BY total DESC";
				$query .= " LIMIT 0, 10";
				
				if($query_result = get_data($query)){
					foreach($query_result as $row){
						$total = (int) $row->total;
						
						$data[] = array(elgg_get_excerpt($row->name, 25), $total);
					}
				}
				
				$result["data"] = array($data);
				
				$options = advanced_statistics_get_default_chart_options("bar");
				$options["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
				$options["axes"]["xaxis"]["tickOptions"] = array("angle" => "-30", "fontSize" => "8pt");
				
				$result["options"] = $options;
				
				break;
			case "popular-tools":
				
				if($group_tools = elgg_get_config("group_tool_options")){
					$yes_id = add_metastring("yes");
					
					$data = array();
					$order = array();
					
					foreach($group_tools as $key => $tool){
						$tool_id = add_metastring($tool->name . "_enable");
						
						$query = "SELECT md.name_id, count(*) AS total";
						$query .= " FROM " . $dbprefix . "metadata md";
						$query .= " JOIN " . $dbprefix . "entities e ON md.entity_guid = e.guid";
						$query .= " WHERE md.name_id = " . $tool_id;
						$query .= " AND e.type = 'group' AND e.enabled = 'yes'";
						$query .= " AND md.value_id = " . $yes_id;
						
						if($query_result = get_data_row($query)){
							$total = (int) $query_result->total;
							$order[$key] = $total;
							$data[$key] = array($tool->name . " [" . $total . "]", $total);
						}
					}
					
					array_multisort($order, $data);
					
					$result["data"] = array($data);
					$result["options"] = advanced_statistics_get_default_chart_options("pie");
				}
				
				break;
			case "most-active":
				$data = array();
				
				$week_ago = time() - (7 * 24 * 60 * 60);
				
				$query = "SELECT ge.name, count(*) AS total";
				$query .= " FROM " . $dbprefix . "river r";
				$query .= " JOIN " . $dbprefix . "entities e ON r.object_guid = e.guid";
				$query .= " JOIN " . $dbprefix . "entities eg ON e.container_guid = eg.guid";
				$query .= " JOIN " . $dbprefix . "groups_entity ge ON eg.guid = ge.guid";
				$query .= " WHERE e.enabled = 'yes' AND e.site_guid = " . $current_site_guid;
				$query .= " AND eg.type = 'group' AND eg.enabled = 'yes' AND eg.site_guid = " . $current_site_guid;
				$query .= " AND r.posted > " . $week_ago;
				$query .= " GROUP BY ge.name";
				$query .= " ORDER BY total DESC";
				$query .= " LIMIT 0, 10";
				
				if($query_result = get_data($query)){
					foreach($query_result as $row){
						$total = (int) $row->total;
						
						$data[] = array(elgg_get_excerpt($row->name, 25), $total);
					}
					
					$result["data"] = array($data);
					
					$options = advanced_statistics_get_default_chart_options("bar");
					$options["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
					$options["axes"]["xaxis"]["tickOptions"] = array("angle" => "-30", "fontSize" => "8pt");
					
					$result["options"] = $options;
				}
				
				break;
			case "least-active":
				$data = array();
				
				$week_ago = time() - (7 * 24 * 60 * 60);
				
				$query = "SELECT ge.name, count(*) AS total";
				$query .= " FROM " . $dbprefix . "river r";
				$query .= " JOIN " . $dbprefix . "entities e ON r.object_guid = e.guid";
				$query .= " JOIN " . $dbprefix . "entities eg ON e.container_guid = eg.guid";
				$query .= " JOIN " . $dbprefix . "groups_entity ge ON eg.guid = ge.guid";
				$query .= " WHERE e.enabled = 'yes' AND e.site_guid = " . $current_site_guid;
				$query .= " AND eg.type = 'group' AND eg.enabled = 'yes' AND eg.site_guid = " . $current_site_guid;
				$query .= " GROUP BY ge.name";
				$query .= " ORDER BY total ASC";
				$query .= " LIMIT 0, 10";
				
				if($query_result = get_data($query)){
					foreach($query_result as $row){
						$total = (int) $row->total;
						
						$data[] = array(elgg_get_excerpt($row->name, 25), $total);
					}
					
					$result["data"] = array($data);
					
					$options = advanced_statistics_get_default_chart_options("bar");
					$options["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
					$options["axes"]["xaxis"]["tickOptions"] = array("angle" => "-30", "fontSize" => "8pt");
					
					$result["options"] = $options;
				}
				
				break;
			case "dead-vs-alive":
				$data = array();
				$guids = array();
				
				$month = time() - (30 * 24 * 60 * 60);
				
				$base_query = "SELECT DISTINCT eg.guid";
				$base_query .= " FROM " . $dbprefix . "river r";
				$base_query .= " JOIN " . $dbprefix . "entities e ON r.object_guid = e.guid";
				$base_query .= " JOIN " . $dbprefix . "entities eg ON e.container_guid = eg.guid";
				$base_query .= " WHERE e.enabled = 'yes' AND e.site_guid = " . $current_site_guid;
				$base_query .= " AND eg.enabled = 'yes' AND eg.site_guid = " . $current_site_guid;
				$base_query .= " AND eg.type = 'group'";
				
				// activity in last month
				$month_query = $base_query . " AND r.posted >= " . $month;
				
				if($query_result = get_data($month_query)){
					$total = count($query_result);
					$data[] = array(elgg_echo("advanced_statistics:groups:dead_vs_alive:last_month", array($total)), $total);
				}
				
				// activity in last 3 months
				$threemonth =  time() - (90 * 24 * 60 * 60);
				
				$threemonth_query_base = $base_query . " AND r.posted >= " . $threemonth;
				$query = $threemonth_query_base . " AND eg.guid NOT IN (" . $month_query . ")";
				
				if($query_result = get_data($query)){
					$total = count($query_result);
					$data[] = array(elgg_echo("advanced_statistics:groups:dead_vs_alive:3_months", array($total)), $total);
				}
				
				// activity in last 6 months
				$sixmonth =  time() - (180 * 24 * 60 * 60);
				
				$sixmonth_query_base = $base_query . " AND r.posted >= " . $sixmonth;
				$query = $sixmonth_query_base . " AND eg.guid NOT IN (" . $threemonth_query_base . ")";
				
				if($query_result = get_data($query)){
					$total = count($query_result);
					$data[] = array(elgg_echo("advanced_statistics:groups:dead_vs_alive:6_months", array($total)), $total);
				}
				
				// activity in last year
				$year =  time() - (365 * 24 * 60 * 60);
				
				$year_query_base = $base_query . " AND r.posted >= " . $year;
				$query = $year_query_base . " AND eg.guid NOT IN (" . $sixmonth_query_base . ")";
				
				if($query_result = get_data($query)){
					$total = count($query_result);
					$data[] = array(elgg_echo("advanced_statistics:groups:dead_vs_alive:year", array($total)), $total);
				}
				
				// activity < last year
				$query = $base_query . " AND r.posted < " . $year;
				$query .= " AND eg.guid NOT IN (" . $year_query_base . ")";
				
				if($query_result = get_data($query)){
					$total = count($query_result);
					$data[] = array(elgg_echo("advanced_statistics:groups:dead_vs_alive:more_year", array($total)), $total);
				}
				
				$result["data"] = array($data);
				$result["options"] = advanced_statistics_get_default_chart_options("pie");
				break;
			default:
				$params = array(
					"chart_id" => $chart_id,
					"default_result" => $result
				);
	
			$result = elgg_trigger_plugin_hook("groups", "advanced_statistics", $params, $result);
			break;
		}
	
		return json_encode($result);
	}
	
	function advanced_statistics_get_activity_data($chart_id){
		$result = array("data" => array(), "options" => array());
	
		$dbprefix = elgg_get_config("dbprefix");
		$current_site_guid = elgg_get_site_entity()->getGUID();
	
		switch($chart_id){
			case "day":
				$data = array();
			
				$query = "SELECT DAYOFWEEK(FROM_UNIXTIME(r.posted)) AS day_of_the_week, count(*) as total";
				$query .= " FROM " . $dbprefix . "entities e";
				$query .= " JOIN " . $dbprefix . "river r ON e.guid = r.object_guid";
				$query .= " WHERE e.site_guid = " . $current_site_guid;
				$query .= " GROUP BY DAYOFWEEK(FROM_UNIXTIME(posted))";
			
				if($query_result = get_data($query)){
					foreach($query_result as $row){
						$dotw = $row->day_of_the_week;
						$dotw = elgg_echo("advanced_statistics:activity:day:" . $dotw);
						
						$total = (int) $row->total;
						$data[] = array($dotw . " [" . $total . "]"  , $total);
					}
				}
			
				$result["data"] = array($data);
				$result["options"] = advanced_statistics_get_default_chart_options("bar");
			
				break;
			case "hour":
				$data = array();
			
				$query = "SELECT FROM_UNIXTIME(r.posted, '%k') AS hour_of_the_day, count(*) as total";
				$query .= " FROM " . $dbprefix . "entities e";
				$query .= " JOIN " . $dbprefix . "river r ON e.guid = r.object_guid";
				$query .= " WHERE e.site_guid = " . $current_site_guid;
				$query .= " GROUP BY FROM_UNIXTIME(r.posted, '%k')";
			
				for($i = 0; $i < 24; $i++){
					$data[$i] = array("$i", 0);
				}
				
				if($query_result = get_data($query)){
					foreach($query_result as $row){
						$hotd = $row->hour_of_the_day;
						
						$total = (int) $row->total;
						$data[(int)$hotd] = array($hotd, $total);
					}
				}
			
				$result["data"] = array($data);
				$result["options"] = advanced_statistics_get_default_chart_options("bar");
			
				break;
			case "timeline":
				$data = array();
			
				$query = "SELECT FROM_UNIXTIME(r.posted, '%Y-%m-%d') AS date_created, count(*) as total";
				$query .= " FROM " . $dbprefix . "entities e";
				$query .= " JOIN " . $dbprefix . "river r ON e.guid = r.object_guid";
				$query .= " WHERE e.site_guid = " . $current_site_guid;
				$query .= " GROUP BY FROM_UNIXTIME(r.posted, '%Y-%m-%d')";
							
				if($query_result = get_data($query)){
					foreach($query_result as $row){
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
	
			$result = elgg_trigger_plugin_hook("activity", "advanced_statistics", $params, $result);
			break;
		}
	
		return json_encode($result);
	}
	
	function advanced_statistics_get_widgets_data($chart_id){
		$result = array("data" => array(), "options" => array());
	
		$dbprefix = elgg_get_config("dbprefix");
		$current_site_guid = elgg_get_site_entity()->getGUID();
	
		switch($chart_id){
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
					
				if($query_result = get_data($query)){
					foreach($query_result as $row){
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
					
				if($query_result = get_data($query)){
					foreach($query_result as $row){
						$context = $row->context;
						if(!$context){
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
	
	function advanced_statistics_get_content_data($chart_id){
		$result = array("data" => array(), "options" => array());
	
		$dbprefix = elgg_get_config("dbprefix");
		$current_site_guid = elgg_get_site_entity()->getGUID();
	
		switch($chart_id){
			case "totals":
				$data = array();
			
				$subtype_ids = array();
				$subtypes = get_registered_entity_types("object");
				
				foreach($subtypes as $subtype){
					if($subtype_id = get_subtype_id("object", $subtype)){
						$subtype_ids[] = $subtype_id;
					}
				}
				$query = "SELECT e.subtype as subtype, count(*) as total";
				$query .= " FROM " . $dbprefix . "entities e";
				$query .= " WHERE e.type = 'object'";
				$query .= " AND e.subtype IN (" . implode(",", $subtype_ids) . ")";
				$query .= " AND e.site_guid = " . $current_site_guid;
				$query .= " GROUP BY e.subtype";
				$query .= " ORDER BY total DESC";
					
				if($query_result = get_data($query)){
					foreach($query_result as $row){
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
			case "distribution":
				$data = array();
			
				$subtype_ids = array();
				$subtypes = get_registered_entity_types("object");
				
				foreach($subtypes as $subtype){
					if($subtype_id = get_subtype_id("object", $subtype)){
						$subtype_ids[] = $subtype_id;
					}
				}
				$query = "SELECT e2.type as type, count(*) as total";
				$query .= " FROM " . $dbprefix . "entities e";
				$query .= " JOIN " . $dbprefix . "entities e2 ON e.container_guid = e2.guid";
				$query .= " WHERE e.type = 'object'";
				$query .= " AND e.subtype IN (" . implode(",", $subtype_ids) . ")";
				$query .= " AND e.site_guid = " . $current_site_guid;
				$query .= " GROUP BY e2.type";
				$query .= " ORDER BY total DESC";
					
				if($query_result = get_data($query)){
					foreach($query_result as $row){
						$total = (int) $row->total;
						$data[] = array($row->type, $total);
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
	
			$result = elgg_trigger_plugin_hook("content", "advanced_statistics", $params, $result);
			break;
		}
	
		return json_encode($result);
	}
	
	function advanced_statistics_get_system_data($chart_id){
		$result = array("data" => array(), "options" => array());
	
		$dbprefix = elgg_get_config("dbprefix");
		$current_site_guid = elgg_get_site_entity()->getGUID();
	
		switch($chart_id){
			case "files-users":
				$data = array();
			
				$subtype_ids = array();
				if($subtype_id = get_subtype_id("object", "file")){
					$subtype_ids[] = $subtype_id;
				}
				if($subtype_id = get_subtype_id("object", "images")){
					$subtype_ids[] = $subtype_id;
				}
			
				if(!empty($subtype_ids)){
				
					$query = "SELECT ue.name as user, count(*) as total";
					$query .= " FROM " . $dbprefix . "entities e";
					$query .= " JOIN " . $dbprefix . "users_entity ue ON e.owner_guid = ue.guid";
					$query .= " WHERE e.type = 'object' AND e.subtype IN (" . implode(", ", $subtype_ids) . ")";
					$query .= " AND e.site_guid = " . $current_site_guid;
					$query .= " GROUP BY e.owner_guid";
					$query .= " ORDER BY total DESC";
					$query .= " LIMIT 0, 25";
						
					if($query_result = get_data($query)){
						foreach($query_result as $row){
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
				if($subtype_id = get_subtype_id("object", "file")){
					$subtype_ids[] = $subtype_id;
				}
				if($subtype_id = get_subtype_id("object", "images")){
					$subtype_ids[] = $subtype_id;
				}
			
				if(!empty($subtype_ids)){
				
					$query = "SELECT ge.name as user, count(*) as total";
					$query .= " FROM " . $dbprefix . "entities e";
					$query .= " JOIN " . $dbprefix . "groups_entity ge ON e.container_guid = ge.guid";
					$query .= " WHERE e.type = 'object' AND e.subtype IN (" . implode(", ", $subtype_ids) . ")";
					$query .= " AND e.site_guid = " . $current_site_guid;
					$query .= " GROUP BY e.container_guid";
					$query .= " ORDER BY total DESC";
					$query .= " LIMIT 0, 25";
						
					if($query_result = get_data($query)){
						foreach($query_result as $row){
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
	
	function advanced_statistics_get_default_chart_options($type){
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
