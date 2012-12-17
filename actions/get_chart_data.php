<?php 

	if($chart_id = get_input("chart_id")){
		$result = advanced_statistics_get_chart_data($chart_id);
	}
	
	echo json_encode($result);