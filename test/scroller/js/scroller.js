/* 
 * Scroller
 * author: Marcin Dziewulski
 * web: http://www.jscraft.net
 * email: info@jscraft.net
 * license: http://www.jscraft.net/licensing.html
 */

(function($){
    $.fn.scroller = function(options) {
		var D = {
			element: 'a',
			direction: 'horizontal',
			container: {
				name: 'inside',
				easing: 'easeOutBack',
				duration: 800
			},
			options: {
				margin: -20,
				zoom: 1.5,
				easing: ['easeOutBack', 'easeOutBounce'],
				duration: [300, 500]
			},
			onclick: function(a, img){},
			onmouseover: function(a, img){},
			onmouseout: function(a, img){}
		} // default settings
		
		var S = $.extend(true, D, options); 
		
        return this.each(function(){
			var M = $(this),
				IN = M.find('.'+S.container.name),
				E = M.find(S.element),
				P = {
					init: function(){
						this._globals.init();
						this._container.init();
						this._position.init();
						this.events.init();
					},
					_globals: {
						init: function(){
							D = {
								w: M.width(),
								h: M.height()
							},
							I = {
								w: E.width(),
								h: E.height()
							},
							DIR = S.direction,
							MW = I.w+S.options.margin,
							MH = I.h+S.options.margin;
						}
					},
					_container: {
						init: function(){
							this.dimensions();
							this.center();
						},
						dimensions: function(){
							var css = {}
							if (DIR == 'horizontal'){
								css.width = E.length*MW;
							} else if (DIR == 'vertical') {
								css.height = E.length*MH;
							}
							IN.css(css);
							C = {
								w: IN.width(),
								h: IN.height()
							}
						},
						center: function(){
							var css = {}, l = E.length;
							if (DIR == 'horizontal'){
								css.left = -(l*MW)/l*2-MW/2;
							} else if (DIR == 'vertical') {
								css.top = -(l*MH)/l*2;
							}
							IN.css(css);
						}
					},
					_position: {
						init: function(){
							this.set();
						},
						set: function(){
							E.each(function(i){
								var t = $(this),
									img = t.find('img'),
									src = img.attr('src');
								if (DIR == 'horizontal'){
									var x = MW*i,
										css = {
											left: parseInt(x),
											top: 0
										}
								} else if (DIR == 'vertical'){
									var y = MH*i,
										css = {
											left: 0,
											top: parseInt(y)
										}
								}
								css.background = 'url('+src+') no-repeat center';
								img.hide();
								t.css(css);
							});
						}
					},
					_helper: {
						zoomin: function(){
							var zoom = S.options.zoom,
								easing = S.options.easing[0],
								duration = S.options.duration[0],
								animation = {
									width: I.w*zoom,
									height: I.h*zoom,
									marginLeft:(I.w-I.w*zoom)/2,
									marginTop:(I.h-I.h*zoom)/2
								},
								css = {
									zIndex: 10
								}
							return {
								animation: animation,
								easing: easing,
								css: css,
								duration: duration
							}
						},
						zoomout: function(){
							var easing = S.options.easing[1],
								duration = S.options.duration[1],
								animation = {
									width: I.w,
									height: I.h,
									marginLeft: 0,
									marginTop: 0
								},
								css = {
									zIndex: 1
								}
							return {
								animation: animation,
								easing: easing,
								css: css,
								duration: duration
							}
						},
						animate: function(t, o){
							t.css(o.css).stop(true, true).animate(o.animation, o.duration, o.easing);
						}
					},
					events: {
						init: function(){
							this.hover();
							this.click();
						},
						hover: function(){
							E.bind('mouseover mouseleave', function(e){
								var t = $(this), img = t.find('img');
									if (e.type == 'mouseover'){
										var h = P._helper.zoomin();
										S.onmouseover.call(this, t, img);
									} else {
										var h = P._helper.zoomout();
										S.onmouseout.call(this, t, img);
									}
								if (!t.hasClass('active')) {
									P._helper.animate(t, h);
								}
							});	
						},
						click: function(){
							E.click(function(){
								var t = $(this), img = t.find('img'), container = S.container,
									position = t.position(), y = position.top, x = position.left,
									animate = {};
								if (DIR == 'horizontal'){
									animate.left = -x+D.w/2-MW/2;
								} else if (DIR == 'vertical') {
									animate.top = -y+D.h/2-MH/2;
								}
								if (!t.hasClass('active')){
									var zoomin = P._helper.zoomin(),
										zoomout = P._helper.zoomout();
									
									P._helper.animate(E, zoomout);
									P._helper.animate(t, zoomin);
									
									E.removeClass('active');
									t.addClass('active');
								}
								IN.animate(animate, container.duration, container.easing);
								S.onclick.call(this, t, img);
								return false;
							});
						}
					}
				}
			P.init();
        });
    };
}(jQuery));