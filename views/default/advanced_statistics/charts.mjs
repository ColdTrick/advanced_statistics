import 'jquery';
import Ajax from 'elgg/Ajax';
import 'chartjs';

var advancedStatistics = {
	init: function (selector) {
		// initialize the charts
		$(selector).each(function(){
			var $target = $(this);
			
			var ajax = new Ajax(false);
			ajax.view($(this).data().chartHref, {
				success: function(result){
					new Chart($target, result);
				},
				data: {
					view: 'json'
				}
			});
		});
		
		
		return;
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		var $elem = $(selector);
		var data = $elem.data();
		if (data.initialized) {
			return;
		}
		
		if (!$elem.is(':visible')) {
			return;
		}
		
		var ctx = $elem.getContext('2d');
		
		switch (data.chartType) {
			case 'pie':
				var chart = new Chart(ctx, {
					type: data.chartType,
					data: data.chartData,
					options: {
						maintainAspectRatio: false,
						plugins: {
							legend: {
								display: false,
							},
						},
					}
				});
				break;
			case 'bar':
				var chart = new Chart(ctx, {
					type: data.chartType,
					data: data.chartData,
					options: {
						maintainAspectRatio: false,
						plugins: {
							legend: {
								display: false,
							},
						},
						scales: {
							y: {
								beginAtZero: true,
							},
						}
					},
				});
				break;
		}
		
		$elem.data('initialized', true);
	}
};

export default advancedStatistics;
