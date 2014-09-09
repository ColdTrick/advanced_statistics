<?php

/**
*   Widget handler counts (pie)
*   Widget context
*/

echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:widgets:handlers'), "id" => "advanced-statistics-widgets-handlers"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:widgets:context'), "id" => "advanced-statistics-widgets-context"));
