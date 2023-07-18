<?php

use Elgg\Database\QueryBuilder;
use Elgg\Values;

$count = elgg_count_entities([
	'type' => 'user',
	'wheres' => [
		function(QueryBuilder $qb, $main_alias) {
			return $qb->compare("{$main_alias}.last_action", '>=', Values::normalizeTimestamp('-10 minutes'), ELGG_VALUE_TIMESTAMP);
		}
	],
]);

echo elgg_echo('admin:statistics:label:onlineusers') . ": <strong>{$count}</strong>";

?>
<script>
	setTimeout(function(){
		$(".elgg-widget-instance-online-user-count .elgg-widget-content").load(elgg.normalize_url("ajax/view/widgets/online_user_count/content"));
	}, 60 * 1000);
</script>
