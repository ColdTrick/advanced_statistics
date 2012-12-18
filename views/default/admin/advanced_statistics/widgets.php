<?php 

/**
*           Widget handler counts (pie)
    Widget context
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

$title = elgg_echo('advanced_statistics:widgets:handlers');
$body = "<div id='advanced-statistics-widgets-handlers' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:widgets:context');
$body = "<div id='advanced-statistics-widgets-context' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);
