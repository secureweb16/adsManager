<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PublisherPayment;
use App\Models\PublisherReportCsv;
use Response;

class ImportExportController extends Controller
{
	public function ecport_view(){
		$allReport = PublisherReportCsv::all();
		return view('admin.importExport.export',compact('allReport'));
	}

	public function ecport_csv_file(){

		$headers = [
			'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
			'Content-type'        => 'text/csv',
			'Content-Disposition' => 'attachment; filename=publisherReport.csv',
			'Expires'             => '0',
			'Pragma'              => 'public',
		];

		$publisherPayments = PublisherPayment::with(['publisher_data'])->get();

		if(count($publisherPayments) > 0){

			foreach ($publisherPayments as $key => $value) {
				$data[] = array(
					'publisher_name'	=>	$value->publisher_data->first_name." ".$value->publisher_data->last_name,
					'publisher_email'	=>	$value->publisher_data->email,
					'paid_amount'			=>	$value->paid_amount,
					'payable_amount'	=>	$value->payable_amount,
					'total_amount'		=>	$value->total_amount,
				);			
			}

			$columns = array('Publisher Name', 'Publisher Email','Paid Amount','Payable Amount','Total Amount');

			$callback = function() use ($data,$columns){
				$file = fopen('php://output', 'w');
				fputcsv($file, $columns);
				foreach ($data as $row) { 
					fputcsv($file, $row);
				}
				fclose($file);
			};

			return Response::stream($callback, 200, $headers);
		}else{
			return redirect()->back()->with('message', 'Currently data is not avaliable');
		}

	}


	public function import_csv_file(){
		return view('admin.importExport.import');
	}

	public function mark_paid($id){

		$pubreportcsv = PublisherReportCsv::findOrFail(decrypt($id));
		$destinationPath  = base_path('public/publisherReport');
		$file_path = $destinationPath.'/'.$pubreportcsv->csv_name;

		$file = fopen($file_path, "r");
		$all_data = array();

		$i = 0;
		while ( ($row = fgetcsv($file, 200, ",")) !==FALSE) {
			if($i != 0){
				$user = User::where('email',$row[1])->first();
				if(!empty($user)){
					$pulisherid = $user->id;
					$publisherData = PublisherPayment::where('publisher_id',$pulisherid)->first();						
					if(!empty($publisherData)){
						
						$publisherid = $publisherData->id;

						$old_paid_amount = (!empty($publisherData->paid_amount))?trim($publisherData->paid_amount):0;
						$new_paid_amount = (isset($row[2]))?(float)$row[2]:0;
						$final_paid_amount = $old_paid_amount+$new_paid_amount;

						$old_payable_amount = (!empty($publisherData->payable_amount))?trim($publisherData->payable_amount):0;
						$new_payable_amount = (isset($row[2]))?(float)$row[2]:0;
						$final_payable_amount = $old_payable_amount - $new_payable_amount;

						PublisherPayment::where('id',$publisherid)					
						->update([							
							'paid_amount' 		=> $final_paid_amount,
							'payable_amount' 	=> $final_payable_amount
						]);
					}
				}
			}
			$i++;
		}
		fclose($file);

		PublisherReportCsv::where('id',decrypt($id))->update([ 'mark_paid' => '1' ]);
		return redirect()->route('admin.export.csv');
	}

	public function import_publisher_csv(Request $request) {
		return redirect()->back()->with('message', 'Comming Soon!');
		$request->validate([
			'csv_file'    => 'required|file|mimes:csv,txt|max:2048',
		]);
		// echo "dfbfd";
		// exit;
		// echo "<pre>";
		$image = $request->file('csv_file');
		echo "<pre>";
    	print_r($image);

		$pathinfo = $request->file('csv_file')->getClientOriginalName();

		print_r($pathinfo);
		exit;

		$filename = pathinfo($pathinfo, PATHINFO_FILENAME);
		$extension = pathinfo($pathinfo, PATHINFO_EXTENSION);

		$fullname = $filename . '.' . $extension; 

    // print_r($fullname);
    // exit;

		$destinationPath  = base_path('public/csvfile');
    	// $advertiser_id    = Auth::user()->id;
		$image->move($destinationPath, $fullname);

		$file_path = $destinationPath.'/'.$fullname;

		$file = fopen($file_path, "r");
		$all_data = array();

		$i = $j = $k = $x = $y = 0;

		while ( ($row = fgetcsv($file, 200, ",")) !==FALSE) {

			if($i != 0){
				
				if(isset($row[1])){

					$user = User::where('email',$row[1])->first();
					if(!empty($user)){						
						$pulisherid = $user->id;
						$publisherData = PublisherPayment::where('publisher_id',$pulisherid)->first();
						
						if(!empty($publisherData)){
							
							$publisherid = $publisherData->id;
							// exit;

							$old_no_of_clicks = $publisherData->no_of_clicks;
							$new_no_of_clicks = (isset($row[3]))?(int)$row[3]:0;
							$final_no_of_clicks = $old_no_of_clicks+$new_no_of_clicks;
							
							$old_paid_amount = $publisherData->paid_amount;
							$new_paid_amount = (isset($row[4]))?(float)$row[4]:0;
							$final_paid_amount = $old_paid_amount+$new_paid_amount;

							$old_payable_amount = $publisherData->payable_amount;
							$new_payable_amount = (isset($row[5]))?(float)$row[5]:0;
							$final_payable_amount = $old_payable_amount+$new_payable_amount;

							$old_total_amount = $publisherData->total_amount;
							$new_total_amount = (isset($row[6]))?(float)$row[6]:0;
							$final_total_amount = $old_total_amount+$new_total_amount;

							PublisherPayment::where('id', $publisherid)					
							->update([
								'no_of_clicks' 		=> $final_no_of_clicks,
								'paid_amount' 		=> $final_paid_amount,
								'payable_amount' 	=> $final_payable_amount,
								'total_amount' 		=> $final_total_amount
							]);
							$x++;
						}else{
							$publisherreport = new PublisherPayment();
							$publisherreport->publisher_id 		= $pulisherid;
							$publisherreport->no_of_clicks 		= (isset($row[3]))?$row[3]:0;
							$publisherreport->paid_amount 		= (isset($row[4]))?$row[4]:0;
							$publisherreport->payable_amount 	= (isset($row[5]))?$row[5]:0;
							$publisherreport->total_amount 		= (isset($row[6]))?$row[6]:0;
							$publisherreport->save();	
							$y++;					
						}
						$j++;
					}else{
						$k++;
					}
				}				
			}
			$i++;
		}
		fclose($file);
		
		echo $message = $j.' user payment is update. '.$k." user not exist";
		return redirect()->route('admin.import.csv')->with('message', $message);
	}
}
