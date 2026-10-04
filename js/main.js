// Sticky nav
$(window).scroll(function () {
	var scroll = $(window).scrollTop();

	if (scroll >= 50) {
		$(".navigation-holder").addClass("menu-scroll");
	}
	else {
		$(".navigation-holder").removeClass("menu-scroll");
	}
});
 


$(function(){
"use strict";
// WRAP TABLES IN SCROLLABLE DIV, FOR MOBILE
	$('table').wrap('<div class="scrollable"></div>');
	$('table.unwrap').unwrap();
	
	
	// DISPLAY SWIPE ICON 
	// before scroll
	$('.scrollable').on('scroll', function(){
		var scrollHorizontal = $(this).scrollLeft();
		if (scrollHorizontal > 0) {
		$(this).addClass('scrolledR');
		}
	});
	
	});

function printPage()
{
	window.print();
}

//function to email page
function mailPage()
{
  mail_str = "mailto:?subject= " + document.title;
  mail_str += "&body= I recommend you read this -- " + document.title;
  mail_str += ". You should check this out at, " + location.href; 
  location.href = mail_str;
}

// Back to top
function popTop() {

	if (document.documentElement.scrollTop >= 10) {
		$('.left-ani').css({
			"transform": "translateX(0)"
		});

	} else {
		$('.left-ani').css({
			"transform": "translateX(130px)"
		});
	}
}

function gotop() {
	$('.scroll-btn').click(function () {
		$("body,html").animate({
			scrollTop: 0
		}, 600);
	});
}

$(window).scroll(function () {
	var scroll = $(window).scrollTop();
	var wScroll = $(this).scrollTop();

	popTop();	

});

$(document).ready(function () {
	gotop();
});