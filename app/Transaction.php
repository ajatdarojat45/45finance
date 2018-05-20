<?php

namespace App;

use Auth;
use Charts;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
   protected $fillable = [
      'wallet', 'category', 'note', 'nominal', 'currency', 'date', 'user_id'
   ];

   public function user()
   {
      return $this->belongsTo(User::class);
   }

   public function getData($date1, $date2, $wallet, $category)
   {
      $user = Auth::user()->id;

      if ($wallet == 'all') {
         // mengambil jumlah pengeluaran yang lebih kecil dari date1
         $debet = Transaction::where('user_id', $user)
                              ->where('nominal', '<', 0)
                              ->where('date', '<', $date1)
                              ->sum('nominal');
         // mengambil jumlah pemasukan yang lebih kecil dari date1
         $credit = Transaction::where('user_id', $user)
                              ->where('nominal', '>=', 0)
                              ->where('date', '<', $date1)
                              ->sum('nominal');
         // menghitung jumlah saldo yang lebih kecil dari date1
         $total = $credit + $debet;

         if ($category == 'all') {
            // mengambil data transaksi diantara tanggal yang di tentukan
            $transactions = Transaction::where('user_id', $user)
                                       ->whereBetween('date', array($date1, $date2))
                                       ->orderBy('date', 'asc')
                                       ->get();
         }else{
            // mengambil data transaksi diantara tanggal yang di tentukan
            $transactions = Transaction::where('user_id', $user)
                                       ->where('category', $category)
                                       ->whereBetween('date', array($date1, $date2))
                                       ->orderBy('date', 'asc')
                                       ->get();
         }
      }else {
         // mengambil jumlah pengeluaran dari dompet tertentu yang lebih kecil dari date1
         $debet = Transaction::where('user_id', $user)
                              ->where('nominal', '<', 0)
                              ->where('wallet', $wallet)
                              ->where('date', '<', $date1)
                              ->sum('nominal');
         // mengambil jumlah pemasukan dari dompet tertentu yang lebih kecil dari date1
         $credit = Transaction::where('user_id', $user)
                              ->where('nominal', '>=', 0)
                              ->where('wallet', $wallet)
                              ->where('date', '<', $date1)
                              ->sum('nominal');
         // menghitung jumlah saldo yang lebih kecil dari date1
         $total = $credit + $debet;

         if ($category == 'all') {
            // mengambil data transaksi diantara tanggal yang di tentukan
            $transactions = Transaction::where('user_id', $user)
                                       ->where('wallet', $wallet)
                                       ->whereBetween('date', array($date1, $date2))
                                       ->orderBy('date', 'asc')
                                       ->get();
         }else{
            // mengambil data transaksi diantara tanggal yang di tentukan
            $transactions = Transaction::where('user_id', $user)
                                       ->where('wallet', $wallet)
                                       ->where('category', $category)
                                       ->whereBetween('date', array($date1, $date2))
                                       ->orderBy('date', 'asc')
                                       ->get();
         }
      }

      return ['transactions' => $transactions, 'total' => $total];
   }

   public function isOwner()
   {
      if(Auth::guest())
           return false;

     return Auth::user()->id == $this->user->id;
   }

   public function reportByCategory($date1, $date2, $wallet, $type)
   {
      $user = Auth::user()->id;
      if ($type == 'debet') {
         if ($wallet == 'all') {
            // get data
            $debets = Transaction::where('user_id', $user)
                                 ->where('nominal', '<', 0)
                                 ->whereBetween('date', array($date1, $date2))
                                 ->get();

            $debetValues = $debets->groupBy('category')
                                 ->map(function ($row) {
                                    return $row->sum('nominal');
                                 });
            // change - to +
            if (count($debetValues) != 0) {
               foreach ($debetValues as $debetValue => $value) {
                  $values[] = $value * -1;
               }
            }else{
               $values[] = '';
            }
            // get labels
            if (count($debets) != 0) {
               foreach ($debets as $debet) {
                  $labels[] = $debet->category;
               }
            }else{
               $labels[] = '';
            }

         }else {
            // get data
            $debets = Transaction::where('user_id', $user)
                                 ->where('nominal', '<', 0)
                                 ->where('wallet', $wallet)
                                 ->whereBetween('date', array($date1, $date2))
                                 ->get();

            $debetValues = $debets->groupBy('category')
                                 ->map(function ($row) {
                                    return $row->sum('nominal');
                                 });
            // change - to +
            if (count($debetValues) != 0) {
               foreach ($debetValues as $debetValue => $value) {
                  $values[] = $value * -1;
               }
            }else{
               $values[] = '';
            }
            // get labels
            if (count($debets) != 0) {
               foreach ($debets as $debet) {
                  $labels[] = $debet->category;
               }
            }else{
               $labels[] = '';
            }
         }
      }else {
         if ($wallet == 'all') {
            // get data
            $credits = Transaction::where('user_id', $user)
                                 ->where('nominal', '>=', 0)
                                 ->whereBetween('date', array($date1, $date2))
                                 ->get();

            $values = $credits->groupBy('category')
                                 ->map(function ($row) {
                                    return $row->sum('nominal');
                                 });

            // get labels
            if (count($credits) != 0) {
               foreach ($credits as $credit) {
                  $labels[] = $credit->category;
               }
            }else{
               $labels[] = '';
            }
         }else {
            // get data
            $credits = Transaction::where('user_id', $user)
                                 ->where('nominal', '>=', 0)
                                 ->where('wallet', $wallet)
                                 ->whereBetween('date', array($date1, $date2))
                                 ->get();

            $values = $credits->groupBy('category')
                                 ->map(function ($row) {
                                    return $row->sum('nominal');
                                 });

            // get labels
            if (count($credits) != 0) {
               foreach ($credits as $credit) {
                  $labels[] = $credit->category;
               }
            }else{
               $labels[] = '';
            }
         }
      }

      // buat chart dan parsing $labels dan $values
      $chart = Charts::create('pie', 'highcharts')
                     ->setTitle('Transaction Report by Category')
                     ->setLabels($labels)
                     ->setValues($values)
                     ->setDimensions(1000,500)
                     ->setResponsive(false);

      return $chart;
   }
}
