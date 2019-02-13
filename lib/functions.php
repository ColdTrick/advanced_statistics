<?php
/**
 * Functions for Advanced Statistics
 */

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
	$defaults = [
		'pie' => [
			'seriesDefaults' => [
				'renderer' => '$.jqplot.PieRenderer',
				'rendererOptions' => [
					'showDataLabels' => true
				],
			],
			'legend' => [
				'show' => true,
				'location' => 'e',
			],
		],
		'bar' => [
			'seriesDefaults' => [
				'renderer' => '$.jqplot.BarRenderer',
				'pointLabels' => [
					'show' => true,
					'stackedValue' => true,
				],
			],
			'legend' => [
				'show' => false,
			],
			'axes' => [
				'xaxis' => [
					'renderer' => '$.jqplot.CategoryAxisRenderer',
				],
			],
		],
		'date' => [
			'axes' => [
				'xaxis' => [
					'renderer' => '$.jqplot.DateAxisRenderer',
				],
				'yaxis' => [
					'autoscale' => true,
					'min' => 0,
				],
				'y2axis' => [
					'autoscale' => true,
					'min' => 0,
					'tickOptions' => [
						'showGridline' => false,
					],
				],
			],
			'highlighter' => [
				'show' => true,
				'sizeAdjust' => 7.5,
			],
		],
	];
	
	return $defaults[$type];
}
