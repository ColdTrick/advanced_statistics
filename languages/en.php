<?php

return [
	
	// settings
	'advanced_statistics:settings:enable_group_stats' => "Enable statistics for group admins",
	'advanced_statistics:settings:include_banned_users' => "Include banned users in statistics",
	'advanced_statistics:settings:include_banned_users:help' => "Include banned users in certain user related statistics (eg. profile field usage).",
	
	// generic
	'advanced_statistics:chart:exclude_banned_users' => "banned users are excluded from this chart",
	'advanced_statistics:chart:since' => "since",
	'advanced_statistics:chart:until' => "until",
	
	// group statistics
	'advanced_statistics:group:title' => "Group statistics",
	
	'advanced_statistics:group:members' => "Members",
	'advanced_statistics:group:contenttype' => "Content types",
	'advanced_statistics:group:content_creation' => "Content creation",
	'advanced_statistics:group:activity' => "Activity",
	
	// admin menu items
	'admin:advanced_statistics' => 'Advanced Statistics',
	'admin:advanced_statistics:users' => 'Users',
	'admin:advanced_statistics:groups' => 'Groups',
	'admin:advanced_statistics:content' => 'Content',
	'admin:advanced_statistics:activity' => 'Activity',
	'admin:advanced_statistics:widgets' => 'Widgets',
	'admin:advanced_statistics:system' => 'System',
	'admin:advanced_statistics:notifications' => 'Notifications',
	
	// date selection
	'advanced_statistics:date_selection:title_button' => 'Show date selection',
	'advanced_statistics:date_selection:start' => 'Start',
	'advanced_statistics:date_selection:end' => 'End',

	// users
	'advanced_statistics:users:popular' => 'Popular Users',
	'advanced_statistics:users:most_used_domains' => 'Most used email domains',
	'advanced_statistics:users:account_creation' => 'Account creation over time',
	'advanced_statistics:users:account_status' => 'Account status',
	'advanced_statistics:users:account_activity' => 'Account activity',
	'advanced_statistics:users:language_distribution' => 'Language distribution',
	'advanced_statistics:users:profile_field_usage' => 'Profile field usage',

	// activity
	'advanced_statistics:activity:day' => 'Activity per day',
	'advanced_statistics:activity:hour' => 'Activity per hour',
	'advanced_statistics:activity:timeline' => 'Activity over time',
	
	// widgets
	'advanced_statistics:widgets:handlers' => 'Widget handlers',
	'advanced_statistics:widgets:context' => 'Widget context',

	// content
	'advanced_statistics:content:totals' => 'Content totals',
	'advanced_statistics:content:distribution' => 'Content container distribution',
	'advanced_statistics:content:subscriptions' => 'Content subscriptions',
	'advanced_statistics:content:subscriptions:help' => 'Shows the number of subscribers on content over time, excluding the owner subscriptions.',
	'advanced_statistics:content:block_subscriptions' => 'Content block subscriptions',
	'advanced_statistics:content:active_groups' => 'Top 10 groups with the most new content',
	'advanced_statistics:content:commenting_groups' => 'Top 10 groups with the most comments',

	// system
	'advanced_statistics:system:files:users' => 'Users with the most files and photos',
	'advanced_statistics:system:files:groups' => 'Groups with the most files and photos',

	// groups
	'advanced_statistics:groups:popular' => "Popular groups",
	'advanced_statistics:groups:created' => "New groups per month",
	'advanced_statistics:groups:created:new' => "New groups",
	'advanced_statistics:groups:popular_tools' => "Popular group tools",
	'advanced_statistics:groups:most_active' => "Most active groups (last week)",
	'advanced_statistics:groups:least_active' => "Least active groups",
	'advanced_statistics:groups:dead_vs_alive' => "Dead vs. Alive",
	'advanced_statistics:groups:dead_vs_alive:last_month' => "< 1 month [%d]",
	'advanced_statistics:groups:dead_vs_alive:3_months' => "< 3 months [%d]",
	'advanced_statistics:groups:dead_vs_alive:6_months' => "< 6 months [%d]",
	'advanced_statistics:groups:dead_vs_alive:year' => "< 1 year [%d]",
	'advanced_statistics:groups:dead_vs_alive:more_year' => "> 1 year [%d]",

	// widgets
	'widgets:advanced_statistics:name' => "Advanced Statistics",
	'widgets:advanced_statistics:description' => "Show some advanced statistics",
	'advanced_statistics:widgets:advanced_statistics:content:no_chart' => "Edit the widget to select a chart.",
	'advanced_statistics:widgets:advanced_statistics:content:no_jqplot' => "Please refresh the page to make this widget work.",
	
	'widgets:online_user_count:name' => "Online Users Counter",
	'widgets:online_user_count:description' => "Show the amount of online users and will automatically refresh",
	
	// user statistics
	'advanced_statistics:numentities:admin_help' => "* non searchable entities, only shown to site administrators",
	
	'advanced_statistics:account:statistics:details:title' => "Detailed content statistics for %s",
	'advanced_statistics:account:statistics:details:week' => "Last 7 days",
	'advanced_statistics:account:statistics:details:month' => "Last 30 days",
	'advanced_statistics:account:statistics:details:this_year' => "This year",
	'advanced_statistics:account:statistics:details:last_year' => "Last year",
	'advanced_statistics:account:statistics:details:chart:years' => "Year totals",
	
	'advanced_statistics:account:likes:top:title' => "Top 5 most liked content",
	'advanced_statistics:account:likes:top:description' => "Below is a list of content you wrote which others have liked in the past %d days ordered by the most liked content first.",
	'advanced_statistics:account:likes:chart' => "Likes over time",
	
	'advanced_statistics:users:friend_bundled' => "Aggregated number of friends",
	'advanced_statistics:users:groups_bundled' => "Aggregated group memberships",
	
	// notifications
	'advanced_statistics:notifications:not_configured' => "Not configured",
	'advanced_statistics:notifications:generic_count' => "Generic",
	'advanced_statistics:notifications:specific_count' => "Specific",
	'advanced_statistics:notifications:generic_vs_specific:help' => "The way users configured notification preferences. Generic means all notifications, specific means only certain notifications (eg. blog creation).",
	
	'advanced_statistics:notifications:delayed_interval' => "Configured delayed delivery interval",
	
	'advanced_statistics:notifications:timed_muting' => "Temporarily disabled notification usage",
	'advanced_statistics:notifications:timed_muting:previous' => "Configured in the past",
	'advanced_statistics:notifications:timed_muting:active' => "Currently active",
	'advanced_statistics:notifications:timed_muting:scheduled' => "Scheduled for use",
	
	'advanced_statistics:notifications:user_configured_methods' => "Configured delivery methods",
	'advanced_statistics:notifications:users_generic_vs_specific' => "Friends notification preferences",
	'advanced_statistics:notifications:groups_generic_vs_specific' => "Groups notification preferences",
];
