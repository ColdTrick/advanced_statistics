define(function(require) {
	
	var $ = require('jquery');
	var elgg = require('elgg');
	var Ajax = require('elgg/Ajax');
	require('jqplot/jquery.jqplot');
	
	var init = function () {
		require(['jqplot/plugins/jqplot.pieRenderer', 
		 		'jqplot/plugins/jqplot.barRenderer', 
		 		'jqplot/plugins/jqplot.categoryAxisRenderer', 
		 		'jqplot/plugins/jqplot.canvasAxisTickRenderer', 
		 		'jqplot/plugins/jqplot.canvasTextRenderer', 
		 		'jqplot/plugins/jqplot.dateAxisRenderer', 
		 		'jqplot/plugins/jqplot.pointLabels', 
		 		'jqplot/plugins/jqplot.highlighter' ], function() {
		 			
			// initialize the plots
			$('.advanced-statistics-plot-container').each(function(){
		
				var $target = $(this);
				
				if (!$.jqplot) {
					$target.html(elgg.echo('advanced_statistics:widgets:advanced_statistics:content:no_jqplot'));
					return;
				}
				
				var ajax = new Ajax();
				ajax.view($(this).data().chartHref, {
					success: function(result){
						var options = result.options;
						
						if(options['seriesDefaults']){
							options['seriesDefaults']['renderer'] = eval(options['seriesDefaults']['renderer']);
						}
						
						if(options['axes']){
							if(options['axes']['xaxis']){
								options['axes']['xaxis']['renderer'] = eval(options['axes']['xaxis']['renderer']);
								options['axes']['xaxis']['tickRenderer'] = eval(options['axes']['xaxis']['tickRenderer']);
							}
							if(options['axes']['yaxis']){
								options['axes']['yaxis']['renderer'] = eval(options['axes']['yaxis']['renderer']);
							}
							if(options['axes']['y2axis']){
								options['axes']['y2axis']['renderer'] = eval(options['axes']['y2axis']['renderer']);
							}
						}
						
						if(options['axesDefaults']){
							options['axesDefaults']['tickRenderer'] = eval(options['axesDefaults']['tickRenderer']);
						}
						
						if(result.data[0].length){
							$.jqplot($target.attr('id'), result.data, options);
						} else {
							$target.html(elgg.echo('notfound'));
						}
					},
					data: {
						view: 'json'
					}
				});
			});
		});
	};

	init();
});