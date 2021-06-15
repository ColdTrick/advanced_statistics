<?php

use Elgg\Exceptions\Http\BadRequestException;

elgg_admin_gatekeeper();

$section = elgg_extract('section', $vars);
$chart = elgg_extract('chart', $vars);

$view = "advanced_statistics/admin/{$section}/{$chart}";
if (!elgg_view_exists($view)) {
	throw new BadRequestException();
}

echo elgg_view($view, $vars);
