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

echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:users:popular'), "id" => "advanced-statistics-users-popular"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:users:most_used_domains'), "id" => "advanced-statistics-users-most-used-domains"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:users:account_creation'), "id" => "advanced-statistics-users-account-creation"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:users:account_status'), "id" => "advanced-statistics-users-account-status"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:users:account_activity'), "id" => "advanced-statistics-users-account-activity"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:users:language_distribution'), "id" => "advanced-statistics-users-language-distribution"));
echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo('advanced_statistics:users:profile_field_usage'), "id" => "advanced-statistics-users-profile-field-usage"));
