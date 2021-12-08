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

echo elgg_view('advanced_statistics/date_selector');

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:users:popular'),
	'id' => 'advanced-statistics-users-popular',
	'page' => 'admin_data',
	'section' => 'users',
	'chart' => 'popular',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:users:most_used_domains'),
	'id' => 'advanced-statistics-users-most-used-domains',
	'page' => 'admin_data',
	'section' => 'users',
	'chart' => 'most_used_domains',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:users:account_creation'),
	'id' => 'advanced-statistics-users-account-creation',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'users',
	'chart' => 'account_creation',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:users:account_status'),
	'id' => 'advanced-statistics-users-account-status',
	'page' => 'admin_data',
	'section' => 'users',
	'chart' => 'account_status',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:users:account_activity'),
	'id' => 'advanced-statistics-users-account-activity',
	'date_limited' => true,
	'page' => 'admin_data',
	'section' => 'users',
	'chart' => 'account_activity',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:users:language_distribution'),
	'id' => 'advanced-statistics-users-language-distribution',
	'page' => 'admin_data',
	'section' => 'users',
	'chart' => 'language_distribution',
]);

echo elgg_view('advanced_statistics/elements/chart', [
	'title' => elgg_echo('advanced_statistics:users:profile_field_usage'),
	'id' => 'advanced-statistics-users-profile-field-usage',
	'page' => 'admin_data',
	'section' => 'users',
	'chart' => 'profile_field_usage',
]);

if (elgg_is_active_plugin('friends')) {
	echo elgg_view('advanced_statistics/elements/chart', [
		'title' => elgg_echo('advanced_statistics:users:friend_bundled'),
		'id' => 'advanced-statistics-users-friends-bundled',
		'page' => 'admin_data',
		'section' => 'users',
		'chart' => 'friends_bundled',
	]);
}

if (elgg_is_active_plugin('groups')) {
	echo elgg_view('advanced_statistics/elements/chart', [
		'title' => elgg_echo('advanced_statistics:users:groups_bundled'),
		'id' => 'advanced-statistics-users-groups-bundled',
		'page' => 'admin_data',
		'section' => 'users',
		'chart' => 'groups_bundled',
	]);
}
