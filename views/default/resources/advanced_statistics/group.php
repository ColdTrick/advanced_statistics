<?php

use Elgg\EntityPermissionsException;

if (elgg_get_plugin_setting('enable_group_stats', 'advanced_statistics') === 'no') {
	throw new EntityPermissionsException();
}

$group = elgg_get_page_owner_entity();

elgg_push_entity_breadcrumbs($group);

echo elgg_view_page(elgg_echo('advanced_statistics:group:title'), [
	'content' => elgg_view('advanced_statistics/group', [
		'entity' => $group,
	]),
]);
