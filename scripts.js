jQuery(window).on("load",function(){
// console.log("loading");
			if(jQuery("input[name='range']:checked").val()==1){
				jQuery(".set-range-fieldset").show();
			}
			else{
				jQuery(".set-range-fieldset").hide();
			}

})
// console.log("bookingside");
jQuery(document).ready(function($) {

	"use strict";

	$("input[name='range']").on("change",function(e){

		if($("input[name='range']:checked").val()==1){
			$(".set-range-fieldset").show();
		}
		else{
			$(".set-range-fieldset").hide();
		}
	});

$("input[name='range']").on("click",function(e){
	if($("input[name='range']:checked").val()==1){
		$(".set-range-fieldset").show();
	}
	else{
		$(".set-range-fieldset").hide();
	}
});

	$(document).on("click",'.external_bookingbutton', function(e) {

		var sh_validation_error = false;
		var sh_booking_length_error = false;

		if ( !$('#check_in').val() ) {
			sh_validation_error = true;
		}

		if ( !$('#check_out').val() ) {
			sh_validation_error = true;
		}

		if ( $('#check_in').val() == sohohotel_check_in_txt ) {
			sh_validation_error = true;
		}

		if ( $('#check_out').val() == sohohotel_check_out_txt ) {
			sh_validation_error = true;
		}

		if ( sohohotel_date_validation($('#check_in_alt').val(), $('#check_out_alt').val()) == true ) {
			sh_validation_error = true;
		}

		var sh_booking_length =  Math.floor(( Date.parse($('#check_out_alt').val()) - Date.parse($('#check_in_alt').val()) ) / 86400000);



		if ( sh_booking_length < sohohotel_bookingMinBookPeriod ) {
			sh_booking_length_error = true;
		}



		if ( sh_validation_error == true ) {
			alert(sohohotel_date_msg);
			return false;
		}

		if ( sh_booking_length_error == true ) {
			alert(sohohotel_booking_length_error_msg);
			return false;
		}

		if($("input[name='range']:checked").val()==1){
			if(sh_booking_length > 35){
				alert("Please select a date range maximum of 35 days");
				return false;
			}
		}

		if(jQuery("input[name='range']:checked").val()==1){
			var c = +(jQuery("#book_room_weeks").val()*7) + +(jQuery("#book_room_days").val())
			if(c<1){
				alert("Please select number of weeks/days of stay as your desire");
				return false;
			}
			if(c > 35){
				alert("Please select a day range maximum of 35 days");
				return false;
			}
			if(c > sh_booking_length){
				alert("The day range you have selected is more than your date range.");
				return false;
			}

		}


	});

	// Format the date
	function sohohotel_format_date(dateInput) {

		var date_array = new Array();
		date_array = dateInput.split('-');

		if ( datepickerDateFormat == 'dd/mm/yy' ) {
			var newDate = (date_array[2] + "/" + date_array[1] + "/" + date_array[0]);
		}

		if ( datepickerDateFormat == 'mm/dd/yy' ) {
			var newDate = (date_array[1] + "/" + date_array[2] + "/" + date_array[0]);
		}

		if ( datepickerDateFormat == 'yy/mm/dd' ) {
			var newDate = (date_array[0] + "/" + date_array[1] + "/" + date_array[2]);
		}

		if ( datepickerDateFormat == 'dd.mm.yy' ) {
			var newDate = (date_array[2] + "." + date_array[1] + "." + date_array[0]);
		}

		if ( datepickerDateFormat == 'mm.dd.yy' ) {
			var newDate = (date_array[1] + "." + date_array[2] + "." + date_array[0]);
		}

		if ( datepickerDateFormat == 'yy.mm.dd' ) {
			var newDate = (date_array[0] + "." + date_array[1] + "." + date_array[2]);
		}

		return newDate;

	}

	// Payment Method Accordion
	function sohohotel_load_accordion() {

		$('div.payment_method').accordion({event: 'mouseup', heightStyle: 'content'});
		$('div.payment_method h3').on('click', function() {
			$('input', this).prop('checked', true);
		});
		$('div.payment_method h3 input').on('click', function() {
			$(this).prop('checked', true);
		});
		$('div.payment_method h3 input').first().prop('checked', true);

	}

	// Check the check in date is before the check out date
	function sohohotel_date_validation(date1, date2) {
	    return new Date(date1) > new Date(date2);
	}

	// Valid booking form fields
	function sohohotel_field_validation() {

		var sh_validation_error = false;

		if ( !$('#open_date_from').val() ) {
			sh_validation_error = true;
		}

		if ( !$('#open_date_to').val() ) {
			sh_validation_error = true;
		}

		if ( sohohotel_date_validation($('#check_in_alt').val(), $('#check_out_alt').val()) == true ) {
			sh_validation_error = true;
		}

		if ( sh_validation_error == true ) {
			return true;
		} else {
			return false;
		}

	}

	function sohohotel_required_field() {

		var sh_validation_error = false;

		$('.sh-required-field').each(function() {
			if ($.trim($(this).val()) == '') {
				sh_validation_error = true;
			}
		});

		if ( sh_validation_error == true ) {
			return true;
		} else {
			return false;
		}

	}

	function sohohotel_email_validation(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}

	function sohohotel_email_field_validation() {

		var sh_validation_error = false;

		$('.email_validation').each(function() {

			if ( sohohotel_email_validation( $(this).val() ) == false ) {
				sh_validation_error = true;
			}

		});

		if ( sh_validation_error == true ) {
			return true;
		} else {
			return false;
		}

	}

	function sohohotel_number_field_validation() {

		var sh_validation_error = false;

		$('.number_validation').each(function() {

			if( !$.isNumeric($(this).val()) ) {
				sh_validation_error = true;
			}

		});

		if ( sh_validation_error == true ) {
			return true;
		} else {
			return false;
		}

	}

	// Add/Remove Rooms For Booking Form Function
	function sohohotel_add_remove_rooms() {

		var i = '';

		var selectedVal = jQuery('#book_room').val();
		jQuery('.rooms-wrapper div[class^="room-"]:not(div.room-1)').hide();

		for(i = 1; i <= selectedVal; i++ ) {
			jQuery('div[class^=room-'+ i +']').show();
		}

		jQuery('#book_room').change(function(e) {
			jQuery('.rooms-wrapper div[class^="room-"]').hide();
			e.preventDefault();
			var selectedVal = jQuery(this).val();

			if(selectedVal > 1) {
				for(i = 1; i <= selectedVal; i++ ) {
					jQuery('div[class^=room-'+ i +']').show();
				}
			}
			else {
				jQuery('div.room-1').show();
			}
		});

	}

	// Load prettyPhoto
	function sohohotel_load_prettyphoto() {

		// PrettyPhoto
		$("a[data-gal^='prettyPhoto']").prettyPhoto({
			hook: 'data-gal',
			animation_speed: 'fast',
			slideshow: 5000,
			autoplay_slideshow: false,
			opacity: 0.80,
			show_title: true,
			allow_resize: true,
			default_width: 500,
			default_height: 344,
			counter_separator_label: '/',
			theme: 'pp_default',
			horizontal_padding: 20,
			hideflash: false,
			wmode: 'opaque',
			autoplay: true,
			modal: false,
			deeplinking: true,
			overlay_gallery: true,
			keyboard_shortcuts: true,
			changepicturecallback: function(){},
			callback: function(){},
			ie6_fallback: true,
			markup: '<div class="pp_pic_holder"> \
						<div class="ppt">&nbsp;</div> \
						<div class="pp_top"> \
							<div class="pp_left"></div> \
							<div class="pp_middle"></div> \
							<div class="pp_right"></div> \
						</div> \
						<div class="pp_content_container"> \
							<div class="pp_left"> \
								<div class="pp_right"> \
									<div class="pp_content"> \
										<div class="pp_loaderIcon"></div> \
										<div class="pp_fade"> \
											<a href="#" class="pp_expand" title="Expand the image">Expand</a> \
											<div class="pp_hoverContainer"> \
												<a class="pp_next" href="#">next</a> \
												<a class="pp_previous" href="#">previous</a> \
											</div> \
											<div id="pp_full_res"></div> \
											<div class="pp_details"> \
												<div class="pp_nav"> \
													<a href="#" class="pp_arrow_previous">Previous</a> \
													<p class="currentTextHolder">0/0</p> \
													<a href="#" class="pp_arrow_next">Next</a> \
												</div> \
												<p class="pp_description"></p> \
												{pp_social} \
												<a class="pp_close" href="#"><i class="fa fa-close"></i></a> \
											</div> \
										</div> \
									</div> \
								</div> \
								</div> \
							</div> \
							<div class="pp_bottom"> \
								<div class="pp_left"></div> \
								<div class="pp_middle"></div> \
								<div class="pp_right"></div> \
							</div> \
						</div> \
						<div class="pp_overlay"></div>',
				gallery_markup: '<div class="pp_gallery"> \
									<a href="#" class="pp_arrow_previous">Previous</a> \
									<div> \
										<ul> \
											{gallery} \
										</ul> \
									</div> \
									<a href="#" class="pp_arrow_next">Next</a> \
								</div>',
				image_markup: '<img id="fullResImage" src="{path}" />',
				flash_markup: '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="{width}" height="{height}"><param name="wmode" value="{wmode}" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="movie" value="{path}" /><embed src="{path}" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="{width}" height="{height}" wmode="{wmode}"></embed></object>',
				quicktime_markup: '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" codebase="http://www.apple.com/qtactivex/qtplugin.cab" height="{height}" width="{width}"><param name="src" value="{path}"><param name="autoplay" value="{autoplay}"><param name="type" value="video/quicktime"><embed src="{path}" height="{height}" width="{width}" autoplay="{autoplay}" type="video/quicktime" pluginspage="http://www.apple.com/quicktime/download/"></embed></object>',
				iframe_markup: '<iframe src ="{path}" width="{width}" height="{height}" frameborder="no"></iframe>',
				inline_markup: '<div class="pp_inline">{content}</div>',
				custom_markup: '',
				social_tools: '<div class="pp_social"><div class="twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-count="none">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><iframe src="http://www.facebook.com/plugins/like.php?locale=en_US&href='+location.href+'&amp;layout=button_count&amp;show_faces=true&amp;width=500&amp;action=like&amp;font&amp;colorscheme=light&amp;height=23" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:500px; height:23px;" allowTransparency="true"></iframe></div></div>'
		});

	}

	sohohotel_add_remove_rooms();

	$(".bookingbutton").submit(function() { return false; });
	$(".select-room-button").submit(function() { return false; });

	$(document).on("click",'.bookingbutton, .select-room-button, .edit-room-button, .select-services, .edit-booking-button, .apply-coupon-button, .booking_payment, .edit-step-2-button', function(e) {

		// Booking step 1 button
		if( $(this).attr("class") == 'bookingbutton' ) {

			var sh_booking_length_error = false;
			var sh_booking_length =  Math.floor(( Date.parse($('#check_out_alt').val()) - Date.parse($('#check_in_alt').val()) ) / 86400000);

			if ( sh_booking_length < sohohotel_bookingMinBookPeriod ) {
				sh_booking_length_error = true;
			}

			if ( sohohotel_field_validation() == true ) {
				alert(sohohotel_date_msg);
				return false;
			}

			if ( sh_booking_length_error == true ) {
				alert(sohohotel_booking_length_error_msg);
				return false;
			}


			// alert("please wait while we find available condos");

			if($("input[name='range']:checked").val()==1){
				if(sh_booking_length > 35){
					alert("Please select a date range maximum of 35 days");
					return false;
				}
			}

			if(jQuery("input[name='range']:checked").val()==1){
				var c = +(jQuery("#book_room_weeks").val()*7) + +(jQuery("#book_room_days").val())
				if(c<1){
					alert("Please select number of weeks/days of stay as your desire");
					return false;
				}
				if(c > 35){
					alert("Please select a day range maximum of 35 days");
					return false;
				}
				if(c > sh_booking_length){
					alert("The day range you have selected is more than your date range.");
					return false;
				}

			}

			$(".sh-fixed-dialog.processing").show()

		}

		// Coupon button
		if( $(this).attr("class") == 'apply-coupon-button' ) {

			var fired_button = $(this).val();
			$(".apply_coupon_hidden").val("true");

		}

		// Booking payment button
		if( $(this).attr("class") == 'booking_payment' ) {

			$(".booking-payment-data").val("true");

			if( sohohotel_terms_set == 'true' ) {

				if( $('.terms_and_conditions').is(':checked') == false ) {
		            alert(sohohotel_terms_msg);
					return false;
		        }

			}

			if ( sohohotel_required_field() == true ) {
				alert(sohohotel_required_msg);
				return false;
			}

			if ( sohohotel_email_field_validation() == true ) {
				alert(sohohotel_invalid_email_msg);
				return false;
			}

			if ( sohohotel_number_field_validation() == true ) {
				alert(sohohotel_invalid_phone_msg);
				return false;
			}

		}

		// Booking Step 2, get the selected room button value and add it to an input field for submission
		if( $(this).attr("class") == 'select-room-button' ) {

			var fired_button = $(this).val();
			$(".selected-room").val(fired_button);

		}

		// Booking Step 2, get the selected room button value and add it to an input field for submission
		if( $(this).attr("class") == 'edit-room-button' ) {

			var fired_button = $(this).val();
			$(".edit-room-field").val(fired_button);

		}

		// Booking Step 2, get the selected room button value and add it to an input field for submission
		if( $(this).attr("class") == 'edit-booking-button' ) {

			var fired_button = $(this).val();
			$(".edit-booking-field").val(fired_button);

		}

		// Booking Step 2, get the selected room button value and add it to an input field for submission
		if( $(this).attr("class") == 'edit-step-2-button' ) {
			
			var fired_button = $(this).val();
			$(".edit-step-2").val(fired_button);

		}

		// AJAX
		$.ajax({
			type: 'POST',
			url: sohohotel_booking_AJAX_URL,
			data: $('.booking-form-data').serialize(),
			dataType: 'json',
			success: function(response) {

				if (response.status == 'success') {
					$('.booking-form-data')[0].reset();
				}

				$('.booking-step-wrapper').html(response.booking_step_wrapper);
				$('.booking-main').html(response.booking_main);
				$('.booking-side').html(response.booking_side);

				sohohotel_add_remove_rooms();
				sohohotel_load_prettyphoto();
				sohohotel_load_accordion();
				sh_load_open_datepicker();

				// Scroll to top for each booking step
				$('html,body').animate({
				   scrollTop: $(".booking-step-wrapper").offset().top
				});

				$('.booking-main').css('opacity','1');
				setTimeout(function(){
					if($("input[name='range']:checked").val()==1){
						$(".set-range-fieldset").show();
					}
					else{
						$(".set-range-fieldset").hide();
					}
				},1000);

				$('.booking-side').css('opacity','1');
				$(".sh-fixed-dialog.processing").hide();
				$(".remaining-rooms").fadeIn(800).fadeOut(800).fadeIn(800).fadeOut(800).fadeIn(800).fadeOut(800).fadeIn(800);

			}
		});

		$('.booking-main').css('opacity','0.3');
		$('.booking-side').css('opacity','0.3');

		return false;

	});

	$(document).on("click",'.calendar_button_prev, .calendar_button_next, .calendar_button_current, .calendar_button_custom', function(e) {

		if( $(this).attr("class") == 'calendar_button_prev' ) {
			$('.sh_calendar_navigation').val('prev');
		}

		if( $(this).attr("class") == 'calendar_button_next' ) {
			$('.sh_calendar_navigation').val('next');
		}

		if( $(this).attr("class") == 'calendar_button_current' ) {
			$('.sh_calendar_navigation').val('current');
		}

		if( $(this).attr("class") == 'calendar_button_custom' ) {
			$('.sh_calendar_navigation').val('custom');
		}

		var message = $('#message').val();
		var name = $('#name').val();
		var email = $('#email').val();

		$.ajax({
			type: 'POST',
			url: sohohotel_booking_AJAX_URL,
			data: $('.availability_checker_form').serialize(),
			dataType: 'json',
			success: function(response) {

				if (response.status == 'success') {
					$('.availability_checker_form')[0].reset();
				}

				$('.sh-availability-calendar-wrapper').html(response.content);
				$('.sh-availability-calendar-wrapper').css('opacity','1');

			}
		});

		$('.sh-availability-calendar-wrapper').css('opacity','0.3');

		return false;

	});

	/* Booking form 3 room & guest selection */
	$(".room-selection, .guest-selection").on("click",function(e) {
		if ($(".room-guest-selection-input-wrapper").hasClass("room-guest-selection-input-open")) {
			$(".room-guest-selection-input-wrapper").fadeOut(100);
			$(".room-guest-selection-input-wrapper").removeClass("room-guest-selection-input-open");
		} else {
			$(".room-guest-selection-input-wrapper").fadeIn(100);
			$(".room-guest-selection-input-wrapper").addClass("room-guest-selection-input-open");

			$('.datepicker--open').css('top','-9999px');


			// Scroll to top for each booking step
			$('html,body').animate({
			   scrollTop: $(".booking-form-input-3-alt").offset().top - 190
			});

			sohohotel_guest_select_change();
			sohohotel_recacluclate();
		}
		e.preventDefault();
		e.stopPropagation();
		daterangepicker.close();
	});

	$(".room-selection-done-btn").on("click",function(e) {
		$(".room-guest-selection-input-wrapper").fadeOut(100);
		$(".room-guest-selection-input-wrapper").removeClass("room-guest-selection-input-open");
		e.preventDefault();
		e.stopPropagation();
	});

	function sohohotel_guest_select_change() {
		$(".book_room_adults, .book_room_children").on('change', function() {
			sohohotel_recacluclate();
		});
	}

	sohohotel_add_room();
	sohohotel_remove_room();
	sohohotel_guest_select_change();

	function sohohotel_add_room() {
		$(".add-another-room-btn").on( "click", function(e) {
	        $(this).parent().find(".room-input-wrapper-outer").append($(".room-input-wrapper-hidden").html());
			sohohotel_guest_select_change();
			sohohotel_remove_room();
			sohohotel_recacluclate();
			e.preventDefault();
	    });
	}

	function sohohotel_remove_room() {
	    $(".room-input-title").unbind("click").bind("click", function(e) {
	        $(this).parent().remove();
			sohohotel_recacluclate();
			e.preventDefault();
	    });
	}

	function sohohotel_recacluclate() {
		$(".room-input-wrapper-outer").each(function() {

			var total_guests = [];

			$(this).children('div').each(function(index) {
				$(this).find('.room-input-title .room-count').text(index + 1);
				$(this).find('.book_room_adults').attr('name', 'book_room_adults_' + (index + 1));
				$(this).find('.book_room_children').attr('name', 'book_room_children_' + (index + 1));

				$(".room-value").text(index + 1);
				$("#book_room").val( index + 1 );

				total_guests.push( parseInt( $(this).find('.book_room_adults').val() ) );
				total_guests.push( parseInt( $(this).find('.book_room_children').val() ) );
				$(".guest-value").text( total_guests.reduce(sohohotel_getsum) );

				if ( (index + 1) >= sohohotel_max_rooms ) {
					$('.add-another-room-btn').css('display','none');
					$('.booking-room-limit').css('display','block');

				} else {
					$('.add-another-room-btn').css('display','block');
					$('.booking-room-limit').css('display','none');
				}

			});


		});
	}

	function sohohotel_getsum(total, num) {
	    return total + num;
	}

	function sh_off_screen(input) {

		var elm = $(input);
		var off = elm .offset();
		var l = off.left;
		var w = elm.width();
		var docW = $(".outer-wrapper").width();

		var isEntirelyVisible = (l+ w <= docW);

		if ( ! isEntirelyVisible ) {
			// off screen
			return true;
		} else {
			// not off screen
			return false;
		}

	}

	function sh_datepicker_position(offscreen,class_name) {

		// Offscreen
		if ( offscreen == true ) {

			var position = $(class_name).position();
			var height = $(class_name).outerHeight();
			var width = $(class_name).outerWidth();
			var width_datepicker = $(".datepicker").outerWidth();
			$("#datepicker-check_in_hidden").css({"top":(position.top + height),"left":(position.left - (width_datepicker - width - 2))});

			$(window).resize(function() {
				var position = $(class_name).position();
				var height = $(class_name).outerHeight();
				var width = $(class_name).outerWidth();
				var width_datepicker = $(".datepicker").outerWidth();
				$("#datepicker-check_in_hidden").css({"top":(position.top + height),"left":(position.left - (width_datepicker - width - 2))});
			});

		// Not offscreen
		} else {

			var position = $(class_name).position();
			var height = $(class_name).outerHeight();
			$("#datepicker-check_in_hidden").css({"top":(position.top + height),"left":position.left});

			$(window).resize(function() {
				var position = $(class_name).position();
				var height = $(class_name).outerHeight();
				$("#datepicker-check_in_hidden").css({"top":(position.top + height),"left":position.left});
			});

		}

	}

	function sh_load_datepicker() {

		// Make Datepicker Fields Read Only
		jQuery("#check_in").attr('readonly', true);
		jQuery("#check_out").attr('readonly', true);

		// Regular Datepicker
		if( $("#check_in_hidden").length ) {

			var daterangepicker = new HotelDatepicker(document.getElementById('check_in_hidden'), {
				disabledDates: sohohotel_blocked_dates_all,
				enableCheckout: true,
				i18n: {
					selected: sohohotel_dp_selected,
					night: sohohotel_dp_night,
					nights: sohohotel_dp_nights,
					button: sohohotel_dp_button,
					'checkin-disabled': sohohotel_dp_checkin_disabled,
					'checkout-disabled': sohohotel_dp_checkout_disabled,
					'day-names-short': sohohotel_datepicker_days,
					'day-names': sohohotel_datepicker_days,
					'month-names-short': sohohotel_datepicker_months,
					'month-names': sohohotel_datepicker_months,
					'error-more': sohohotel_dp_error_more,
					'error-more-plural': sohohotel_dp_error_more_plural,
					'error-less': sohohotel_dp_error_less,
					'error-less-plural': sohohotel_dp_error_less_plural,
					'info-more': sohohotel_dp_info_more,
					'info-more-plural': sohohotel_dp_info_more_plural,
					'info-range': sohohotel_dp_info_range,
					'info-default': sohohotel_dp_info_default
				}
			});

			var input = document.getElementById('check_in_hidden');

			input.addEventListener('afterClose', function () {

				if ($('div').find("#check_in_hidden").val() != '') {
					var data = $('#check_in_hidden').val();
					var arr = data.split(' - ');
					$('#check_in').val( sohohotel_format_date( arr[0] ) );
					$('#check_out').val( sohohotel_format_date( arr[1] ) );
					$('#check_in_alt').val( arr[0] );
					$('#check_out_alt').val( arr[1] );
				}

			}, false);

			$("#check_in").click(function(e){

				$("#check_out").removeClass("check_out_focus");
				$("#check_in").addClass("check_in_focus");
				$('html,body').animate({
				   scrollTop: $(".booking-form-input-1").offset().top - 190
				});

				$("#datepicker-check_in_hidden").css({"top":"","left":""});
				$(".room-guest-selection-input-wrapper").fadeOut(100);
				$(".room-guest-selection-input-wrapper").removeClass("room-guest-selection-input-open");
				e.preventDefault();
				e.stopPropagation();
				daterangepicker.open();

				// Off screen
				if ( sh_off_screen(".datepicker") == true ) {
					$(window).scroll(function(){
						if ($("#check_in").hasClass("check_in_focus")) {
							sh_datepicker_position(true,".booking-form-input-1");
						}
					});
					sh_datepicker_position(true,".booking-form-input-1");

				// On screen
				} else {
					$(window).scroll(function(){
						if ($("#check_in").hasClass("check_in_focus")) {
							sh_datepicker_position(false,".booking-form-input-1");
						}
					});
					sh_datepicker_position(false,".booking-form-input-1");

				}

			});

			$("#check_out").click(function(e){

				$("#check_in").removeClass("check_in_focus");
				$("#check_out").addClass("check_out_focus");
				$('html,body').animate({
				   scrollTop: $(".booking-form-input-2").offset().top - 190
				});

				$("#datepicker-check_in_hidden").css({"top":"","left":""});
				$(".room-guest-selection-input-wrapper").fadeOut(100);
				$(".room-guest-selection-input-wrapper").removeClass("room-guest-selection-input-open");
				e.preventDefault();
				e.stopPropagation();
				daterangepicker.open();

				// Off screen
				if ( sh_off_screen(".datepicker") == true ) {
					$(window).scroll(function(){
						if ($("#check_out").hasClass("check_out_focus")) {
							sh_datepicker_position(true,".booking-form-input-2");
						}
					});
					sh_datepicker_position(true,".booking-form-input-2");

				// On screen
				} else {
					$(window).scroll(function(){
						if ($("#check_out").hasClass("check_out_focus")) {
							sh_datepicker_position(false,".booking-form-input-2");
						}
					});
					sh_datepicker_position(false,".booking-form-input-2");
				}

			});

		}

	}

	function sh_load_open_datepicker() {

		// Open Datepicker
		if( $("#check_in_open_hidden").length ) {

			var daterangepicker = new HotelDatepicker(document.getElementById('check_in_open_hidden'), {
				disabledDates: sohohotel_blocked_dates_all,
				enableCheckout: true,
				autoClose: false,
				i18n: {
					selected: sohohotel_dp_selected,
					night: sohohotel_dp_night,
					nights: sohohotel_dp_nights,
					button: sohohotel_dp_button,
					'checkin-disabled': sohohotel_dp_checkin_disabled,
					'checkout-disabled': sohohotel_dp_checkout_disabled,
					'day-names-short': sohohotel_datepicker_days,
					'day-names': sohohotel_datepicker_days,
					'month-names-short': sohohotel_datepicker_months,
					'month-names': sohohotel_datepicker_months,
					'error-more': sohohotel_dp_error_more,
					'error-more-plural': sohohotel_dp_error_more_plural,
					'error-less': sohohotel_dp_error_less,
					'error-less-plural': sohohotel_dp_error_less_plural,
					'info-more': sohohotel_dp_info_more,
					'info-more-plural': sohohotel_dp_info_more_plural,
					'info-range': sohohotel_dp_info_range,
					'info-default': sohohotel_dp_info_default
				}
			});

			var input = document.getElementById('check_in_open_hidden');

			daterangepicker.open();

			// Check if dates have been set, and if so write them to visible date fields
			var target = document.querySelector("#check_in_open_hidden");
			var observer = new MutationObserver(function(mutations) {

				var data = $('#check_in_open_hidden').val();
				var arr = data.split(' - ');
				$('#open_date_from').val( sohohotel_format_date( arr[0] ) );
				$('#open_date_to').val( sohohotel_format_date( arr[1] ) );
				$('#check_in_alt').val( arr[0] );
				$('#check_out_alt').val( arr[1] );

			});
			var config = { attributes: true, childList: true, characterData: true };
			observer.observe(target, config);

		}

	}

	sh_load_open_datepicker();
	sh_load_datepicker();

});
