<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
   protected $fillable = [
      'wallet', 'category', 'note', 'nominal', 'currency', 'date', 'user_id'
   ];

   public function getData($date1, $date2)
   {
      $debet = Transaction::where('nominal', '<', 0)->where('date', '<', $date1)->sum('nominal');
      $credit = Transaction::where('nominal', '>=', 0)->where('date', '<', $date1)->sum('nominal');
      $total = $credit + $debet;

      $transactions = Transaction::where('user_id', Auth::user()->id)
                                 ->whereBetween('date', array($date1, $date2))
                                 ->orderBy('date', 'asc')
                                 ->get();
      
      return ['transactions' => $transactions, 'total' =>$total];
   }
}
