<?php 

/**
 *     25 users with most friends (bars)
    25 most friended users (most followers)
    25 most used emaildomains (bar)
    Language distribution (pie)
    Account creation over time (line)
    Active vs Unvalidated vs Banned (pie)
    Last login < 1 month < 3 months < 6 months < 1 year (pie)
    Profile fields usage (pie)
    Profile Completeness (with PM plugin)
 */

elgg_load_js("jquery.jqplot");
elgg_load_css("jquery.jqplot");

// extra jqplot plugins
elgg_load_js("jquery.jqplot.pieRenderer");

$title = elgg_echo('advanced_statistics:users:popular');
$body = "<div id='advanced-statistics-users-popular' class='advanced-statistics-plot-container'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:users:language-distribution');
$body = "<div id='advanced-statistics-users-language-distribution' class='advanced-statistics-plot-container'></div>";
echo elgg_view_module("inline", $title, $body);