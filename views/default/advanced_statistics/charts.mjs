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
	}
};

export default advancedStatistics;
