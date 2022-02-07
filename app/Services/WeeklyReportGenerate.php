<?php

namespace App\Services;

use App\Models\PublisherReport;
use App\Models\PublisherPayment;
use App\Models\User;
use App\Models\PublisherReportCsv;
use App\Models\CampaignClicksIpTracking;
use Response;
use Auth;


class WeeklyReportGenerate{
	
	public function __construct(){		
		return $this->weekly_report_generate();
	}

	private function weekly_report_generate(){	
		$publisher = $this->get_publisher_and_amount();
		if(count($publisher)){
			$this->generate_csv_file($publisher);
		}
	}


	/* Get publisher and payable amount */

	private function get_publisher_and_amount(){
		return User::whereHas('publisherreportdata',function($query) {
			$query->where('status','=','0');
		})->withSum(['publisherreportdata' => function($query) {
      $query->where('status','=','0');
    }],'payable_amount')->with(['publisherreportdata'])->get();
	}


	/* Generate Csv File */

	private function generate_csv_file($publisher){
		$ids = $data = array();
		foreach ($publisher as $key => $value) {
			$data[] = array(
				'publisher_name'	=>	$value->first_name." ".$value->last_name,
				'publisher_email'	=>	$value->email,
				'payable_amount'	=>	$value->publisherreportdata_sum_payable_amount,
				'wallet_address'	=>	wallet_address($value->id),
			);
			foreach($value->publisherreportdata as $pubReport){
				$ids[] = $pubReport->id;
			}
		}		

    /*$path = storage_path('app/public/publisherReport/');*/
    
    $path = base_path('public/publisherReport/');
    $fileName = 'publisherReport_'.date('Y_m_d').'.csv';
    $file = fopen($path.$fileName, 'w');
    $columns = array('Publisher Name','Publisher Email','Payable Amount','Wallet Address');

    fputcsv($file, $columns);
		foreach ($data as $row) {
    	fputcsv($file, $row);
  	}
    fclose($file);

    $publisherReportCsv = new PublisherReportCsv();
    $publisherReportCsv->csv_name = $fileName;
    $publisherReportCsv->save();
    PublisherReport::whereIn('id', $ids)->update(['status' => '1']);
	}


	/* CSV Header */

	private function get_csv_header(){
		$headers = [
			'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
			'Content-type'        => 'text/csv',
			'Content-Disposition' => 'attachment; filename=publisherReport.csv',
			'Expires'             => '0',
			'Pragma'              => 'public',
		];
		return $headers;		
	}


}
