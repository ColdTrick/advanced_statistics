<?php

echo elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => [
		[
			'#type' => 'date',
			'#label' => elgg_echo('advanced_statistics:date_selection:start'),
			'name' => 'ts_lower',
			'value' => get_input('ts_lower'),
		],
		[
			'#type' => 'date',
			'#label' => elgg_echo('advanced_statistics:date_selection:end'),
			'name' => 'ts_upper',
			'value' => get_input('ts_upper'),
		],
		[
			'#type' => 'submit',
			'value' => elgg_echo('submit'),
		],
	],
]);
