<?php

// select which stats you wish to see

$chart_options = array(
	"activity-day|activity:day" => elgg_echo('advanced_statistics:activity:day'),
	"activity-hour|activity:hour" => elgg_echo('advanced_statistics:activity:hour'),
	"activity-timeline|activity:timeline" => elgg_echo('advanced_statistics:activity:timeline'),
	"content-totals|content:totals" => elgg_echo('advanced_statistics:content:totals'),
	"content-distribution|content:distribution" => elgg_echo('advanced_statistics:content:distribution'),
	"groups-popular|groups:popular" => elgg_echo('advanced_statistics:groups:popular'),
	"groups-popular-tools|groups:popular_tools" => elgg_echo('advanced_statistics:groups:popular_tools'),
	"groups-most-active|groups:most_active" => elgg_echo('advanced_statistics:groups:most_active'),
	"groups-least-active|groups:least_active" => elgg_echo('advanced_statistics:groups:least_active'),
	"groups-dead-vs-alive|groups:dead_vs_alive" => elgg_echo('advanced_statistics:groups:dead_vs_alive'),
	"system-files-users|system:files:users" => elgg_echo('advanced_statistics:system:files:users'),
	"system-files-groups|system:files:groups" => elgg_echo('advanced_statistics:system:files:groups'),
	"users-popular|users:popular" => elgg_echo('advanced_statistics:users:popular'),
	"users-most-used-domains|users:most_used_domains" => elgg_echo('advanced_statistics:users:most_used_domains'),
	"users-account-creation|users:account_creation" => elgg_echo('advanced_statistics:users:account_creation'),
	"users-account-status|users:account_status" => elgg_echo('advanced_statistics:users:account_status'),
	"users-account-activity|users:account_activity" => elgg_echo('advanced_statistics:users:account_activity'),
	"users-language-distribution|users:language_distribution" => elgg_echo('advanced_statistics:users:language_distribution'),
	"users-profile-field-usage|users:profile_field_usage" => elgg_echo('advanced_statistics:users:profile_field_usage'),
	"widgets-handlers|widgets:handlers" => elgg_echo('advanced_statistics:widgets:handlers'),
	"widgets-context|widgets:context" => elgg_echo('advanced_statistics:widgets:context'),
		
);

echo elgg_view("input/dropdown", array("name" => "params[chart]", "options_values" => $chart_options, "value" => $vars["entity"]->chart));