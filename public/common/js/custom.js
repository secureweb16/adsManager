$(document).ready( function () {

	$('.edit-profile').click(function(){
		$('.readonly').removeAttr('readonly');
		$('.showdiv').show();
	});

	$('#frequency').click(function(){
		$(this).css({ 'border' : 'solid'});
		$(this).removeAttr('readonly');
		$('button').show();
	});

	$('.default input[type="checkbox"]').click(function(){
		var id = $(this).attr('attrtid');
		var key = 'status';
		if($(this).prop("checked") == true){
			var value = 'Yes';
		}
		else if($(this).prop("checked") == false){
			var value = 'No';
		}
		update_telegram_frequency(id,key,value);
	});

	$('.status input[type="checkbox"]').click(function(){
		var id = $(this).attr('attrtid');
		var key = 'status';
		if($(this).prop("checked") == true){
			var value = '1';
		}
		else if($(this).prop("checked") == false){
			var value = '0';
		}
		// update_telegram_frequency(id,key,value);
		$.ajax({
			type:'POST',
			url:'/publisher/settings/telegram-update',
			headers: {
				'X-CSRF-Token': $('meta[name="_token"]').attr('content')
			},
			data:{
				telegrm_group:id,
				key:key,
				value:value,
			},
			success:function(res) {}
		});
	});

	$('.checkverify').click(function(){

		$('#errormsg').html('');
		var id = $(this).attr('attrtid');
		var key = 'verify';
		var value = '1';
		$.ajax({
			type:'POST',
			url:'/publisher/settings/telegram-update',
			headers: {
				'X-CSRF-Token': $('meta[name="_token"]').attr('content')
			},
			data:{
				telegrm_group:id,
				key:key,
				value:value,
			},
			success:function(res) {
				console.log('res',res);
				if(res == 'success'){
					swal({
						title: "Verified",
						text: "Your group is verified",
						type: 'success'
					});
					$('.class_'+id).addClass('verified');
					$('.class_'+id).html('Verified');
				}else{
					swal({
						title: "Error",
						text: "Either your group name is incorrect or You have added wrong bot to your group.",
						type: 'error'
					});					
				}
			}
		});
	});

	$('.inactive .adminstatus').click(function(){	
		swal({
			title: "Error",
			text: "You can not make active please contact to support",
			type: 'error'
		}); 
	});

});

function update_telegram_frequency(id,key,value){
	if(key == 'frequency'){
		var value = $('#frequency').val();	
		if(value <= '0'){
			alert('Frequency Value minimun 1');
			return false;
		}
	}
	$.ajax({
		type:'POST',
		url:'/publisher/settings/telegram-update',
		headers: {
			'X-CSRF-Token': $('meta[name="_token"]').attr('content')
		},
		data:{
			telegrm_group:id,
			key:key,
			value:value,
		},
		success:function(res) {
			// swal('Done');
			location.reload();			
		}
	});
}



function getOptionStart(select) {
	var startkey  = $('select[name="from_all_days"]').find(":selected").attr('key');
	var endkey  = $('select[name="to_all_days"]').find(":selected").attr('key');
	startkey = parseInt(startkey,10);
	endkey = parseInt(endkey,10);
	if(startkey >= endkey){
		startkey = startkey+1;
		$('select[name="to_all_days"] option:selected').attr("selected",null);
		$('select[name="to_all_days"] option[key="'+startkey+'"]').attr("selected","selected");
	}
	if(startkey == 24){
		swal({
			title: "Error",
			text: "Start date not be 11:00 PM",
			type: 'error'
		}); 
	}

}

function getOptionEnd(select) {
	var endkey  = $('select[name="to_all_days"]').find(":selected").attr('key');
	var startkey  = $('select[name="from_all_days"]').find(":selected").attr('key');
	startkey = parseInt(startkey,10);
	endkey = parseInt(endkey,10);
	if(startkey >= endkey){
		endkey = endkey-1;
		$('select[name="from_all_days"] option:selected').attr("selected",null);
		$('select[name="from_all_days"] option[key="'+endkey+'"]').attr("selected","selected");
	}

	if(endkey == -1){
		swal({
			title: "Error",
			text: "End date not be 00:00 AM",
			type: 'error'
		}); 
	}
}


function getCustomOptionStart(day) {
	var startkey  = $('.'+day+'.start').find(":selected").attr('key');
	var endkey  = $('.'+day+'.end').find(":selected").attr('key');
	startkey = parseInt(startkey,10);
	endkey = parseInt(endkey,10);

	if(startkey >= endkey){
		startkey = startkey+1;  
		$('.'+day+'.end option:selected').attr("selected",null);
		$('.'+day+'.end option[key="'+startkey+'"]').attr("selected","selected");
	}
	if(startkey == 24){
		swal({
			title: "Error",
			text: "Start date not be 11:00 PM",
			type: 'error'
		}); 
	}
}

function getCustomOptionEnd(day) {
	var endkey  = $('.'+day+'.end').find(":selected").attr('key');
	var startkey  = $('.'+day+'.start').find(":selected").attr('key');
	startkey = parseInt(startkey,10);
	endkey = parseInt(endkey,10);
	
	if(startkey >= endkey){
		endkey = endkey-1;
		$('.'+day+'.start option:selected').attr("selected",null);
		$('.'+day+'.start option[key="'+endkey+'"]').attr("selected","selected");
	}
	if(endkey == -1){
		swal({
			title: "Error",
			text: "End date not be 00:00 AM",
			type: 'error'
		}); 
	}
}