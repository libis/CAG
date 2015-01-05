/* 
 * jQuery - Collapser - Plugin v1.0
 * http://www.aakashweb.com/
 * Copyright 2010, Aakash Chakravarthy
 * Released under the MIT License.
 */

(function(jQuery){
	jQuery.fn.collapser= function(options, beforeCallback, afterCallback) {
        
        var defaults = {
            target: 'next',
			targetOnly: null,
            effect: 'slide',
			changeText: true,
			expandHtml: 'Expand',
			collapseHtml: 'Collapse',
			expandClass: '',
			collapseClass:''
        };
        
        var options = jQuery.extend(defaults, options);
		
		var expHtml,collHtml, effectShow, effectHide;
		
		if(options.effect == 'slide'){
			effectShow = 'slideDown';
			effectHide = 'slideUp';
		}else{
			effectShow = 'fadeIn';
			effectHide = 'fadeOut';
		}
		
		if(options.changeText == true){
			expHtml = options.expandHtml;
			collHtml = options.collapseHtml;
		}
		
		function callBeforeCallback(obj){
			if(beforeCallback !== undefined){
				beforeCallback.apply(obj);
			}
		}
		
		function callAfterCallback(obj){
			if(afterCallback !== undefined){
				afterCallback.apply(obj);
			}
		}
		
		function hideElement(obj, method){
			callBeforeCallback(obj);
			if(method == 1){
				obj[options.target](options.targetOnly)[effectHide]();
				obj.html(expHtml);
				obj.removeClass(options.collapseClass);
				obj.addClass(options.expandClass);
			}else{
				jQuery(document).find(options.target)[effectHide]();
				obj.html(expHtml);
				obj.removeClass(options.collapseClass);
				obj.addClass(options.expandClass);
			}
			callAfterCallback(obj);
		}
		
		function showElement(obj, method){
			callBeforeCallback(obj)
			if(method == 1){
				obj[options.target](options.targetOnly)[effectShow]();
				obj.html(collHtml);
				obj.removeClass(options.expandClass);
				obj.addClass(options.collapseClass);
			}else{
				jQuery(document).find(options.target)[effectShow]();
				obj.html(collHtml);
				obj.removeClass(options.expandClass);
				obj.addClass(options.collapseClass);
			}
			callAfterCallback(obj);
		}
		
		function toggleElement(obj, method){
			if(method == 1){
				if(obj[options.target](options.targetOnly).is(':visible')){
					hideElement(obj, 1);
				}else{
					showElement(obj, 1);
				}
			}else{
				if(jQuery(document).find(options.target).is(':visible')){
					hideElement(obj, 2);
				}else{
					showElement(obj, 2);
				}
			}
		}
		
		return this.each(function(){
		   
		   if(jQuery.fn[options.target] && jQuery(this)[options.target]()){
			   jQuery(this).toggle(function(){		
					toggleElement(jQuery(this), 1);
				},function(){
					toggleElement(jQuery(this), 1);
				});	
				
		   }else{
			   
			   jQuery(this).toggle(function(){
					toggleElement(jQuery(this), 2);
				},function(){
					toggleElement(jQuery(this), 2);
				});
		   }
		   
		   // Initialize  
		   if(jQuery.fn[options.target] && jQuery(this)[options.target]()){
				if(jQuery(this)[options.target]().is(':hidden')){
					jQuery(this).html(expHtml);
					jQuery(this).removeClass(options.collapseClass);
					jQuery(this).addClass(options.expandClass);
				}else{
					jQuery(this).html(collHtml);
					jQuery(this).removeClass(options.expandClass);
					jQuery(this).addClass(options.collapseClass);
				}
			}else{
				if(jQuery(document).find(options.target).is(':hidden')){
					jQuery(this).html(expHtml);
				}else{
					jQuery(this).html(collHtml);
				}
			}
		   
        });
    };
    
})(jQuery);