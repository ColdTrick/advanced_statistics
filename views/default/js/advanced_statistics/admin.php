<?php

?>
//<script>
elgg.provide("elgg.advanced_statistics");

elgg.advanced_statistics.init = function(){

	// initialize the plots
	$(".advanced-statistics-plot-container").each(function(){
		var target = $(this).attr("id");
		var parts = target.split("-");
		
		elgg.getJSON("advanced_statistics/" + parts[2] + "/" + target, {
			success: function(result){
				var options = result.options;
				if(options["seriesDefaults"]){
					options["seriesDefaults"]["renderer"] = eval(options["seriesDefaults"]["renderer"]);
				}
				if(options["axes"]){
					if(options["axes"]["xaxis"]){
						options["axes"]["xaxis"]["renderer"] = eval(options["axes"]["xaxis"]["renderer"]);
						options["axes"]["xaxis"]["tickRenderer"] = eval(options["axes"]["xaxis"]["tickRenderer"]);
					}
					if(options["axes"]["yaxis"]){
						options["axes"]["yaxis"]["renderer"] = eval(options["axes"]["yaxis"]["renderer"]);
					}
					if(options["axes"]["y2axis"]){
						options["axes"]["y2axis"]["renderer"] = eval(options["axes"]["y2axis"]["renderer"]);
					}
				}
				if(options["axesDefaults"]){
					options["axesDefaults"]["tickRenderer"] = eval(options["axesDefaults"]["tickRenderer"]);
				}
				
				$.jqplot(target, result.data, options);
				// hide loader
				$("#"+ target).next().hide();
			}
		});
	});
}

//register init hook
elgg.register_hook_handler("init", "system", elgg.advanced_statistics.init);