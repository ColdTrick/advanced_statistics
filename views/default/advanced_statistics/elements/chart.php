<?php
/**
 * Generate a chart info module
 *
 * @uses $vars['id']             Unique ID for the chart
 * @uses $vars['title']          Title of the chart
 * @uses $vars['help']           Help text below the chart
 * @uses $vars['page']           Main page which will handle the chart data (@see views/json/advanced_statistics)
 * @uses $vars['section']        Section in the page
 * @uses $vars['chart']          Name of the chart
 * @uses $vars['url_elements']   Additional URL elements
 * @uses $vars['date_limited']   Is a date range selection supported (default: false)
 * @uses $vars['container_guid'] Container GUID to limit data to (for groups)
 */

elgg_require_js('advanced_statistics/charts');
elgg_require_css('advanced_statistics/jqplot');

$id = elgg_extract('id', $vars);
$title = elgg_extract('title', $vars);

$page = elgg_extract('page', $vars);

$url_elements = (array) elgg_extract('url_elements', $vars, []);

$url_elements['section'] = elgg_extract('section', $vars);
$url_elements['chart'] = elgg_extract('chart', $vars);

if ((bool) elgg_extract('date_limited', $vars, false)) {
	$date_part = '';
	$ts_lower = get_input('ts_lower');
	$ts_upper = get_input('ts_upper');
	if ($ts_lower) {
		$date_part .= ' since ' . $ts_lower;
	}
	if ($ts_upper) {
		$date_part .= ' until ' . $ts_upper;
	}
	
	if (!empty($date_part)) {
		$title .= ' -' . $date_part;
	}
	
	$url_elements['ts_lower'] = $ts_lower;
	$url_elements['ts_upper'] = $ts_upper;
}

$container_guid = elgg_extract('container_guid', $vars);
if ($container_guid) {
	$url_elements['container_guid'] = $container_guid;
}

$body = elgg_format_element('div', [
	'id' => $id,
	'class' => 'advanced-statistics-plot-container',
	'data-chart-href' => elgg_http_add_url_query_elements("advanced_statistics/{$page}", $url_elements),
], elgg_view('graphics/ajax_loader', ['hidden' => false]));


$help = elgg_extract('help', $vars);
if (!elgg_is_empty($help)) {
	$body .= elgg_format_element('div', ['class' => ['elgg-field-help', 'elgg-text-help']], $help);
}

echo elgg_view_module('info', $title, $body);
?>
<script>
	require(['advanced_statistics/charts'], function (advancedStatistics) {
		setTimeout(function() {
			advancedStatistics.init('#<?php echo $id; ?>');
		}, 500);
	});
</script>
