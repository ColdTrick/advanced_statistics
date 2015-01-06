<?php

$count = find_active_users(array(), 10, 0, true);

echo elgg_echo("admin:statistics:label:onlineusers") . ": <strong>$count</strong>";

?>
<script type="text/javascript">
	setTimeout(function(){ 
	
		$(".elgg-widget-instance-online_user_count > .elgg-body > .elgg-widget-content").load(elgg.normalize_url("ajax/view/widgets/online_user_count/content"));
	
	}, 60 * 1000);
</script>