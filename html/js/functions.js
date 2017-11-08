$(function(){

	// select默认选中项颜色
	var unSelected = "#999";
	var selected = "#333";
	$("select").css("color", unSelected);
	$("option").css("color", selected);
	$("select").change(function () {
		var selItem = $(this).val();
		if (selItem == $(this).find('option:first').val()) {
			$(this).css("color", unSelected);
		} else {
			$(this).css("color", selected);
		}
	});

	// 文本更多
	 $(".m-editor").each(function() {
	 	var _this = $(this)
	 	var height = _this.height();
	 	var more = _this.find('.m-more');
	 	var len = _this.has(".m-more").length;
	 	if ( len > 0 && height > 55 ) {
	 		_this.css({'height': '4.5em','padding-bottom':'1.5em'});
			more.css({'height': '1.5em'});
	 		more.show();
	 		more.click(function() {
	 			if ( !_this.hasClass('active') ) {
	 				_this.addClass('active');
	 			} else {
	 				_this.removeClass('active');
	 			}
	 		});
	 	} else {
	 		more.hide();
	 	}
	 });



});
$(function() {
	function Tab(args){
		var tabMenu = args.tabMenu;
		var tabCont = args.tabCont;
		var evt = args.evt || 'click'
		tabMenu.eq(0).addClass('active');
		tabCont.eq(0).show().siblings().hide();
		tabMenu[evt](function(){
			var _this = $(this);
			var _index = tabMenu.index(_this);
			_this.addClass('active').siblings().removeClass('active');
			tabCont.eq(_index).show().siblings().hide();
			return false;
		});
	}
	new Tab({
		tabMenu : $('.detail_nav a'),
		tabCont : $('.detail_wrap .detail_main'),
		evt     : 'click'
	});
});
function  forum_type() {

	// 论坛分类
	if ($(".classify_tab")[0]) {
		//var tabTop = $(".classify_tab").offset().top;
		//console.log("tabTop=" + tabTop);
		//$(window).scroll(function () {
		//	var winTop = $(window).scrollTop();
		//	var topVal = tabTop+0;
		//	if (winTop >= topVal) {
		//		 $(".classify_tab").addClass("cTab_fixed");
		//	} else {
		//		 $(".classify_tab").removeClass("cTab_fixed");
		//	}
		//	// 当顶部搜索悬浮存在时
		//	if ($(".index_header")[0]) {
		//		var headH = $(".index_header").outerHeight();
		//		nearTop = tabTop - headH;
		//		if (winTop > nearTop) {
		//			$(".index_header").hide();
		//		} else {
		//			$(".index_header").show();
		//		}
		//	}
		//	if ($(".list_goods")[0]) {
		//		var tabH = $(".classify_tab").outerHeight();
		//		if ($(".classify_tab").hasClass("cTab_fixed")) {
		//			$(".list_goods").css("padding-top", tabH);
		//		} else {
		//			$(".list_goods").css("padding-top", "");
		//		}
		//	}
		//});
		/*$(document).on("click", ".cTab_more", function () {
			console.log(1);
			// var winTop = $(window).scrollTop();
			// if (winTop < tabTop) {
				// var top = tabTop + 1;
				// $("html,body").animate({scrollTop: top}, 0);
				// $("html,body").addClass("cTab_show");
				// $(".classify_tab").addClass("cTab_fixed");
				$(".classify_tab").toggleClass("cTab_hover");
			// } else {
				// $("html,body").toggleClass("cTab_show");
				// $(".classify_tab").toggleClass("cTab_hover");
			// }
		});*/
		$(document).on("click", ".cTab_msk", function () {
			$(".classify_tab").removeClass("cTab_hover");
			$("html,body").removeClass("cTab_show");
		});
	}
}
function words_deal(){
	// 字数限制
	var curLength=$("#textArea").val().length;
	if(curLength>100){
		var num=$("#textArea").val().substr(0,100);
		$("#textArea").val(num);
		alert("超过字数限制，多出的字将被截断！" );
	}
	else{
		$(".textNum em").text(0+$("#textArea").val().length);
	}
}
function imgHeight(){
	// 图片限定高度
	$(".item-pic").each(function(){
		var w = $(this).width();
		$(this).css({"height": w});
	});
}
function imgHeight2(){
	// 图片限定高度
	$(".item-pic").each(function(){
		var w = $(this).width();
		var h = w*0.63;
		$(this).css({"height": h});
	});
}
//获取底部菜单中购物车数量
function getCartNum(url,type){
	$.ajax({
		url:url,
		dataType:"json",
		async:true,
		data:{},
		type:"POST",
		beforeSend:function(){
		},
		success:function(data){
			if(type==1){
				var cartHtml = "购物车<em>"+data.num+"</em>";
			}else{
				var cartHtml = "<em>"+data.num+"</em>";
			}

			if(data.num >0){
				$(".hint-num ").html(cartHtml);
			}else{
				$(".hint-num").html();
			}
		},
		complete:function(){
		},
		error:function(){
		}
	})
}
