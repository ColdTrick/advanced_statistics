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

echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:system:files:users'), "id" => "advanced-statistics-system-files-users"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:system:files:groups'), "id" => "advanced-statistics-system-files-groups"));

// $title = elgg_echo('advanced_statistics:system:files:sites');
// $body = "<div id='advanced-statistics-system-files-sites' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
// echo elgg_view_module("inline", $title, $body);

// $title = elgg_echo('advanced_statistics:system:database:tables');
// $body = "<div id='advanced-statistics-system-database-tables' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
// echo elgg_view_module("inline", $title, $body);

// $title = elgg_echo('advanced_statistics:system:database:overhead');
// $body = "<div id='advanced-statistics-system-database-overhead' class='advanced-statistics-plot-container'></div><div class='elgg-ajax-loader'></div>";
// echo elgg_view_module("inline", $title, $body);
