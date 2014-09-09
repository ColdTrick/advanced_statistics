<?php

/**
*   count created content (pie)
*   distribution (groups vs personal)
*   content usage in groups (% blog, %file etc)
*   content usage personal
*/

echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:content:totals'), "id" => "advanced-statistics-content-totals"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:content:distribution'), "id" => "advanced-statistics-content-distribution"));
