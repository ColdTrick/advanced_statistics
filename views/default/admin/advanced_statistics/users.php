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
elgg_load_js("jquery.jqplot.barRenderer");
elgg_load_js("jquery.jqplot.categoryAxisRenderer");
elgg_load_js("jquery.jqplot.canvasAxisTickRenderer");
elgg_load_js("jquery.jqplot.canvasTextRenderer");
elgg_load_js("jquery.jqplot.dateAxisRenderer");
elgg_load_js("jquery.jqplot.pointLabels");
elgg_load_js("jquery.jqplot.highlighter");

$title = elgg_echo('advanced_statistics:users:popular');
$body = "<div id='advanced-statistics-users-popular' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:users:most_used_domains');
$body = "<div id='advanced-statistics-users-most-used-domains' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:users:account_creation');
$body = "<div id='advanced-statistics-users-account-creation' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:users:account_status');
$body = "<div id='advanced-statistics-users-account-status' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:users:account_activity');
$body = "<div id='advanced-statistics-users-account-activity' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:users:language_distribution');
$body = "<div id='advanced-statistics-users-language-distribution' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);

$title = elgg_echo('advanced_statistics:users:profile_field_usage');
$body = "<div id='advanced-statistics-users-profile-field-usage' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
echo elgg_view_module("inline", $title, $body);