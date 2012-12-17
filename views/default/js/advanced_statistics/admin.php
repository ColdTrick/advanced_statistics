<?php

?>
//<script>
elgg.provide("elgg.advanced_statistics");

elgg.advanced_statistics.init = function(){

	// initialize the plots
	$(".advanced-statistics-plot-container").each(function(){
		var target = $(this).attr("id");
		elgg.action("advanced_statistics/get_chart_data", {
			// have to use synchronous here, else the function
			// will return before the data is fetched
			data: {
				chart_id: target,
			},
			success: function(result){
				var options = result.output.options;
				if(options["seriesDefaults"]){
					options["seriesDefaults"]["renderer"] = eval(options["seriesDefaults"]["renderer"]);
					
				}
//				console.log(result.output.data);
				$.jqplot(target, result.output.data, options);
			}
		});
	});
}

//register init hook
elgg.register_hook_handler("init", "system", elgg.advanced_statistics.init);