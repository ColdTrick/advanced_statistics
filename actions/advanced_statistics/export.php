<?php

$request_data = elgg_get_request_data();
if (empty($request_data['page']) || empty($request_data['section']) || empty($request_data['chart'])) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

// load entity if a guid is provided, during viewing of the chart this is handled by the Router
if (!empty($request_data['guid'])) {
	$request_data['entity'] = get_entity((int) $request_data['guid']);
}

// get the chart data
$viewtype = elgg_get_viewtype();
elgg_set_viewtype('json');

$json_data = elgg_view("advanced_statistics/{$request_data['page']}", $request_data);

elgg_set_viewtype($viewtype);

if (empty($json_data)) {
	return elgg_error_response(elgg_echo('notfound'));
}

$graph_data = json_decode($json_data, true);
$datasets = elgg_extract('datasets', elgg_extract('data', $graph_data));
$labels = elgg_extract('labels', elgg_extract('data', $graph_data), []);
if (empty($datasets)) {
	return elgg_error_response(elgg_echo('notfound'));
}

$result = [];
foreach ($datasets as $series => $series_data) {
	$serie_label = elgg_extract('label', $series_data);
	$data = elgg_extract('data', $series_data);
	
	foreach ($data as $index => $point) {
		$label = elgg_extract('x', $point, elgg_extract($index, $labels));
		$value = elgg_extract('y', $point, $point);
		
		if (!array_key_exists($index, $result)) {
			$result[$index] = [$label, $value];
		} else {
			$result[$index][] = $value;
		}
	}
}

if (empty($result)) {
	return elgg_error_response(elgg_echo('notfound'));
}

$fh_temp = new \ElggTempFile();
$fh = $fh_temp->open('write');

foreach ($result as $point) {
	fputcsv($fh, $point, ';', '"');
}

$contents = $fh_temp->grabFile();
$fh_temp->close();

$title = elgg_extract('title', $request_data, elgg_echo('unknown'));
$title = elgg_get_friendly_title($title);

header('Content-Type: text/csv;charset=utf-8');
header("Content-Disposition: attachment;filename={$title}.csv");
header('Content-Length: ' . strlen($contents));

echo $contents;
exit();
