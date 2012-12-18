<?php

	function advanced_statistics_get_users_data($chart_id){
		$result = array("data" => array(), "options" => array());
		
		$dbprefix = elgg_get_config("dbprefix");
		$current_site_guid = elgg_get_site_entity()->getGUID();
		
		switch($chart_id){
			case "language-distribution":
				$data = array();
				
				$query = "SELECT language, count(*) AS total";
				$query .= " FROM " . $dbprefix . "users_entity";
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
							
							$ticks[] = elgg_echo("profile:" . $field_name);
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
				$query .= " JOIN " . $dbprefix . "entities e ON ue.guid = e.guid";
				$query .= " WHERE r.relationship = 'friend'";
				$query .= " AND e.enabled = 'yes' AND ue.banned = 'no'";
				$query .= " GROUP BY ue.name";
				$query .= " ORDER BY total desc";
				$query .= " LIMIT 0, 10";
				
				if($query_result = get_data($query)){
					foreach($query_result as $row){
						$data[] = (int) $row->total;
						$ticks[] = $row->name;
					}
				}
				
				$result["data"] = array($data);
				
				$options = advanced_statistics_get_default_chart_options("bar");
				$options["axes"]["xaxis"]["ticks"] = $ticks;
				$options["axes"]["xaxis"]["tickRenderer"] = "$.jqplot.CanvasAxisTickRenderer";
				$options["axes"]["xaxis"]["tickOptions"] = array("angle" => "-30", "fontSize" => "8pt");
				
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
			
				$query = "SELECT DAYOFWEEK(FROM_UNIXTIME(posted)) AS day_of_the_week, count(*) as total";
				$query .= " FROM " . $dbprefix . "river";
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
			
				$query = "SELECT FROM_UNIXTIME(posted, '%k') AS hour_of_the_day, count(*) as total";
				$query .= " FROM " . $dbprefix . "river";
				$query .= " GROUP BY FROM_UNIXTIME(posted, '%k')";
			
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
			
				$query = "SELECT FROM_UNIXTIME(posted, '%Y-%m-%d') AS date_created, count(*) as total";
				$query .= " FROM " . $dbprefix . "river";
				$query .= " GROUP BY FROM_UNIXTIME(posted, '%Y-%m-%d')";
							
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