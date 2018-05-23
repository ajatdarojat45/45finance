@extends('layouts.header')
@section('title')
   Transaction
@endsection
@section('content')
<div class="container">
	@if (session('success'))
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>Success!</strong> {{session('success') }}
	</div>
	@endif
	@if (session('warning'))
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<strong>Success!</strong> {{session('warning') }}
	</div>
	@endif
	<center>
		<a href="{{route('dashboard')}}" style="color:#808080">/Dashboard</a>
		<b style="color:#808080">/Transaction</b>
	</center>
	{{-- form --}}
	<form style="border: 1px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('transaction/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		<label for="">Import your file here (Excel : .xls / .xlsx) :</label>
		<div class="row">
		  <div class="col-lg-3 col-md-3">
			  <input type="file" name="import_file" />
		  </div>
		  <button class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> Import File</button>
		</div>
	</form>
	{{-- form --}}
	<br>
	<div class="row">
		<div class="col-lg-12 col-md-12">
			<div class="tabs-container">
				<ul class="nav nav-tabs">
					<li class="active"><a data-toggle="tab" href="#deposit"> Transaction</a></li>
					<li class=""><a data-toggle="tab" href="#graph"> Graph</a></li>
				</ul>
				<div class="tab-content">
					{{-- Deposit --}}
					<div id="deposit" class="tab-pane active">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6 col-lg-6">
                           <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">
                              <i class="fa fa-plus-circle"></i> Add
                           </button><br><br>
   								 <label for="formGroupExampleInput">Search :</label>
									 <form class="" action="{{route('transaction/index')}}" method="get">
                               <div class="input-group" style="margin-bottom:5px;">
                                  <span class="input-group-addon"><i class="fa fa-google-wallet"></i> Wallet</span>
                                  <select name="wallet" style="height:32px" class="form-control" required="required">
                                      <option value="">-- Please select wallet --</option>
                                      <option value="all" @if ($dompet == 'all') selected @endif>-- All --</option>
                                      @foreach ($wallets as $wallet)
                                         <option value="{{$wallet->wallet}}" @if ($dompet == $wallet->wallet)selected @endif>
                                            {{$wallet->wallet}}
                                         </option>
                                      @endforeach
                                  </select>
                               </div>
                               <div class="input-group" style="margin-bottom:5px;">
                                  <span class="input-group-addon"><i class="fa fa-archive"></i> Category</span>
                                  <select name="category" style="height:32px" class="form-control" required="required">
                                      <option value="">-- Please select category --</option>
                                      <option value="all" @if ($kategori == 'all') selected @endif>-- All --</option>
                                      @foreach ($categories as $category)
                                         <option value="{{$category->category}}" @if ($kategori == $category->category) selected @endif>
                                            {{$category->category}}
                                         </option>
                                      @endforeach
                                  </select>
                               </div>
										 <div class="input-group">
											 <div class="" id="data_1">
												 <div class="input-group date">
													 <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="{{$date1}}" name="date1">
												 </div>
											 </div>
											 <div class="input-group-addon"><b>to</b></div>
											 <div class="" id="data_1_1">
												 <div class="input-group date">
													 <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													 <input type="text" class="form-control" value="{{$date2}}" name="date2">
												 </div>
											 </div>
											 <span class="input-group-btn">
												 <button type="submit" class="btn btn-primary" data-toggle="tooltip" title="Cari">
													 <i class="fa fa-search"></i> Search
												 </button>
											 </span>
										 </div>
									 </form>
   							</div>
								<div class="col-md-6 col-lg-6">
									<label for="formGroupExampleInput" class="pull-right">Export to :</label><br><br>
									<button type="button" style="margin-left:5px;" class="btn btn-danger btn-sm pull-right" data-toggle="tooltip" title="Export ke PDF" onclick="window.open('{{ route('transaction/exportToPdf', ['date1' => date('y-m-d', strtotime($date1)), 'date2' => date('y-m-d', strtotime($date2)), 'wallet' => $dompet, 'category' => $kategori]) }}', '_blank');">
										<i class="fa fa-file-pdf-o"></i> PDF
									</button>
									<button type="button" class="btn btn-success btn-sm pull-right" data-toggle="tooltip" title="Export ke PDF" onclick="window.open('{{ route('transaction/exportToExcel', ['date1' => date('y-m-d', strtotime($date1)), 'date2' => date('y-m-d', strtotime($date2)), 'type' => 'xlsx', 'wallet' => $dompet, 'category' => $kategori]) }}', '_blank');">
										<i class="fa fa-file-excel-o"></i> Excel
									</button>
								</div>
							</div>
							<br>
							<div class="table-responsive">
                        <form class="" action="{{route('transaction/multipleDestroy')}}" method="post">
                           {{csrf_field()}}
   								<table id="example1" class="table table-hover table-striped">
   									 <thead>
   										  <tr>
                                      <th></th>
   												<th style="text-align: center;">No</th>
                                       {{-- <th style="text-align: center;">Currency</th> --}}
   												<th style="text-align: center;">Wallet</th>
   												<th style="text-align: center;">Category</th>
   												<th style="text-align: center;">Note</th>
   												<th style="text-align: center;">Credit</th>
   												<th style="text-align: center;">Debit</th>
   												<th style="text-align: center;">Balance</th>
   												<th style="text-align: center;">Date</th>
                                       <th style="text-align: center;">Action</th>
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
                                        <td class="text-center">
                                           <input type="checkbox" name="transactions[]" value="{{$transaction->id}}">
                                        </td>
   												 <td class="text-center">{{++$no}}</td>
   												 {{-- <td class="text-left">{{$transaction->currency}}</td> --}}
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
   												 <td class="text-center">
                                           <a href="{{route('transaction/destroy', $transaction->id)}}" class="btn btn-danger btn-sm btn-outline confirm"><i class="fa fa-trash"></i> </a>
                                        </td>
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
                                   <th colspan="5" style="text-align: center">Subtotal</th>
                                   <th style="text-align: right; color:green;">{{GlobalHelper::f_currency($creditTotal)}} </th>
                                   <th style="text-align: right; color:red;">{{GlobalHelper::f_currency($debetTotal)}}</th>
                                   <th style="text-align: right;">
                                       {{ GlobalHelper::f_currency($creditTotal + $debetTotal) }}
                                   </th>
                                   <th></th>
                                   <th></th>
                               </tr>
                               <tr>
                                   <th colspan="5" style="text-align: center">Total</th>
                                   <th style="text-align: right;">{{GlobalHelper::f_currency($data['total'])}} </th>
                                   <th style="text-align: right;">{{GlobalHelper::f_currency($creditTotal + $debetTotal) }}</th>
                                   <th style="text-align: right;">
                                       {{GlobalHelper::f_currency($data['total'] + $creditTotal + $debetTotal) }}
                                   </th>
                                   <th></th>
                                   <th></th>
                               </tr>
                               <tr>
                                   <th colspan="10" style="text-align: center">
                                      <button type="submit" name="button" class="btn btn-danger btn-sm btn-block" onclick="javasciprt: return confirm('Are you sure, to delete this data?')">
                                         <i class="fa fa-trash"></i> Delete
                                       </button>
                                   </th>
                               </tr>
                               </tfoot>
   								</table>
                        </form>
							</div>
						</div>
					</div>
					{{-- depositr --}}
					{{-- graph --}}
					<div id="graph" class="tab-pane">
						<div class="panel-body">
							<div class="text-center table-responsive">
                        @php
                           if (count($debetValues) != 0) {
                              foreach ($debetValues as $debetValue => $value) {
                                 $debetValuesConvert[] = $value * -1;
                              }
                           }else{
                              $debetValuesConvert[] = '';
                           }
                        @endphp
								{!! Charts::multi('line', 'highcharts')
									->setTitle('Transaction Graph')
									->setColors(['#ff0000', '#0000ff', '#00ff00'])
									->setLabels($labels)
                           ->setDataset('Debet', $debetValuesConvert)
									->setDataset('Credit', $creditValues)
									->setDataset('Balance', $balanceValues)
									->setDimensions(1000,500)
									->setResponsive(false)
									->render();
								!!}
							</div>
						</div>
					</div>
					{{-- graph --}}
				</div>
			</div>
		</div>
	</div>
   <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
      <form class="" action="{{route('transaction/store')}}" method="post">
     <div class="modal-dialog">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
           <h4 class="modal-title" id=""></h4>
         </div>
         <div class="modal-body">
               <fieldset class="form-horizontal">
                  <div class="form-group">
                     @if ($errors->has('nominal'))
                        <span class="help-block">
                           <strong style="color: red">{{ $errors->first('nominal') }}</strong>
                        </span>
                     @endif
                     <label class="col-sm-2 control-label">Nominal</label>
                     <div class="col-sm-10">
                        <input type="number" name="nominal" class="form-control" value="{{ old('nominal') }}">
                     </div>
                  </div>
                  <div class="form-group">
                     @if ($errors->has('name'))
                        <span class="help-block">
                           <strong style="color: red">{{ $errors->first('type') }}</strong>
                        </span>
                     @endif
                     <label class="col-sm-2 control-label">Type</label>
                     <div class="col-sm-10">
                        <select name="type" id="input" class="form-control" required="required" style="height:35px;">
                           <option value="">-- Select Type --</option>
                           <option value="-1">Debet</option>
                           <option value="1">Credit</option>
                        </select>
                     </div>
                  </div>
                  <div class="form-group">
                     @if ($errors->has('wallet'))
                        <span class="help-block">
                           <strong style="color: red">{{ $errors->first('wallet') }}</strong>
                        </span>
                     @endif
                     <label class="col-sm-2 control-label">Wallet</label>
                     <div class="col-sm-10">
                        <input type="text" name="wallet" class="form-control" value="{{ old('wallet') }}">
                     </div>
                  </div>
                  <div class="form-group">
                     @if ($errors->has('category'))
                        <span class="help-block">
                           <strong style="color: red">{{ $errors->first('category') }}</strong>
                        </span>
                     @endif
                     <label class="col-sm-2 control-label">Category</label>
                     <div class="col-sm-10">
                        <input type="text" name="category" class="form-control" value="{{ old('category') }}">
                     </div>
                  </div>
                  <div class="form-group">
                     @if ($errors->has('fimz_id'))
                        <span class="help-block">
                           <strong style="color: red">{{ $errors->first('date') }}</strong>
                        </span>
                     @endif
                     <label class="col-sm-2 control-label">Date</label>
                     <div class="col-sm-10">
                        <div class="" id="data_1">
                           @if ($errors->has('start'))
                              <span class="help-block">
                                 <strong style="color: red"></strong>
                              </span>
                           @endif
                           <div class="input-group date">
                              <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" value="{{ old('date') }}" name="date">
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     @if ($errors->has('fimz_id'))
                        <span class="help-block">
                           <strong style="color: red">{{ $errors->first('note') }}</strong>
                        </span>
                     @endif
                     <label class="col-sm-2 control-label">Note</label>
                     <div class="col-sm-10">
                        <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                     </div>
                  </div>
                  {{csrf_field()}}
               </fieldset>
         </div>
         <div class="modal-footer">
           <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
           <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
         </div>
       </div>
     </div>
  </form>
   </div>
@endsection
