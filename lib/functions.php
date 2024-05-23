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
function advanced_statistics_get_timestamp_query_part(string $field_name): string {
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
function advanced_statistics_get_default_chart_options(string $type): array {
	
	$defaults = [
		'options' => [
			'maintainAspectRatio' => false,
			'plugins' => [
				'legend' => [
					'display' => false,
				],
			],
		],
	];
	
	$type_defaults = [
		'pie' => [
			'type' => 'pie',
			'options' => [
				'plugins' => [
					'legend' => [
						'display' => true,
					],
				],
			],
		],
		'bar' => [
			'type' => 'bar',
			'options' => [
				'scales' => [
					'y' => [
						'min' => 0,
					],
				],
			],
		],
		'date' => [
			'type' => 'line',
			'options' => [
				'scales' => [
					'y' => [
						'min' => 0,
					],
				],
			],
		],
	];
	
	return array_merge_recursive($defaults, $type_defaults[$type]);
}
