<?php 

/**
*        10 most popular groups (bar)
    enabled tools for group (pie)
    10 most active groups (most activity in last week) (bar)
    10 least active groups (least activity in last week)
    Dead vs Alive groups (last activity < 1 month <3 <6 <12) (pie)
*/

elgg_load_js("jquery.jqplot");
elgg_load_css("jquery.jqplot");

// extra jqplot plugins
elgg_load_js("jquery.jqplot.pieRenderer");
elgg_load_js("jquery.jqplot.barRenderer");
elgg_load_js("jquery.jqplot.categoryAxisRenderer");
elgg_load_js("jquery.jqplot.canvasAxisTickRenderer");
elgg_load_js("jquery.jqplot.canvasTextRenderer");
// elgg_load_js("jquery.jqplot.dateAxisRenderer");
// elgg_load_js("jquery.jqplot.pointLabels");
// elgg_load_js("jquery.jqplot.highlighter");

$title = elgg_echo('advanced_statistics:groups:popular');
$body = "<div id='advanced-statistics-groups-popular' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:groups:popular_tools');
$body = "<div id='advanced-statistics-groups-popular-tools' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:groups:most_active');
$body = "<div id='advanced-statistics-groups-most-active' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:groups:least_active');
$body = "<div id='advanced-statistics-groups-least-active' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:groups:dead_vs_alive');
$body = "<div id='advanced-statistics-groups-dead-vs-alive' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);