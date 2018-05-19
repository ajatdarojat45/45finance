<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Input;
use Excel;
use Charts;
use DateTime;
use Carbon\Carbon;
use App\Transaction;
use Vsmoraes\Pdf\Pdf;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
   private $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

   public function index(Request $request)
	{
      $no = 0;
      $date3 = '';

      if (empty($request->date1)) {
         $date1 = date('Y/m/d');
         $date2 = date('Y/m/d');
      }else {
         $date1      =  $request->date1;
         $date2      =  $request->date2;
      }

      if ($date2 < $date1) {
         $date3 = $date2;
         $date2 = $date1;
         $date1 = $date3;
      }

      $transactions = new Transaction;
      $data = $transactions->getData($date1, $date2);

		return view('transactions.index', compact('date1', 'date2', 'data', 'no'));
	}

   public function importExcel()
	{
		if(Input::hasFile('import_file')){

         try {
            $path = Input::file('import_file')->getRealPath();
   			$data = Excel::load($path, function($reader) {
   			})->get();
            // dd($data);
   			if(!empty($data) && $data->count()){
   				foreach ($data as $key => $value) {
   					$inserts[] = [
                     'note' => $value->note,
                     'nominal' => $value->amount,
                     'category' => $value->category,
                     'wallet' => $value->account,
                     'currency' => $value->currency,
                     'date' => DateTime::createFromFormat('d/m/Y', $value->date),
                     'user_id' => Auth::user()->id,
                  ];
   				}
               // dd($inserts);
   				if(!empty($inserts)){
   					DB::table('transactions')->insert($inserts);
   					// dd('Insert Record successfully.');
                  return back()->with('success', 'Data imported');
   				}else {
                  return back()->with('warning', 'Can not import data');
               }
   			}
         } catch (\Exception $e) {
            return back()->with('warning', 'Sorry your file or data format does not comply with the rules. Please check and try again.');
         }

		}

		return back()->with('warning', 'Please select file');
	}

   public function exportToExcel($date1, $date2, $type)
	{
      $data = Transaction::select('wallet', 'category', 'note', 'nominal', 'currency', 'date')
                     ->where('user_id', Auth::user()->id)
                     ->whereBetween('date', array($date1, $date2))
                     ->orderBy('date', 'asc')
                     ->get()
                     ->toArray();

		return Excel::create('transaction', function($excel) use ($data) {
			$excel->sheet('transaction sheet', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}

   public function exportToPdf($date1, $date2)
   {
      $no = 0;
      $transactions = new Transaction;
      $data = $transactions->getData($date1, $date2);

      $html = view('transactions.indexPdf', compact('data', 'date1', 'date2', 'no'))->render();

      return $this->pdf
           ->load($html, 'A4', 'landscape')
           ->show();
   }
}
