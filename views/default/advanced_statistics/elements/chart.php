<?php
/**
 * Generate a chart info module
 *
 * @uses $vars['id']                   Unique ID for the chart
 * @uses $vars['title']                Title of the chart
 * @uses $vars['help']                 Help text below the chart
 * @uses $vars['page']                 Main page which will handle the chart data (@see views/json/advanced_statistics)
 * @uses $vars['section']              Section in the page
 * @uses $vars['chart']                Name of the chart
 * @uses $vars['url_elements']         Additional URL elements
 * @uses $vars['date_limited']         Is a date range selection supported (default: false)
 * @uses $vars['container_guid']       Container GUID to limit data to (for groups)
 * @uses $vars['include_banned_users'] Will banned users be included in this chart (default: true)
 * @uses $vars['allow_export']         Is it allowed to export the graph data as an CSV-file (default: true)
 */

elgg_import_esm('advanced_statistics/charts');

$id = elgg_extract('id', $vars);
$title = elgg_extract('title', $vars);

$page = elgg_extract('page', $vars);

$url_elements = (array) elgg_extract('url_elements', $vars, []);

$url_elements['section'] = elgg_extract('section', $vars);
$url_elements['chart'] = elgg_extract('chart', $vars);

if ((bool) elgg_extract('date_limited', $vars, false)) {
	$date_part = [];
	
	$ts_lower = get_input('ts_lower');
	if (!empty($ts_lower)) {
		$date_part[] = elgg_echo('advanced_statistics:chart:since');
		$date_part[] = $ts_lower;
	}
	
	$ts_upper = get_input('ts_upper');
	if (!empty($ts_upper)) {
		$date_part[] = elgg_echo('advanced_statistics:chart:until');
		$date_part[] = $ts_upper;
	}
	
	if (!empty($date_part)) {
		array_unshift($date_part, '-');
		$title .= ' ' . implode(' ', $date_part);
	}
	
	$url_elements['ts_lower'] = $ts_lower;
	$url_elements['ts_upper'] = $ts_upper;
}

$container_guid = elgg_extract('container_guid', $vars);
if ($container_guid) {
	$url_elements['container_guid'] = $container_guid;
}

$url_elements['include_banned_users'] = (int) elgg_extract('include_banned_users', $vars, true);
if (!(bool) elgg_extract('include_banned_users', $vars, true)) {
	$title .= ' - ' . elgg_echo('advanced_statistics:chart:exclude_banned_users');
}

$body = elgg_format_element('div', ['class' => 'advanced-statistics-plot-container'], elgg_format_element('canvas', [
	'id' => $id,
	'data-chart-href' => elgg_http_add_url_query_elements("advanced_statistics/{$page}", $url_elements),
]));


$help = elgg_extract('help', $vars);
if (!elgg_is_empty($help)) {
	$body .= elgg_format_element('div', ['class' => ['elgg-field-help', 'elgg-text-help']], $help);
}

$params = [];
if ((bool) elgg_extract('allow_export', $vars, true)) {
	$export_params = $url_elements;
	$export_params['page'] = $page;
	$export_params['title'] = elgg_extract('title', $vars); // without postfix (date selection)
	$export_params['ts_lower'] = get_input('ts_lower');
	$export_params['ts_upper'] = get_input('ts_upper');
	
	$params['menu'] = elgg_view('output/url', [
		'icon' => 'download',
		'text' => elgg_echo('export'),
		'href' => elgg_generate_action_url('advanced_statistics/export', $export_params),
	]);
}

echo elgg_view_module('info', $title, $body, $params);
?>
<script>
	import('advanced_statistics/charts').then(function (advancedStatistics) {
		setTimeout(function() {
			advancedStatistics.default.init('#<?php echo $id; ?>');
		}, 500);
	});
</script>
