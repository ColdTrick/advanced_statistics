<?php
/**
 * Elgg statistics screen
 */

$user = elgg_get_page_owner_entity();
if (!$user instanceof ElggUser) {
	return;
}

$entity_stats = get_entity_statistics($user->guid);

if (empty($entity_stats)) {
	return;
}

$rows = [];
$show_admin_help = false;

foreach ($entity_stats as $type => $entry) {
	foreach ($entry as $subtype => $count) {
		$content_type = "{$type} {$subtype}";
		if (elgg_language_key_exists("collection:{$type}:{$subtype}")) {
			$content_type = elgg_echo("collection:{$type}:{$subtype}");
		}
		
		$cells = [];
		
		$registered_subtypes = get_registered_entity_types($type);
		if (in_array($subtype, $registered_subtypes)) {
			// is searcheable, so show to user
			$cells[] = elgg_format_element('td', ['class' => 'column-one'], $content_type);
			$cells[] = elgg_format_element('td', ['class' => 'center'], $count);
		} elseif (elgg_is_admin_logged_in()) {
				$show_admin_help = true;
				// not searchable, only admins get to see this
				$cells[] = elgg_format_element('td', ['class' => ['column-one', 'elgg-subtext']], "{$content_type} *");
				$cells[] = elgg_format_element('td', ['class' => ['center', 'elgg-subtext']], $count);
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

$title = elgg_echo('usersettings:statistics:label:numentities');

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
