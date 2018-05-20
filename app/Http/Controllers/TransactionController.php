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
         $date1      = date('Y/m/d');
         $date2      = date('Y/m/d');
         $dompet     = 'all';
         $kategori   = 'all';
      }else {
         $date1      =  $request->date1;
         $date2      =  $request->date2;
         $dompet     =  $request->wallet;
         $kategori   =  $request->category;
      }

      if ($date2 < $date1) {
         $date3 = $date2;
         $date2 = $date1;
         $date1 = $date3;
      }

      $transactions = new Transaction;
      $data = $transactions->getData($date1, $date2, $dompet, $kategori);

      $wallets = Transaction::where('user_id', Auth::user()->id)->groupBy('wallet')->get();
      $categories = Transaction::where('user_id', Auth::user()->id)->groupBy('category')->get();

		return view('transactions.index', compact('date1', 'date2', 'dompet', 'kategori', 'data', 'no', 'wallets', 'categories'));
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

   public function exportToExcel($date1, $date2, $type, $wallet, $category)
	{
      // mengambil data dari db
      $transactions = new Transaction;
      $transactions = $transactions->getData($date1, $date2, $wallet, $category);
      // rubah data collection ke array
      $data = $transactions['transactions']->toArray();
      // export data ke excel
		return Excel::create('transaction', function($excel) use ($data) {
			$excel->sheet('transaction sheet', function($sheet) use ($data)
	        {
				$sheet->fromArray($data);
	        });
		})->download($type);
	}

   public function exportToPdf($date1, $date2, $wallet, $category)
   {
      $no = 0;
      // mengambil data dari db
      $transactions = new Transaction;
      $data = $transactions->getData($date1, $date2, $wallet, $category);
      // export to pdf
      $html = view('transactions.indexPdf', compact('data', 'date1', 'date2', 'no'))->render();
      return $this->pdf
           ->load($html, 'A4', 'landscape')
           ->show();
   }

   public function destroy($id)
   {
      $transaction = Transaction::findOrFail($id);

      if($transaction->isOwner()){
         $transaction->delete();
      }else{
         return back()->with('warning', 'You can not delete this data.');
      }

      return back()->with('success', 'Data Deleted');
   }

   public function multipleDestroy(Request $request)
   {
      // dd($request->transactions);
      if ($request->transactions != null) {
         foreach ($request->transactions as $data) {
            $transaction = Transaction::where('id', $data)
                                       ->where('user_id', Auth::user()->id)
                                       ->first();
            $transaction->delete();
         }
         return back()->with('success', 'Data Deleted');

      }else {
         return back()->with('warning', 'Please select data.');
      }
   }

   public function ReportByCategory(Request $request)
   {
      $date3 = '';

      if (empty($request->date1)) {
         $date1      = date('Y/m/d');
         $date2      = date('Y/m/d');
         $dompet     = 'all';
         $type       = 'debet';
      }else {
         $date1      =  $request->date1;
         $date2      =  $request->date2;
         $dompet     =  $request->wallet;
         $type       =  $request->type;
      }

      if ($date2 < $date1) {
         $date3 = $date2;
         $date2 = $date1;
         $date1 = $date3;
      }

      $transactions = new Transaction;
      $chart = $transactions->reportByCategory($date1, $date2, $dompet, $type);

      $wallets = Transaction::where('user_id', Auth::user()->id)->groupBy('wallet')->get();

      return view('transactions.reportByCategory', compact('date1', 'date2', 'dompet', 'wallets', 'chart', 'type'));
   }
}
