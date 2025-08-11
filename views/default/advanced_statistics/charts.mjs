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
				success: function(result) {

					// check if it has empty results
					if (typeof result.data.datasets !== 'undefined') {
						var totalData = 0;
						result.data.datasets.forEach((elem) => {
							if (typeof elem.data !== 'undefined') {
								totalData += elem.data.length;
							}
						});

						if (totalData === 0) {
							// no results found
							import('elgg/i18n').then((i18n) => {
								$target.parent().replaceWith('<p class="elgg-no-results">' + i18n.default.echo('notfound') + '</p>');
							});

							return;
						}
					}

					new Chart($target, result);
					
					$target.prev('.elgg-ajax-loader').addClass('hidden');
				},
				data: {
					view: 'json'
				}
			});
		});
	}
};

export default advancedStatistics;
