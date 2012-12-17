<?php 

$result = array("data" => array(), "options" => array());

if($chart_id = get_input("chart_id")){
	switch($chart_id){
		case "advanced-statistics-users-language-distribution":
			$result["data"] = array(
								 array('Heavy Industry', 182),
								 array('Retail', 9)
//								 ,
//								 array('Light Industry', 14),
//								 array('Out of home', 16),
//								 array('Commuting', 7), 
//								 array('Orientation', 9)
								);
			$result["options"] = array(
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
								);
	
			break;
		default:
			$result["data"] = array(array(1, 3, 2, 4, 6, 9));
			break;
	}
}
//echo json_encode($result);
echo json_encode($result);