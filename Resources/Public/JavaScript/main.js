'use strict';

(function(){
	var domLoaded = setInterval(function(){
		if (document.readyState == "complete") {
			onComplete();
		}
	}, 100);
	function onComplete(){
		clearInterval(domLoaded);
		var input = document.getElementById('fluidRecommendationPassword');
		if (input) {
			input.value = '0';
		}
	}
})();