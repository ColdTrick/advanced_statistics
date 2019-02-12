<?php

elgg_register_menu_item('title', [
	'name' => 'date_selector',
	'icon' => 'filter',
	'text' => elgg_echo('advanced_statistics:date_selection:title_button'),
	'href' => '#advanced-statistics-date-selection',
	'rel' => 'toggle',
	'link_class' => [
		'elgg-button',
		'elgg-button-action',
	],
]);

$form = elgg_view_form('advanced_statistics/date_selector', [
	'class' => 'mbm',
	'action' => current_page_url(),
	'disable_security' => true,
]);

$classes = ['clearfix'];

if (empty(get_input('ts_lower')) && empty(get_input('ts_upper'))) {
	$classes[] = 'hidden';
}

echo elgg_format_element('div', [
	'id' => 'advanced-statistics-date-selection',
	'class' => $classes,
], $form);
