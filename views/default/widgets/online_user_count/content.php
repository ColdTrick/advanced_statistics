<?php

$count = find_active_users(['count' => true]);

echo elgg_echo("admin:statistics:label:onlineusers") . ": <strong>$count</strong>";

?>
<script type="text/javascript">
	setTimeout(function(){
		$(".elgg-widget-instance-online-user-count .elgg-widget-content").load(elgg.normalize_url("ajax/view/widgets/online_user_count/content"));
	
	}, 60 * 1000);
</script>