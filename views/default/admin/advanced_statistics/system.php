<?php 

/**
*     

FileStorage

    most consuming owners (bar)
    most consuming groups (bar)
    most consuming sites (bar)
    Based on ElggFile and ElggPhoto (tidypics) stacked bar


DB Size

    data distribution (bar)
*/

elgg_load_js("jquery.jqplot");
elgg_load_css("jquery.jqplot");

// extra jqplot plugins
// elgg_load_js("jquery.jqplot.pieRenderer");
elgg_load_js("jquery.jqplot.barRenderer");
elgg_load_js("jquery.jqplot.categoryAxisRenderer");
elgg_load_js("jquery.jqplot.canvasAxisTickRenderer");
elgg_load_js("jquery.jqplot.canvasTextRenderer");
// elgg_load_js("jquery.jqplot.dateAxisRenderer");
// elgg_load_js("jquery.jqplot.pointLabels");
// elgg_load_js("jquery.jqplot.highlighter");

$title = elgg_echo('advanced_statistics:system:files:users');
$body = "<div id='advanced-statistics-system-files-users' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:system:files:groups');
$body = "<div id='advanced-statistics-system-files-groups' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

// $title = elgg_echo('advanced_statistics:system:files:sites');
// $body = "<div id='advanced-statistics-system-files-sites' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
// echo elgg_view_module("inline", $title, $body);

// $title = elgg_echo('advanced_statistics:system:database:tables');
// $body = "<div id='advanced-statistics-system-database-tables' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
// echo elgg_view_module("inline", $title, $body);

// $title = elgg_echo('advanced_statistics:system:database:overhead');
// $body = "<div id='advanced-statistics-system-database-overhead' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
// echo elgg_view_module("inline", $title, $body);
