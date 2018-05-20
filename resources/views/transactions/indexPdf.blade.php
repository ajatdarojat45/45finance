<!DOCTYPE html>
<html>
   {{-- <head> --}}
      <title>Transaction Report - 45 Reload</title>
      <link href="{{ asset('inspinia/css/bootstrap.min.css') }}" rel="stylesheet">
      <link href="{{ asset('inspinia/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
   {{-- </head> --}}
   <body>
      <div style="font-size: 13px; text-align: right; color:#5E5B5C">
         <i>Print date
         {{ date("d/m/Y H:i:s") }} by {{ Auth::user()->name }}
         </i>
      </div>
      <div style="color: #5E5B5C">
         <center><b style="text-transform: uppercase; color:#5E5B5C;"><h4>Transaction Report</h4></b></center><br>
         <p>From <b><i>{{date('d M. Y', strtotime($date1))}}</i></b> to <b><i>{{date('d M. Y', strtotime($date2))}}</i></b></p>
         <table id="example1" class="table table-hover table-striped">
             <thead>
                 <tr>
                     <th style="text-align: center;">No</th>
                     <th style="text-align: center;">Wallet</th>
                     <th style="text-align: center;">Category</th>
                     <th style="text-align: center;">Note</th>
                     <th style="text-align: center;">Credit</th>
                     <th style="text-align: center;">Debit</th>
                     <th style="text-align: center;">Balance</th>
                     <th style="text-align: center;">Date</th>
                 </tr>
             </thead>
             <tbody>
                @php
                  if (count($data['transactions']) == 0) {
                     $labels[] = 'Start';
                     $debetValues[] = 0;
                     $creditValues[] = 0;
                     $balanceValues[] = 0;
                   }

                   $credit = 0;
                   $debet = 0;
                   $debetTotal = 0;
                   $creditTotal = 0;
                   $balance = 0;
                   $total = $data['total'];
                @endphp
                @foreach ($data['transactions'] as $transaction)
                   <tr>
                      <td class="text-center">{{++$no}}</td>
                      <td class="text-left">{{$transaction->wallet}}</td>
                      <td class="text-left">{{$transaction->category}}</td>
                      <td class="text-left">{{$transaction->note}}</td>
                      <td class="text-right" style="color:green;">
                         @if ($transaction->nominal >= 0)
                            @php
                            $credit =  $transaction->nominal;
                            $creditTotal = $creditTotal + $credit;
                            @endphp
                               {{GlobalHelper::f_currency($transaction->nominal)}}
                         @endif
                      </td>
                      <td class="text-right" style="color:red;">
                         @if ($transaction->nominal < 0)
                            @php
                            $debet =  $transaction->nominal;
                            $debetTotal = $debetTotal + $debet;
                            @endphp
                               {{GlobalHelper::f_currency($transaction->nominal)}}
                         @endif
                      </td>
                      <td class="text-right">
                            {{ GlobalHelper::f_currency($balance = $total + $creditTotal + $debetTotal) }}
                      </td>
                      <td class="text-center">{{date('d M. Y', strtotime($transaction->date))}}</td>
                   </tr>
                   @php
                     $labels[] = date('d M. Y', strtotime($transaction->date));
                     $debetValues[] = $debetTotal;
                     $creditValues[] = $creditTotal;
                     $balanceValues[] = $balance;
                   @endphp
                @endforeach
             </tbody>
             <tfoot>
             <tr>
                 <th colspan="4" style="text-align: center">Subtotal</th>
                 <th style="text-align: right; color:green;">{{GlobalHelper::f_currency($creditTotal)}} </th>
                 <th style="text-align: right; color:red;">{{GlobalHelper::f_currency($debetTotal)}}</th>
                 <th style="text-align: right;">
                     {{ GlobalHelper::f_currency($creditTotal + $debetTotal) }}
                 </th>
                 <th></th>
             </tr>
             <tr>
                 <th colspan="4" style="text-align: center">Total</th>
                 <th style="text-align: right;">{{GlobalHelper::f_currency($data['total'])}} </th>
                 <th style="text-align: right;">{{GlobalHelper::f_currency($creditTotal + $debetTotal) }}</th>
                 <th style="text-align: right;">
                     {{GlobalHelper::f_currency($data['total'] + $creditTotal + $debetTotal) }}
                 </th>
                 <th></th>
             </tr>
             </tfoot>
         </table>
      </div>
   </body>
</html>
