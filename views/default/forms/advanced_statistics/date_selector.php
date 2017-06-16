<?php

echo elgg_echo('advanced_statistics:date_selection:start');
echo elgg_view('input/date', [
	'name' => 'ts_lower',
	'value' => get_input('ts_lower'),
	'class' => 'mlm',
]);

echo elgg_echo('advanced_statistics:date_selection:end');
echo elgg_view('input/date', [
	'name' => 'ts_upper',
	'value' => get_input('ts_upper'),
	'class' => 'mlm',
]);

echo elgg_view('input/submit', ['class' => 'mlm']);
