<?php

use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Exceptions\Http\EntityPermissionsException;

$container_guid = (int) elgg_extract('container_guid', $vars);
if (empty($container_guid)) {
	throw new BadRequestException();
}

$group = get_entity($container_guid);
if (!$group instanceof ElggGroup || !$group->canEdit()) {
	throw new EntityPermissionsException();
}

$section = elgg_extract('section', $vars);
$chart = elgg_extract('chart', $vars);

$view = "advanced_statistics/group/{$section}/{$chart}";
if (!elgg_view_exists($view)) {
	throw new BadRequestException();
}

echo elgg_view($view, $vars);
