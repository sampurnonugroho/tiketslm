@extends('layouts.maps')
@extends('layouts.menu_left')

@section('content')
	<div class="container-fluid">
		<!-- Breadcrumb-->
		<div class="row pt-2 pb-2">
			<div class="col-sm-9">
				<h4 class="page-title">Google Maps</h4>
				<ol class="breadcrumb">
					<li class="breadcrumb-item"><a href="javaScript:void();">Dashtreme</a></li>
					<li class="breadcrumb-item"><a href="javaScript:void();">Maps</a></li>
					<li class="breadcrumb-item active" aria-current="page">Google Maps</li>
				</ol>
			</div>
			<div class="col-sm-3">
				<div class="btn-group float-sm-right">
					<button type="button" class="btn btn-light waves-effect waves-light"><i class="fa fa-cog mr-1"></i> Setting</button>
					<button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split waves-effect waves-light" data-toggle="dropdown">
						<span class="caret"></span>
					</button>
					<div class="dropdown-menu">
						<a href="javaScript:void();" class="dropdown-item">Action</a>
						<a href="javaScript:void();" class="dropdown-item">Another action</a>
						<a href="javaScript:void();" class="dropdown-item">Something else here</a>
						<div class="dropdown-divider"></div>
						<a href="javaScript:void();" class="dropdown-item">Separated link</a>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumb-->
		<div class="row">
			<div id="info"></div>
			<div class="col-lg-12">
				<div class="card">
					<div class="card-header text-uppercase">Map With Marker
						
						<select id="zoom" class="pull-right">
							<option value="">Pilih</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
						</select>

						<select id="location" class="pull-right">
							<option value="">Pilih</option>
							<option value="106.84513,-6.21462">Jakarta</option>
						</select>
					</div>
					<div class="card-body">
						<div id="marker-map" class="gmaps"></div>
					</div>
				</div>
			</div>
		</div>
		<!--End Row-->

	</div>
	<!-- End container-fluid-->
@endsection