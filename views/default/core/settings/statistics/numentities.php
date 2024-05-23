<?php
/**
 * Elgg statistics screen
 *
 * @uses $vars['entity'] The user entity for whom to show statistics
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity()); // page owner for BC reasons
if (!$user instanceof \ElggUser) {
	return;
}

$options = [
	'owner_guid' => $user->guid,
];

if (!elgg_is_admin_logged_in()) {
	$options['type_subtype_pairs'] = elgg_entity_types_with_capability('searchable');
}

$entity_stats = elgg_get_entity_statistics($options);
if (empty($entity_stats)) {
	return;
}

$rows = [];
$show_admin_help = false;

foreach ($entity_stats as $type => $subtypes) {
	foreach ($subtypes as $subtype => $count) {
		$cells = [];
		
		$label = "{$type} {$subtype}";
		if (elgg_language_key_exists("collection:{$type}:{$subtype}")) {
			$label = elgg_echo("collection:{$type}:{$subtype}");
		} elseif (elgg_language_key_exists("item:{$type}:{$subtype}")) {
			$label = elgg_echo("item:{$type}:{$subtype}");
		}
		
		$registered_subtypes = elgg_extract($type, elgg_entity_types_with_capability('searchable'), []);
		if (in_array($subtype, $registered_subtypes)) {
			// is searchable, so show to user
			$cells[] = elgg_format_element('td', ['class' => 'column-one'], $label);
			$cells[] = elgg_format_element('td', ['class' => 'center'], $count);
		} elseif (elgg_is_admin_logged_in()) {
			$show_admin_help = true;
			// not searchable, only admins get to see this
			$cells[] = elgg_format_element('td', ['class' => 'column-one'], elgg_format_element('span', ['class' => 'elgg-quiet'], "{$label} *"));
			$cells[] = elgg_format_element('td', ['class' => 'center'], elgg_format_element('span', ['class' => 'elgg-quiet'], $count));
		}
		
		if (empty($cells)) {
			continue;
		}
		
		$cells[] = elgg_format_element('td', ['class' => 'center'], elgg_view('output/url', [
			'text' => elgg_echo('more_info'),
			'href' => elgg_http_add_url_query_elements('ajax/view/advanced_statistics/account/statistics/details', [
				'type' => $type,
				'subtype' => $subtype,
				'user_guid' => $user->guid,
			]),
			'class' => 'elgg-lightbox',
		]));
		
		$rows[] = elgg_format_element('tr', [], implode(PHP_EOL, $cells));
	}
}

if (empty($rows)) {
	return;
}

if ($user->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('usersettings:statistics:label:numentities');
} else {
	$title = elgg_echo('usersettings:statistics:numentities:user', [$user->getDisplayName()]);
}

$table_contents = elgg_format_element('thead', [], elgg_format_element('tr', [], implode(PHP_EOL, [
	elgg_format_element('th', [], elgg_echo('admin:statistics:numentities:type')),
	elgg_format_element('th', ['class' => 'center'], elgg_echo('admin:statistics:numentities:number')),
	elgg_format_element('th', ['class' => 'center'], '&nbsp;'),
])));
$table_contents .= elgg_format_element('tbody', [], implode(PHP_EOL, $rows));

$content = elgg_format_element('table', ['class' => 'elgg-table'], $table_contents);

if ($show_admin_help) {
	$content .= elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_echo('advanced_statistics:numentities:admin_help'));
}

echo elgg_view_module('info', $title, $content);
