$(function() {

	var filedval = $('#pubreportrange').val();    
	var days = 0;
	var start = moment().subtract(days, 'days');
	var end = moment();
	if(filedval != 'Select the date' && typeof filedval != 'undefined'){
		const filedate = filedval.split("-");
		let startD = filedate[0].trim();
		let endD = filedate[1].trim();
		var days = daysdifference(startD, endD);
		var startdate = new Date(endD);
		var start = moment(startdate).subtract(days, 'days');
		var end = moment(endD);
	}

	function cb(start, end) { $('#pubreportrange').val(filedval); }

	$('#pubreportrange').daterangepicker({
		linkedCalendars: false,
		startDate: start,
		endDate: end,
		maxDate: moment(),
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment()],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		},
	}, cb);
	cb(start, end);
});

function daysdifference(firstDate, secondDate){
	var startDay = new Date(firstDate);
	var endDay = new Date(secondDate);
	var millisBetween = startDay.getTime() - endDay.getTime();
	var days = millisBetween / (1000 * 3600 * 24);
	return Math.round(Math.abs(days));
}

setTimeout(function() {
	var filedval = $('#pubreportrange').val();
	$('#pubreportrange').val(filedval);
}, 2000);

function send_email(){
	var value = $('#quatesupport').val();
	$('#loader').show();
	$.ajax({
		type:'POST',
		url:'/support',
		headers: {
			'X-CSRF-Token': $('meta[name="_token"]').attr('content')
		},
		data:{ value:value },
		success:function(res) {
			if(res == 'true'){
				$('.msg-email').html('Email Sent Successfully');
				$('#loader').hide();
				setTimeout(function(){ location.reload(); }, 3000);
			}
		}
	});
}

function update_notification_user(notification_id,redirect_url){
	$.ajax({
		type:'POST',
		url:'/update_notification',
		headers: {
			'X-CSRF-Token': $('meta[name="_token"]').attr('content')
		},
		data:{ notification_id:notification_id },
		success:function(res) {
			if(res == 'true'){
				if(redirect_url != ''){
					window.location = redirect_url;
				}else{
					location.reload();
				}
			}
		}
	});
}

function update_notification_admin(notification_id,redirect_url){
	$.ajax({
		type:'POST',
		url:'/update_notification_admin',
		headers: {
			'X-CSRF-Token': $('meta[name="_token"]').attr('content')
		},
		data:{ notification_id:notification_id },
		success:function(res) {
			if(res == 'true'){
				if(redirect_url != ''){
					window.location = redirect_url;
				}else{
					location.reload();
				}
			}
		}
	});
}

$(document).ready(function() {
	$("input[type='checkbox']").click(function() {
		var value = $(this).val();
		if ($(this).prop("checked")) {
			$('.'+value).removeAttr('disabled');
		} else {
			$('.'+value).attr('disabled','disabled');
		}
	});
});