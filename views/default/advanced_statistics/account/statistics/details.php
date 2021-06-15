<?php
/**
 * Ajax view to show more details about content statistics of a user
 */

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\EntityPermissionsException;

$user_guid = (int) elgg_extract('user_guid', $vars);
$type = elgg_extract('type', $vars);
$subtype = elgg_extract('subtype', $vars);

if (empty($user_guid) || empty($type) || empty($subtype)) {
	throw new BadRequestException();
}

$user = get_user($user_guid);
if (!$user instanceof ElggUser || !$user->canEdit()) {
	throw new EntityPermissionsException();
}

$get_count = function(array $options = []) use ($type, $subtype, $user) {
	$options = array_merge([
		'type' => $type,
		'subtype' => $subtype,
		'owner_guid' => $user->guid,
	], $options);
	
	return elgg_count_entities($options);
};

$result = '';
$result .= '<table class="elgg-table">';

// total
$result .= '<tr>';
$result .= '<th>' . elgg_echo('total') . '</th>';
$result .= '<td>' . $get_count() . '</td>';
$result .= '</tr>';

// this week
$result .= '<tr>';
$result .= '<th>' . elgg_echo('advanced_statistics:account:statistics:details:week') . '</th>';
$result .= '<td>' . $get_count([
	'created_after' => 'today -7 days',
]) . '</td>';
$result .= '</tr>';

// this month
$result .= '<tr>';
$result .= '<th>' . elgg_echo('advanced_statistics:account:statistics:details:month') . '</th>';
$result .= '<td>' . $get_count([
	'created_after' => 'today -30 days',
]) . '</td>';
$result .= '</tr>';

// this year
$result .= '<tr>';
$result .= '<th>' . elgg_echo('advanced_statistics:account:statistics:details:this_year') . '</th>';
$result .= '<td>' . $get_count([
	'created_after' => 'first day of january this year',
]) . '</td>';
$result .= '</tr>';

// last year
$result .= '<tr>';
$result .= '<th>' . elgg_echo('advanced_statistics:account:statistics:details:last_year') . '</th>';
$result .= '<td>' . $get_count([
	'created_after' => 'first day of january last year',
	'created_before' => 'first day of january this year',
]) . '</td>';
$result .= '</tr>';

$result .= '</table>';

$content_type = "{$type} {$subtype}";
if (elgg_language_key_exists("collection:{$type}:{$subtype}")) {
	$content_type = elgg_echo("collection:{$type}:{$subtype}");
}

echo elgg_view_module('info', elgg_echo('advanced_statistics:account:statistics:details:title', [$content_type]), $result);

if ($get_count(['created_before' => 'first day of january this year'])) {
	echo elgg_view('advanced_statistics/elements/chart', [
		'title' => elgg_echo('advanced_statistics:account:statistics:details:chart:years'),
		'id' => 'advanced-statistics-account-statistics-details-years',
		'date_limited' => false,
		'page' => 'account',
		'section' => 'details',
		'chart' => 'years',
		'url_elements' => [
			'user_guid' => $user->guid,
			'type' => $type,
			'subtype' => $subtype,
		],
	]);
}
