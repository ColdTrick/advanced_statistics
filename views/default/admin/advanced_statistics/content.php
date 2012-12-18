<?php 

/**
*            count created content (pie)
    distribution (groups vs personal)
    content usage in groups (% blog, %file etc)
    content usage personal
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
elgg_load_js("jquery.jqplot.highlighter");

$title = elgg_echo('advanced_statistics:content:totals');
$body = "<div id='advanced-statistics-content-totals' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:content:distribution');
$body = "<div id='advanced-statistics-content-distribution' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

