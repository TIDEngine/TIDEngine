$(function(){
	$('.demo1').scroller();
	
	$('.demo2').scroller({
		container: {
			easing: 'easeOutExpo'
		},
		options: {
			margin: 10,
			zoom: 2,
			easing: ['easeInSine', 'easeOutElastic'],
			duration: [200, 1000]
		},
		onclick: function(a, img){
			var alt = img.attr('alt'), h2 = $('.title');
			h2.text(alt);
		}
	});
	
	$('.demo3').scroller({
		container: {
			easing: 'easeOutElastic'
		},
		direction: 'vertical'
	});
	
});
