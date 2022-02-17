$(function() {

	var filedval = $('#advreportrange').val();
	var days = 0;
	var start = moment().subtract(days, 'days');
	var end = moment();
	if(filedval != 'Select the date to see the clicks' && typeof filedval != 'undefined'){
		const filedate = filedval.split("-");
		let startD = filedate[0].trim();
		let endD = filedate[1].trim();
		var days = daysdifference(startD, endD);
		var startdate = new Date(endD);
		var start = moment(startdate).subtract(days, 'days');
		var end = moment(endD);
	}

	function cb(start, end) { $('#advreportrange').val(filedval); }

	$('#advreportrange').daterangepicker({
		linkedCalendars: false,
		startDate: start,
		endDate: end,
		maxDate: moment(),
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
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
	var filedval = $('#advreportrange').val();
	$('#advreportrange').val(filedval);
}, 2000);

function change_campaign_status(campin_id,status){
	$.ajax({
		type:'POST',
		url:'/advertiser/update-campaign',
		headers: {
			'X-CSRF-Token': $('meta[name="_token"]').attr('content')
		},
		data:{
			campin_id:campin_id,
			status:status,
		},
		success:function(data) {
			location.reload();
		}
	});
}

function change_clicks_ondate_dashboard(){
	var datadate = $('#mydate').val();
	var url = window.location.href;
	if (window.location.href.indexOf("onDate") > -1) {
		var finalUrl1 = url.substring(0, url.indexOf('?'));
		var finalUrl = finalUrl1+"?onDate="+datadate;
	}else{
		var finalUrl = url+"?onDate="+datadate;
	}
	window.location.href = finalUrl;
}
