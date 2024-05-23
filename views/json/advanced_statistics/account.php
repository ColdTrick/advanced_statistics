<?php
/**
 * Fetch graph data for account statistics
 */
use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\EntityPermissionsException;

$user_guid = (int) elgg_extract('user_guid', $vars);

if (empty($user_guid)) {
	throw new BadRequestException();
}

$user = get_user($user_guid);
if (!$user instanceof \ElggUser || !$user->canEdit()) {
	throw new EntityPermissionsException();
}

$section = elgg_extract('section', $vars);
$chart = elgg_extract('chart', $vars);

$view = "advanced_statistics/account/{$section}/{$chart}";
if (!elgg_view_exists($view)) {
	throw new BadRequestException();
}

$vars['user'] = $user;

echo elgg_view($view, $vars);
