/*
 * jQuery functions
 * Written by AppThemes
 *
 * Built for use with the jQuery library
 * http://jquery.com
 *
 * Version 1.0
 *
 * Left .js uncompressed so it's easier to customize
 */

// <![CDATA[

!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a:a(jQuery)}(function(a){function b(b){var g=b||window.event,h=i.call(arguments,1),j=0,l=0,m=0,n=0,o=0,p=0;if(b=a.event.fix(g),b.type="mousewheel","detail"in g&&(m=-1*g.detail),"wheelDelta"in g&&(m=g.wheelDelta),"wheelDeltaY"in g&&(m=g.wheelDeltaY),"wheelDeltaX"in g&&(l=-1*g.wheelDeltaX),"axis"in g&&g.axis===g.HORIZONTAL_AXIS&&(l=-1*m,m=0),j=0===m?l:m,"deltaY"in g&&(m=-1*g.deltaY,j=m),"deltaX"in g&&(l=g.deltaX,0===m&&(j=-1*l)),0!==m||0!==l){if(1===g.deltaMode){var q=a.data(this,"mousewheel-line-height");j*=q,m*=q,l*=q}else if(2===g.deltaMode){var r=a.data(this,"mousewheel-page-height");j*=r,m*=r,l*=r}if(n=Math.max(Math.abs(m),Math.abs(l)),(!f||f>n)&&(f=n,d(g,n)&&(f/=40)),d(g,n)&&(j/=40,l/=40,m/=40),j=Math[j>=1?"floor":"ceil"](j/f),l=Math[l>=1?"floor":"ceil"](l/f),m=Math[m>=1?"floor":"ceil"](m/f),k.settings.normalizeOffset&&this.getBoundingClientRect){var s=this.getBoundingClientRect();o=b.clientX-s.left,p=b.clientY-s.top}return b.deltaX=l,b.deltaY=m,b.deltaFactor=f,b.offsetX=o,b.offsetY=p,b.deltaMode=0,h.unshift(b,j,l,m),e&&clearTimeout(e),e=setTimeout(c,200),(a.event.dispatch||a.event.handle).apply(this,h)}}function c(){f=null}function d(a,b){return k.settings.adjustOldDeltas&&"mousewheel"===a.type&&b%120===0}var e,f,g=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],h="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],i=Array.prototype.slice;if(a.event.fixHooks)for(var j=g.length;j;)a.event.fixHooks[g[--j]]=a.event.mouseHooks;var k=a.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var c=h.length;c;)this.addEventListener(h[--c],b,!1);else this.onmousewheel=b;a.data(this,"mousewheel-line-height",k.getLineHeight(this)),a.data(this,"mousewheel-page-height",k.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var c=h.length;c;)this.removeEventListener(h[--c],b,!1);else this.onmousewheel=null;a.removeData(this,"mousewheel-line-height"),a.removeData(this,"mousewheel-page-height")},getLineHeight:function(b){var c=a(b),d=c["offsetParent"in a.fn?"offsetParent":"parent"]();return d.length||(d=a("body")),parseInt(d.css("fontSize"),10)||parseInt(c.css("fontSize"),10)||16},getPageHeight:function(b){return a(b).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};a.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel")},unmousewheel:function(a){return this.unbind("mousewheel",a)}})});

jQuery(document).ready(function() {

	/* makes the tables responsive */
	if ( jQuery.isFunction( jQuery.fn.footable ) ) {
		jQuery('.footable').footable();
	}
	
	/* initialize the datepicker for forms */
	jQuery('#couponForm .datepicker').datepicker({
		dateFormat: 'yy-mm-dd',
		minDate: 0
	});

	/* initialize the form validation */
	if ( jQuery.isFunction(jQuery.fn.validate) ) {
		jQuery("#couponForm, #loginForm, #commentForm").validate({
			ignore: '.ignore',
			errorClass: "invalid",
			errorElement: "div",
			rules: {
				'post_content': {
					minlength: 15
				}
			}
		});
	}

	/* hide flash elements on ColorBox load */
	jQuery(document).bind("cbox_open", function() {
		//jQuery('object, embed, iframe').css({'visibility':'hidden'});
	});
	jQuery(document).bind("cbox_closed", function() {
		jQuery('object, embed, iframe').css({'visibility':'inherit'});
	});
	
	if ( jQuery.isFunction( jQuery.fn.jCarouselLite ) ) {
		jQuery(".slide-contain").jCarouselLite({
			btnNext: ".next",
			btnPrev: ".prev",
			visible: ( jQuery(window).width() < 940 ) ? 3 : 4,
			pause: true,
			auto: true,
			random: false,
			timeout: 4000,
			//scroll: 4,
			mouseWheel: true,
			speed: 500,
			easing: "swing" // for different types of easing, see easing.js
		});
		
		jQuery(".store-widget-slider").jCarouselLite({
			vertical: true,
			visible: 2,
			pause: true,
			auto: true,
			timeout: 2800,
			speed: 1000
		});
	}
	

	
	/* assign the ColorBox event to elements */
	if ( jQuery.isFunction(jQuery.colorbox) ) {
		jQuery( document ).on('click', 'a.mini-comments', function() {
			var postID = jQuery(this).data('rel');
			jQuery.colorbox({
				href: flatter_params.ajax_url + "?action=comment-form&id=" + postID,
				rel: function(){ return jQuery(this).data('rel'); },
				maxWidth: ( jQuery(window).width() < 940 ) ? '90%' : false,
				maxHeight: ( jQuery(window).width() < 940 ) ? '90%' : false,
				transition:'fade'
			});
			return false;
		});

		jQuery( document ).on('click', 'a.mail', function() {
			var postID = jQuery(this).data('id');
			jQuery.colorbox({
				href: flatter_params.ajax_url + "?action=email-form&id=" + postID,
				transition:'fade'
			});
			return false;
		});
		
		jQuery( document ).on('click', '.coupon_type-coupon-code a.coupon-code-link, .featured-slider a.coupon-code, .featured-slider a.coupon-hidden', function() {
			var couponcode = jQuery(this).data('clipboard-text');
			var linkID = jQuery(this).attr('id');
			jQuery(this).fadeOut('fast').html('<span>' + couponcode + '</span>').fadeIn('fast');
			if( jQuery(this).hasClass( 'coupon-hidden' ) ) {
				jQuery(this).removeClass( 'coupon-hidden' ).addClass( 'coupon-code' );
			}
			jQuery(this).parent().next().hide();
			jQuery.colorbox({
				href: flatter_params.ajax_url + "?action=coupon-code-popup&id=" + linkID,
				transition:'fade',
				maxWidth:'100%',
				onLoad: function() {
					if ( flatter_params.is_mobile ) {
						jQuery('#cboxOverlay, #wrapper').hide();
					}
				},
				onComplete: function() {
					ZeroClipboard.config( { moviePath: flatter_params.templateurl + '/includes/js/zeroclipboard/ZeroClipboard.swf' } );
					var clip = new ZeroClipboard( jQuery('#copy-button') );
					clip.on( 'complete', function( client, args ) {
						jQuery("#copy-button").html(flatter_params.text_copied);
					});
					clip.on( 'mouseover', function( client ) {
						jQuery("#copy-button").addClass('hover');
					});
					clip.on( 'mouseout', function( client ) {
						jQuery("#copy-button").removeClass('hover');
					});
					clip.on( 'noflash', function( client ) {
						jQuery("#copy-button").remove();
					});
					clip.on( 'wrongflash', function( client ) {
						jQuery("#copy-button").remove();
					});
				},
				onCleanup: function() {
					ZeroClipboard.destroy();
					if ( flatter_params.is_mobile ) {
						jQuery('#wrapper').show();
					}
				}
			});
			if( !flatter_params.force_affiliate ) {
				return false;
			} else if( is_ie() ) {
				window.open(jQuery(this).attr('href'), "_blank", '', false);
			}
		});
	}

	jQuery( document ).on( 'mouseover', ".coupon-code-link", function () {
		jQuery(this).parent().next().show();
	});
	jQuery( document ).on( 'mouseout', ".coupon-code-link", function () {
		jQuery(this).parent().next().hide();
	});
	
	/*if ( FlashDetect.installed ) {

		jQuery(".coupon-code-link").each(function() {
			jQuery(this).removeAttr('title');
		});

		ZeroClipboard.setDefaults( { moviePath: clipboard_params.templateurl + '/includes/js/zeroclipboard/ZeroClipboard.swf' } );
		var clip = new ZeroClipboard( jQuery('.coupon-code-link.coupon-hidden, .coupon-code-link.coupon-code') );

		clip.on( 'complete', function ( client, args ) {
			jQuery(this).fadeOut('fast').html('<span>' + args.text + '</span>').fadeIn('fast');
			jQuery(this).parent().next().hide();
			
			if( jQuery(this).hasClass( 'coupon-hidden' ) ) {
				jQuery(this).removeClass( 'coupon-hidden' ).addClass( 'coupon-code' );
			}
			
			if( flatter_params.popup_solution ) {
				window.open( window.location.protocol + "//" + window.location.host + "/" + window.location.pathname + '?_c=' + jQuery(this).data('coupon-id') + '&_nonce=' + jQuery(this).data('coupon-nonce'), '_blank' );
				location.href = jQuery(this).attr('href');
			} else {
				window.open( jQuery(this).attr('href'), '_blank' );
				return false;
			}
			
			/*var popup_clip = new ZeroClipboard( jQuery('.fl-coupon-popup .fl-coupon-copy') );
			popup_clip.on( 'complete', function ( client, args ) {
				alert('copied');
				jQuery(this).html('Copied!');
				return false;
			});* /
			
			
		});
		clip.on( 'mouseover', function ( client, args ) {
			jQuery(this).parent().next().show();
		});
		clip.on( 'mouseout', function ( client, args ) {
			jQuery(this).parent().next().hide();
		});
		

	} else {
	
		jQuery( document ).on('click', '.coupon-code-link.coupon-hidden, .coupon-code-link.coupon-code', function() {
			var couponcode = jQuery(this).data('clipboard-text');
			if( jQuery(this).hasClass( 'coupon-hidden' ) ) {
				jQuery(this).removeClass( 'coupon-hidden' ).addClass( 'coupon-code' );
			}
			jQuery(this).fadeOut('fast').html('<span>' + couponcode + '</span>').fadeIn('fast');

		});
	
	} */
	
	jQuery( document ).on('click', 'a.show-comments', function() {
		var postID = jQuery(this).data('rel');
		jQuery("#comments-" + postID ).slideToggle(400, 'easeOutBack');
		return false;		
	});

	jQuery( document ).on('click', 'a.share', function() {
		jQuery(this).next(".drop").slideToggle(400, 'easeOutBack');
		return false;		
	});
	

	// show the new store name and url fields if "add new" option is selected
	jQuery("#store_name_select").change(function() {
		if (jQuery(this).val() == 'add-new') {
			jQuery('li.new-store').fadeIn('fast');
			jQuery('li.new-store input').addClass('required');
			jQuery('li#new-store-url input').addClass('url');
		} else {
			jQuery('li.new-store').hide();
			jQuery('li.new-store input').removeClass('required invalid');
			jQuery('li#new-store-url input').removeClass('url');
		}
	}).change();

   // show the coupon code or upload coupon field based on type select box
	jQuery('#coupon_type_select').change(function() {	
		if (jQuery(this).val() == 'coupon-code') {
			jQuery('li#ctype-coupon-code').fadeIn('fast');
			jQuery('li#ctype-coupon-code input').addClass('required');
			jQuery('li#ctype-printable-coupon input').removeClass('required invalid');
			jQuery('li#ctype-printable-coupon').hide();
			jQuery('li#ctype-printable-coupon-preview').hide();
		} else if (jQuery(this).val() == 'printable-coupon') {
			jQuery('li#ctype-printable-coupon').fadeIn('fast');
			jQuery('li#ctype-printable-coupon-preview').fadeIn('fast');
			if ( ! jQuery('li#ctype-printable-coupon-preview') )
				jQuery('li#ctype-printable-coupon input').addClass('required');
			jQuery('li#ctype-coupon-code input').removeClass('required invalid');
			jQuery('li#ctype-coupon-code').hide();
		} else {
			jQuery('li.ctype').hide();
			jQuery('li.ctype input').removeClass('required invalid');
		}		
	}).change(); 
	
	// toggle reports form
	jQuery(".reports_form_link a").on( "click", function() {
		jQuery(this).parents('li').next().children('.reports_form').slideToggle( 400, 'easeOutBack' );
		return false;
	});
	
	/* convert header menu into select list on mobile devices */
	if ( jQuery.isFunction( jQuery.fn.tinyNav ) ) {
		jQuery('.header_menu_res .menu').tinyNav({
			active: 'active',
			header: flatter_params.text_mobile_primary,
			header_href: flatter_params.home_url,
			indent: '-',
			excluded: ['#adv_categories', '#adv_stores']
		});
		
		jQuery('#nav').tinyNav({
			active: 'active',
			header: flatter_params.text_mobile_top,
			header_href: flatter_params.home_url,
			indent: '-',
			excluded: ['#adv_categories', '#adv_stores']
		});
	}
	
	jQuery('.header_menu_res a.menu-toggle').click(function (){
		jQuery(this).next('ul.menu').toggle();
		return false;
	});
	
});

// used for the search box default text
function clearAndColor(el, e2) {
	//grab the current fields value and set a variable
	if (el.defaultValue==el.value) el.value = "";
	//Change the form fields text color
	if (el.style) el.style.color = "#333";

}


// used for the search box default text
function reText(el){
	//Change the form fields text color
	if (el.style) el.style.color = "#ccc";
	if (el.value== "") el.value = el.defaultValue;
}

jQuery(function() {
		
	jQuery( ".newtag" ).autocomplete({
		source: function( request, response ) {
			jQuery.ajax({
				url: flatter_params.ajax_url + "?action=ajax-tag-search-front&tax=" + flatter_params.app_tax_store,
				dataType: "json",
				data: {
					term: request.term
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					//alert('ERROR!: '+ errorThrown);
					//alert('ERROR!: '+ textStatus);
					//alert('ERROR!: '+ XMLHttpRequest);
				},
				success: function( data ) {
					response( jQuery.map( data, function( item ) {
						return {
							term: item,
							value: item.name
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function(event, ui) {
			// alert (ui.item.term.slug);
			storeurl = ui.item.term.clpr_store_url;
			if (storeurl != 0) {
				jQuery(".clpr_store_url").html('<a href="' + storeurl + '" target="_blank">' + storeurl + '<br /><img src="' + ui.item.term.clpr_store_image_url + '" class="screen-thumb" /></a><input type="hidden" name="clpr_store_id" value="' + ui.item.term.term_id + '" /><input type="hidden" name="clpr_store_slug" value="' + ui.item.term.slug + '" />');
			}	
		}
	});
		
	jQuery( ".newtag" ).keydown(function(event) {
		if (jQuery("#clpr_store_url").length == 0) {
			jQuery(".clpr_store_url").html('<input type="text" class="text" id="clpr_store_url" name="clpr_store_url" value="http://" />');
		}			
	});
		
		
	jQuery( document ).on('click', 'button.comment-submit', function() {
		
		var comment_post_ID = jQuery(this).next().val();
		var postURL = flatter_params.ajax_url + "?action=post-comment";
		var author = jQuery('input#author-' + comment_post_ID).val();
		var email = jQuery('#email-' + comment_post_ID).val();
		var url = jQuery('#url-' + comment_post_ID).val();
		var comment = jQuery('#comment-' + comment_post_ID).val();				
		
		var postData = 'author=' + author 
			+ '&email=' + email 
			+ '&url=' + url 
			+ '&comment=' + comment 
			+ '&comment_post_ID=' + comment_post_ID ;
		
		// alert(postData);
		
		jQuery.ajax({
			beforeSend: function() {
				return jQuery("#commentform-" + comment_post_ID).validate({
					errorClass: "invalid",
					errorElement: "div"
				}).form();
			},
			type: 'POST',
			data: postData,
			url: postURL,
			dataType: "json",
			error: function(XMLHttpRequest, textStatus, errorThrown){
				alert('Error: '+ errorThrown + ' - '+ textStatus + ' - '+ XMLHttpRequest);
			},
			success: function( data ) {
				
				if (data.success == true) {
					//jQuery('.comment-form .post-box').html('<div class="head"><h3>Thanks!</h3></div><div class="text-box"><p>Your comment will appear shortly.</p></div>');
					jQuery.colorbox.close();
					
					if (jQuery("#comments-" + comment_post_ID + " .comments-mini").length == 0 ) {
						jQuery("#comments-" + comment_post_ID).append("<div class='comments-box coupon'><ul class='comments-mini'>" + data.comment + "</ul></div>").fadeOut('slow').fadeIn('slow');
					} else {
						jQuery("#comments-" + comment_post_ID + " .comments-mini").prepend(data.comment).fadeOut('slow').fadeIn('slow');
					}
					
					// update the comment count but delay it a bit
					setTimeout(function() {
						jQuery("#post-" + comment_post_ID + " a.show-comments span").html(data.count).fadeOut('slow').fadeIn('slow');
					}, 2000);
					
				} else {
					jQuery('.comment-form .post-box').html('<div class="head"><h3>Error</h3></div><div class="text-box"><p>' + data.message + '</p></div>');
					jQuery.colorbox.resize();
				}				
			}
		});		
	 
	  return false;			
	});
	
	
	jQuery('body').on('click','#submitted',function(event){
		if(!jQuery("#couponForm").valid()) return;
		jQuery('#spinner').show();
		var postURL = flatter_params.ajax_url,
			comment_captcha_prefix = jQuery('#comment_captcha_prefix').val(),
			comment_captcha_code = jQuery('#comment_captcha_code').val();
		jQuery.ajax({
			type: "post",
			url: postURL,
			data: "action=check-captcha&comment_captcha_prefix="+comment_captcha_prefix+"&comment_captcha_code="+comment_captcha_code,
			success: function(status){
				if(status == "correct") {
					jQuery("#couponForm").submit();
				} else {
					jQuery('#spinner').hide();
					jQuery('<div class="invalid">The captcha code is incorrect. Please try again.</div>').insertAfter('p.comment-form-captcha');
				}
			}, error: function() {
				console.log('Something wrong happen. Please try again later.');
			}
		});
		
		return false;
	});

	
	
	// send the coupon via email pop-up form
	jQuery( document ).on('click', 'button.send-email', function() {
	 
		var post_ID = jQuery(this).next().val();
		var postURL = flatter_params.ajax_url + "?action=send-email";
		var author = jQuery('#author-' + post_ID).val();
		var	email = jQuery('#email-' + post_ID).val();
		var	recipients = jQuery('#recipients-' + post_ID).val();
		var	message = jQuery('#message-' + post_ID).val();
		
		var postData = 'author=' + author 
			+ '&email=' + email 
			+ '&recipients=' + recipients 
			+ '&message=' + message 
			+ '&post_ID=' + post_ID ;
		
		// alert (postData);
		
		jQuery.ajax({
			beforeSend: function() {
				return jQuery("#commentform-" + post_ID).validate({
					errorClass: "invalid",
					errorElement: "div"
				}).form();
			},
			type: 'POST',
			data: postData,
			url: postURL,
			dataType: "json",
			success: function( data ) {

				jQuery('.comment-form .post-box').html('<div class="head"><h3>' + flatter_params.text_sent_email + '</h3></div><div class="text-box"></div>');

				jQuery.each(data, function(i, val){						
					if (val.success == true){
						jQuery('.comment-form .post-box .text-box').append('<p>' + flatter_params.text_shared_email_success + ': ' + val.recipient + '</p>');
						jQuery.colorbox.resize();
					} else {
						jQuery('.comment-form .post-box .text-box').append('<p>' + flatter_params.text_shared_email_failed + ': ' + val.recipient + '.</p>');
						jQuery.colorbox.resize();
					}
				});				
			}
		});
		
	 
	  return false;
	});
		
});

// coupon ajax vote function. calls clpr_vote_update() in voting.php 
function thumbsVote(postID, userID, elementID, voteVal, afterVote) {
	var postData = 'vid=' + voteVal + '&uid=' + userID + '&pid=' + postID;
	var theTarget = document.getElementById(elementID);	// pass in the vote_# css id so we know where to update

	jQuery.ajax({
			target: theTarget,
			type: 'POST',
			beforeSend: function() {
				jQuery('#loading-' + postID).fadeIn('fast'); // show the loading image
				jQuery('#ajax-' + postID).fadeOut('fast'); // fade out the vote buttons
			},
			data: postData,
			url: flatter_params.ajax_url + "?action=ajax-thumbsup",
			error: function(XMLHttpRequest, textStatus, errorThrown){
				alert('Error: '+ errorThrown + ' - '+ textStatus + ' - '+ XMLHttpRequest);
			},
			success: function( data, statusText ) {
				theTarget.innerHTML = afterVote;
				jQuery('#post-' +postID + ' span.percent').html(data).fadeOut('slow').fadeIn('slow');
			}			
		});	
	
	return false;		
}

// coupon ajax reset votes function. calls clpr_reset_coupon_votes_ajax() in voting.php
function resetVotes(postID, elementID, afterReset) {
	var postData = 'pid=' + postID;
	var theTarget = document.getElementById(elementID);	// pass in the reset_# css id so we know where to update

	jQuery.ajax({
			target: theTarget,
			type: 'POST',
			data: postData,
			url: flatter_params.ajax_url + "?action=ajax-resetvotes",
			error: function(XMLHttpRequest, textStatus, errorThrown){
				alert('Error: '+ errorThrown + ' - '+ textStatus + ' - '+ XMLHttpRequest);
			},
			success: function( data, statusText ) {
				theTarget.innerHTML = afterReset;
			}
		});

	return false;	
}

function is_ie() {
	return ((navigator.appName == 'Microsoft Internet Explorer') || ((navigator.appName == 'Netscape') && (new RegExp("Trident/.*rv:([0-9]{1,}[\.0-9]{0,})").exec(navigator.userAgent) != null)));
}

(function(e,t,n,r){var i=e(t);e.fn.lazyload=function(s){function f(){var t=0;o.each(function(){var n=e(this);if(a.skip_invisible&&!n.is(":visible")){return}if(e.abovethetop(this,a)||e.leftofbegin(this,a)){}else if(!e.belowthefold(this,a)&&!e.rightoffold(this,a)){n.trigger("appear");t=0}else{if(++t>a.failure_limit){return false}}})}var o=this;var u;var a={threshold:0,failure_limit:0,event:"scroll",effect:"show",container:t,data_attribute:"original",skip_invisible:true,appear:null,load:null,placeholder:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC"};if(s){if(r!==s.failurelimit){s.failure_limit=s.failurelimit;delete s.failurelimit}if(r!==s.effectspeed){s.effect_speed=s.effectspeed;delete s.effectspeed}e.extend(a,s)}u=a.container===r||a.container===t?i:e(a.container);if(0===a.event.indexOf("scroll")){u.bind(a.event,function(){return f()})}this.each(function(){var t=this;var n=e(t);t.loaded=false;if(n.attr("src")===r||n.attr("src")===false){if(n.is("img")){n.attr("src",a.placeholder)}}n.one("appear",function(){if(!this.loaded){if(a.appear){var r=o.length;a.appear.call(t,r,a)}e("<img />").bind("load",function(){var r=n.attr("data-"+a.data_attribute);n.hide();if(n.is("img")){n.attr("src",r)}else{n.css("background-image","url('"+r+"')")}n[a.effect](a.effect_speed);t.loaded=true;var i=e.grep(o,function(e){return!e.loaded});o=e(i);if(a.load){var s=o.length;a.load.call(t,s,a)}}).attr("src",n.attr("data-"+a.data_attribute))}});if(0!==a.event.indexOf("scroll")){n.bind(a.event,function(){if(!t.loaded){n.trigger("appear")}})}});i.bind("resize",function(){f()});if(/(?:iphone|ipod|ipad).*os 5/gi.test(navigator.appVersion)){i.bind("pageshow",function(t){if(t.originalEvent&&t.originalEvent.persisted){o.each(function(){e(this).trigger("appear")})}})}e(n).ready(function(){f()});return this};e.belowthefold=function(n,s){var o;if(s.container===r||s.container===t){o=(t.innerHeight?t.innerHeight:i.height())+i.scrollTop()}else{o=e(s.container).offset().top+e(s.container).height()}return o<=e(n).offset().top-s.threshold};e.rightoffold=function(n,s){var o;if(s.container===r||s.container===t){o=i.width()+i.scrollLeft()}else{o=e(s.container).offset().left+e(s.container).width()}return o<=e(n).offset().left-s.threshold};e.abovethetop=function(n,s){var o;if(s.container===r||s.container===t){o=i.scrollTop()}else{o=e(s.container).offset().top}return o>=e(n).offset().top+s.threshold+e(n).height()};e.leftofbegin=function(n,s){var o;if(s.container===r||s.container===t){o=i.scrollLeft()}else{o=e(s.container).offset().left}return o>=e(n).offset().left+s.threshold+e(n).width()};e.inviewport=function(t,n){return!e.rightoffold(t,n)&&!e.leftofbegin(t,n)&&!e.belowthefold(t,n)&&!e.abovethetop(t,n)};e.extend(e.expr[":"],{"below-the-fold":function(t){return e.belowthefold(t,{threshold:0})},"above-the-top":function(t){return!e.belowthefold(t,{threshold:0})},"right-of-screen":function(t){return e.rightoffold(t,{threshold:0})},"left-of-screen":function(t){return!e.rightoffold(t,{threshold:0})},"in-viewport":function(t){return e.inviewport(t,{threshold:0})},"above-the-fold":function(t){return!e.belowthefold(t,{threshold:0})},"right-of-fold":function(t){return e.rightoffold(t,{threshold:0})},"left-of-fold":function(t){return!e.rightoffold(t,{threshold:0})}})})(jQuery,window,document)

jQuery(document).ready(function($) {
	$('.link-popup .link-popup-inner').text('Click to copy code & open site');
	$("li.lazy,img.lazy").lazyload({
    	effect : "fadeIn",
		threshold : -50
  	});
	
	$('script').last().attr('id','test');
});