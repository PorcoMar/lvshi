
//图片变大小
function imageSizeChange() {
	var imageWidth = $(".visimg").width();
	
	$(".visimg").find("img").css("width", imageWidth );
}
function imageSizeChange02() {
	var imageWidth = $(".visimg02").width();
	
	$(".visimg02").find("img").css("width", imageWidth );
}
function imageSizeChange03() {
	var imageWidth = $(".visimg03").width();
	
	$(".visimg03").find("img").css("width", imageWidth );
}
function imageSizeChange04() {
	var imageWidth = $(".visimg04").width();
	
	$(".visimg04").find("img").css("width", imageWidth );
}
function imageSizeChange05() {
	var imageWidth = $(".visimg05").width();
	
	$(".visimg05").find("img").css("width", imageWidth );
}



jQuery(document).ready(function() {
	//alert($(".visimg").width())
	imageSizeChange();
	imageSizeChange02();
	imageSizeChange03();
	imageSizeChange04();
	imageSizeChange05();
	
	window.onorientationchange = function() {
		imageSizeChange();
		imageSizeChange02();
		imageSizeChange03();
		imageSizeChange04();
		imageSizeChange05();
	}
	window.onresize = function() {
		imageSizeChange();
		imageSizeChange02();
		imageSizeChange03();
		imageSizeChange04();
		imageSizeChange05();
	}

});

//头部菜单
$(function(){
		
		
		$('.menu_sub').click(function(){
			if($('.bot_menu').is(':visible')){
				$('.bot_menu').hide();	
			}else if($('.bot_menu').is(':hidden')){
				$('.bot_menu').show();	
			}	
		})
		$('.bot_menu li').click(function(){
			if($('.bot_menu').is(':visible')){
				$('.bot_menu').hide();	
			}else if($('.bot_menu').is(':hidden')){
				$('.bot_menu').show();	
			}	
		})
		
		
		
		
	})






