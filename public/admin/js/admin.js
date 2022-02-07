$(function() {
    var filedval = $('#reportrangeadm').val();
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

    function cb(start, end) { $('#reportrangeadm').val(filedval); }

    $('#reportrangeadm').daterangepicker({
        startDate: start,
        endDate: end,
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


 setTimeout(function() { 
 	var filedval = $('#reportrangeadm').val();
 console.log('filedval',filedval);
    $('#reportrangeadm').val(filedval);
    }, 2000);


$(document).ready(function(){


	$('.view-poup span').click(function(){
		$('.view-poup').removeClass('showpoup');
	});

});

function change_campaign_status(campin_id,status){

	$.ajax({
		type:'POST',
		url:'/admin/update-campaign',
		headers: {
			'X-CSRF-Token': $('meta[name="_token"]').attr('content')
		},
		data:{
			campin_id:campin_id,
			status:status,
		},
		success:function(data) {
			 // console.log('data',data);
			 // return false;
			location.reload();
		}
	});
}
function campaign_view(campin_id){

	$.ajax({
		type:'POST',
		url:'/admin/campaign-view',
		headers: {
			'X-CSRF-Token': $('meta[name="_token"]').attr('content')
		},
		data:{
			campin_id:campin_id
		},
		success:function(response) {
			const data = JSON.parse(response);
			$('.view-poup').addClass('showpoup');
			$('#bannerimage').attr('src',data.banner_image);
			$('#adver_name').html(data.advertiser_name);
			$('#admin_approval').html(data.admin_approval);
			$('#budget').html(data.budget);
			$('#campaign_name').html(data.campaign_name);
			$('#description').html(data.description);
			$('#landing_url').html(data.landing_url);
			$('#pay_charges').html(data.pay_charges);
			$('#spend_type').html(data.spend_type);
		}
	});
}


/* change date */


$('.status input[type="checkbox"]').click(function(){
	var id = $(this).attr('attrtid');
	var key = 'admin_status';
	
	if($(this).prop("checked") == true){ var value = '1'; }
	else if($(this).prop("checked") == false){ var value = '0'; }

	$.ajax({
		type:'POST',
		url:'/admin/publishers/groupStatus',
		headers: {
			'X-CSRF-Token': $('meta[name="_token"]').attr('content')
		},
		data:{
			telegrm_group:id,
			key:key,
			value:value,
		},
		success:function(res) { }
	});
});