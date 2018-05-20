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
      <b style="color:#808080">/Report</b>
		<b style="color:#808080">/Transaction Report by Category</b>
	</center>
	{{-- form --}}
	<form style="border: 1px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('transaction/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		<label for="">Import your file here (.xsl & .xslx) :</label>
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
					<li class="active"><a data-toggle="tab" href="#deposit"> Transaction Report</a></li>
					{{-- <li class=""><a data-toggle="tab" href="#graph"> Graph</a></li> --}}
				</ul>
				<div class="tab-content">
					{{-- Deposit --}}
					<div id="deposit" class="tab-pane active">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6 col-lg-6">
   								 <label for="formGroupExampleInput">Search :</label>
									 <form class="" action="{{route('transaction/reportByCategory')}}" method="get">
                               <div class="input-group" style="margin-bottom:5px;">
                                  <span class="input-group-addon"><i class="fa fa-exchange"></i> Type</span>
                                  <select name="type" style="height:32px" class="form-control" required="required">
                                      <option value="">-- Please select type --</option>
                                      <option value="debet"  @if ($type == 'debet') selected @endif>Debet</option>
                                     <option value="credit"  @if ($type == 'credit') selected @endif>Credit</option>
                                  </select>
                               </div>
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

								</div>
							</div>
							<br>
							<div class="table-responsive">
                        {!! $chart->render() !!}
							</div>
						</div>
					</div>
					{{-- depositr --}}
					{{-- graph --}}
					{{-- <div id="graph" class="tab-pane">
						<div class="panel-body">
							<div class="text-center table-responsive">
								{!! Charts::multi('line', 'highcharts')
									->setTitle('Transaction Graph')
									->setColors(['#ff0000', '#0000ff', '#00ff00'])
									->setLabels($labels)
                           ->setDataset('Debet', $debetValues)
									->setDataset('Credit', $creditValues)
									->setDataset('Balance', $balanceValues)
									->setDimensions(1000,500)
									->setResponsive(false)
									->render();
								!!}
							</div>
						</div>
					</div> --}}
					{{-- graph --}}
				</div>
			</div>
		</div>
	</div>
@endsection
