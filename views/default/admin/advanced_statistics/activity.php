<?php

/**
*   Which days most activity?
*   Which hours most activity?
*   Activity count per day (line)
*/

echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:activity:day'), "id" => "advanced-statistics-activity-day"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:activity:hour'), "id" => "advanced-statistics-activity-hour"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:activity:timeline'), "id" => "advanced-statistics-activity-timeline"));
