<?php

$request_data = elgg_get_request_data();
if (empty($request_data['page']) || empty($request_data['section']) || empty($request_data['chart'])) {
	return elgg_error_response(elgg_echo('error:missing_data'));
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
$data = elgg_extract('data', $graph_data);
if (empty($data)) {
	return elgg_error_response(elgg_echo('notfound'));
}

$result = [];
$append = false;
if (isset($graph_data['options']['axes']['xaxis']['ticks'])) {
	$ticks = $graph_data['options']['axes']['xaxis']['ticks'];
	foreach ($ticks as $index => $tick) {
		$result[$index][] = $tick;
	}
	$append = true;
}

foreach ($data as $series => $series_data) {
	foreach ($series_data as $index => $point) {
		if ($append) {
			$result[$index][] = $point;
		} elseif ($series > 0) {
			$result[$index][] = $point[1];
		} else {
			$result[$index] = $point;
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
