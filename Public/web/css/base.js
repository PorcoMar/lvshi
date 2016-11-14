// JavaScript Document


	//文本框默认字
	$('#research').focus(function(){
		$(this).val('');
		$(this).css({'color':'#000','font-weight':'bold'});
		
		if($('#research').val()!=''){
			$('#clearInput').show();
			
		}else if($('#research').val()==''){
			$('#clearInput').hide();
			
		}
		
	})
	$('#research').blur(function(){
		if($(this).val() == ''	){
			$(this).val($(this).attr('value'));
			$(this).css({'font-size':'14px','color':'#ced1d3','font-weight':'normal'})
		}
	})
	
	$('.input_result').val('all').css({'color':'#000','font-weight':'bold'})
	
	//搜索框文本清除
	$('#research').keyup(function(){

		if($('#research').val()!=''){
			$('#clearInput').show();
			
		}else if($('#research').val()==''){
			$('#clearInput').hide();
			
		}
	})
	
	$('#clearInput').click(function(){
		$('#research').val('');
		$(this).hide();
	})