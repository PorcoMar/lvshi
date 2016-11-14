		var senctionID = 0;
		var senctionArray = $('.scroll-section');
		var isScrolling=false;
		//项目加载完成后运行的项目
		$(document).ready(function() {
			adjustFatherDiv();
			adjustSenctions();
			buildLittleNavi();
			JumpToAnotherSection(0);
			setListenner();
		});
		//让父div铺满除了header以外剩余的空间
		function adjustFatherDiv() {
			var window_height = $(window).height();
			$('#father-div').height(window_height);
		}
		//让每个section铺满父div
		function adjustSenctions() {
			var father_div_height = $('#father-div').height();
			$('.scroll-section').height(father_div_height);
		}
		//添加各类监听
		function setListenner() {
			scrollListener();
			setLittleNaviClickListener();
			setArrowDownClickListener();
			setArrowTopClickListener();
		}
		//添加滚轮滑动监听
		function scrollListener() {
			$('#father-div').on("mousewheel DOMMouseScroll", function(e) {
				e.preventDefault();
				if(isScrolling==false){
					isScrolling=true;
				var delta = (e.originalEvent.wheelDelta && (e.originalEvent.wheelDelta > 0 ? 1 : -1)) || // chrome & ie
					(e.originalEvent.detail && (e.originalEvent.detail > 0 ? -1 : 1)); // firefox
				JumpToAnotherSection(delta);

				}
			});
		}
		//根据delta的值跳转到相应的senction，0不动，1上一个，-1下一个
		function JumpToAnotherSection(delta) {
			senctionID = senctionID - delta;
			if (senctionID < 0) {
				senctionID = 0
			}
			if (senctionID > senctionArray.length-1) {
				senctionID = senctionArray.length-1
			};
			$('#father-div').stop().animate({
				scrollTop:$('#father-div').innerHeight()*senctionID+"px"
			}, 500,function(){
				isScrolling=false;
			});
			
			MakeElementHideOrShow();
			updateLittleNavi();
		}
		//根据senction的数量动态的创建小圆点
		function buildLittleNavi(){
			for(var i=0;i<senctionArray.length;i++){
				var item=$("<div class='little-Navi-item'></div>");
				$('#little-Navi').append(item);				
				var img=$("<img class='little-Navi-img' src='./Public/web/img/t1.png' />")
				var str='little-Navi-img-'+i;
				$(img).attr("id",str);
				$(item).append(img);
			}
		}
		
		//更新小圆点的状态，被选中的页面相应小圆点变红。
		function updateLittleNavi(){
			var items=$('.little-Navi-img');
			for(var i=0;i<items.length;i++){
				if($(items[i]).attr('id')==('little-Navi-img-'+senctionID)){
					$(items[i]).attr('src','/Public/web/img/t2.png')
				}else{
					$(items[i]).attr('src','/Public/web/img/t1.png')
				}
			}
		}
		
		//给小圆点添加点击事件
		function setLittleNaviClickListener(){
			var items=$('.little-Navi-img');
			for(var i=0;i<senctionArray.length;i++){
				$(items[i]).click(function(){
					var id=parseInt($(this).attr("id").substring(16,$(this).attr("id").length));
					var delta=senctionID-id;
					JumpToAnotherSection(delta);
				})
			}
		}
		
		//给下箭头设置点击事件；
		function setArrowDownClickListener(){
			$('#arrow-down').click(function(){
				JumpToAnotherSection(-1);
				
			})
		}
		//给上箭头设置点击事件
		function setArrowTopClickListener(){
			$('#arrow-top').click(function(){
				JumpToAnotherSection(senctionID);

			})
		}
		
		//每次完成跳转之后判定一下哪些元素要显示哪些不要。
		function MakeElementHideOrShow(){
			if(senctionID==senctionArray.length-1){
				$('#arrow-down').hide();
			}else{
				$('#arrow-down').show();
			}
			
			if(senctionID==0){
				$('#arrow-top').hide();
			}else{
				$('#arrow-top').show();
			}
			
		};
/********************************/
 	/* 长宽占位 rem算法, 根据root的rem来计算各元素相对rem, 默认html 320/20 = 16px */
 	function placeholderPic(){
  		var w = document.documentElement.offsetWidth||document.body.offsetWidth ;
  		
  		document.documentElement.style.fontSize= w / 20 + 'px' ;
  		document.body.style.fontSize = w / 20 + 'px' ;
 	}
		placeholderPic();
 	window.onresize = function(){
  		placeholderPic();
 	}	