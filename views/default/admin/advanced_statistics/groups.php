<?php

/**
*        10 most popular groups (bar)
    enabled tools for group (pie)
    10 most active groups (most activity in last week) (bar)
    10 least active groups (least activity in last week)
    Dead vs Alive groups (last activity < 1 month <3 <6 <12) (pie)
*/

echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:groups:popular'), "id" => "advanced-statistics-groups-popular"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:groups:popular_tools'), "id" => "advanced-statistics-groups-popular-tools"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:groups:most_active'), "id" => "advanced-statistics-groups-most-active"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:groups:least_active'), "id" => "advanced-statistics-groups-least-active"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:groups:dead_vs_alive'), "id" => "advanced-statistics-groups-dead-vs-alive"));
