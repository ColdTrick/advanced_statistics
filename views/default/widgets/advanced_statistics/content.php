<?php

advanced_statistics_load_externals();

$widget = $vars["entity"];

$chart = $widget->chart;

if (!empty($chart)) {
	
	list($id, $text) = explode("|", $chart);
	
	echo elgg_view("advanced_statistics/elements/chart", array("title" => elgg_echo("advanced_statistics:" . $text), "id" => "advanced-statistics-" . $id));
	
	if (elgg_is_xhr()) {
		?>
		<script type="text/javascript">
			elgg.advanced_statistics.init();
		</script>
		<?php
	}
} else {
	echo elgg_echo("advanced_statistics:widgets:advanced_statistics:content:no_chart");
}