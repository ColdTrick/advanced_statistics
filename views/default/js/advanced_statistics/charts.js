define(['jquery', 'jqplot/jquery.jqplot'], function($) {
	
	var advancedStatistics = {
		init: function (selector) {
			require([
				'elgg/i18n',
				'elgg/Ajax',
				'jqplot/plugins/jqplot.pieRenderer',
				'jqplot/plugins/jqplot.barRenderer',
				'jqplot/plugins/jqplot.categoryAxisRenderer',
				'jqplot/plugins/jqplot.canvasAxisTickRenderer',
				'jqplot/plugins/jqplot.canvasTextRenderer',
				'jqplot/plugins/jqplot.dateAxisRenderer',
				'jqplot/plugins/jqplot.pointLabels',
				'jqplot/plugins/jqplot.highlighter'
			], function(i18n, Ajax) {
				// initialize the plots
				$(selector).each(function(){
			
					var $target = $(this);
					
					if (!$.jqplot) {
						$target.html(i18n.echo('advanced_statistics:widgets:advanced_statistics:content:no_jqplot'));
						return;
					}
					
					var ajax = new Ajax(false);
					ajax.view($(this).data().chartHref, {
						success: function(result){
							var options = result.options;
							console.log(result.options);
							if (options['seriesDefaults']) {
								options['seriesDefaults']['renderer'] = eval(options['seriesDefaults']['renderer']);
							}
							
							if (options['axes']) {
								if (options['axes']['xaxis']) {
									options['axes']['xaxis']['renderer'] = eval(options['axes']['xaxis']['renderer']);
									options['axes']['xaxis']['tickRenderer'] = eval(options['axes']['xaxis']['tickRenderer']);
								}
								
								if (options['axes']['yaxis']) {
									options['axes']['yaxis']['renderer'] = eval(options['axes']['yaxis']['renderer']);
								}
								
								if (options['axes']['y2axis']) {
									options['axes']['y2axis']['renderer'] = eval(options['axes']['y2axis']['renderer']);
								}
							}
							
							if (options['axesDefaults']) {
								options['axesDefaults']['tickRenderer'] = eval(options['axesDefaults']['tickRenderer']);
							}
							console.log(options);
							
							if (result.data[0].length) {
								$target.html(''); // remove loader
								$.jqplot($target.attr('id'), result.data, options);
							} else {
								$target.html(i18n.echo('notfound'));
							}
						},
						data: {
							view: 'json'
						}
					});
				});
			});
		}
	};
	
	return advancedStatistics;
});
