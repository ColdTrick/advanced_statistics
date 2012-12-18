<?php 

/**
*            Which days most activity?
    Which hours most activity?
    Activity count per day (line)
*/

elgg_load_js("jquery.jqplot");
elgg_load_css("jquery.jqplot");

// extra jqplot plugins
// elgg_load_js("jquery.jqplot.pieRenderer");
elgg_load_js("jquery.jqplot.barRenderer");
elgg_load_js("jquery.jqplot.categoryAxisRenderer");
// elgg_load_js("jquery.jqplot.canvasAxisTickRenderer");
// elgg_load_js("jquery.jqplot.canvasTextRenderer");
elgg_load_js("jquery.jqplot.dateAxisRenderer");
// elgg_load_js("jquery.jqplot.pointLabels");
elgg_load_js("jquery.jqplot.highlighter");

$title = elgg_echo('advanced_statistics:activity:day');
$body = "<div id='advanced-statistics-activity-day' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:activity:hour');
$body = "<div id='advanced-statistics-activity-hour' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:activity:timeline');
$body = "<div id='advanced-statistics-activity-timeline' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);
